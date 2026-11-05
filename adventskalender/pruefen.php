<?php

declare(strict_types=1);

/*
 * Prüfseite: https://ihre-seite.de/adventskalender/pruefen.php
 *
 * Zeigt, ob alles bereit ist und ob die Inhalte vor Besuchern geschützt sind. Verrät keine Inhalte,
 * keine Dateinamen und keine Codes. Wer mag, löscht die Datei nach der Prüfung.
 */

require __DIR__.'/lib/config.php';

use Nordwerk\AdventCalendar\DoorRules;

header('Cache-Control: no-store');
header('X-Robots-Tag: noindex');

$checks = [];
$add = static function (string $status, string $title, string $detail = '') use (&$checks): void {
    $checks[] = [$status, $title, $detail];
};

$add(PHP_VERSION_ID >= 80100 ? 'ok' : 'fehler', 'PHP 8.1 oder neuer', PHP_VERSION_ID >= 80100 ? '' : 'Bitte beim Hoster eine neuere PHP-Version einstellen.');

$config = null;

try {
    $config = adventskalender_config();
    $add('ok', 'Inhalte gefunden');
} catch (Throwable $e) {
    $add('fehler', 'Inhalte nicht gefunden', 'Pfad in adventskalender/inhalte-ort.php prüfen.');
}

$base = adventskalender_url($config ?? []);
$contentDir = adventskalender_inhalte_dir();
$root = realpath((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
$real = realpath($contentDir);
$outside = $root && $real && !str_starts_with(str_replace('\\', '/', $real).'/', rtrim(str_replace('\\', '/', $root), '/').'/');

if ($outside) {
    $add('ok', 'Inhalte liegen außerhalb des Webverzeichnisses', 'Am sichersten: Kein Besucher kommt an diese Dateien, egal welcher Server läuft.');
}

if (is_array($config)) {
    [$today] = adventskalender_today($config);
    $year = (int) ($config['jahr'] ?? 0);

    if (DoorRules::demoActive($today, $year, (bool) ($config['demo'] ?? false))) {
        $add('hinweis', 'Vorschau ist an: alle Türchen sind offen', 'Endet am 1. Dezember von selbst. Mit echten Gutscheincodes vorher \'demo\' => false setzen.');
    } else {
        $add('ok', 'Vorschau ist aus');
    }

    if ($config['testdatum_erlaubt'] ?? false) {
        $add('fehler', 'Testdatum ist erlaubt', 'Damit kann jeder mit ?heute=… künftige Türchen öffnen. In kalender.php \'testdatum_erlaubt\' => false setzen.');
    } else {
        $add('ok', 'Testdatum ist gesperrt');
    }

    if ($year && $year !== (int) $today->format('Y')) {
        $add('hinweis', 'Kalenderjahr ist '.$year, 'Heute ist '.$today->format('Y').'. In kalender.php \'jahr\' anpassen oder 0 für jedes Jahr.');
    }

    $doors = $config['tuerchen'] ?? [];
    $missing = [];
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'avif', 'gif'];

    foreach ($doors as $day => $door) {
        $file = (string) ($door['bild'] ?? '');

        if ('' !== $file && (!is_file($contentDir.'/bilder/'.basename($file)) || !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $allowed, true))) {
            $missing[] = $day;
        }
    }

    $add(count($doors) >= 24 ? 'ok' : 'hinweis', count($doors).' von 24 Türchen gefüllt', count($doors) >= 24 ? '' : 'Leere Türchen zeigen „Hier ist heute leider nichts drin.“');
    $add($missing ? 'fehler' : 'ok', $missing ? 'Bild fehlt oder hat ein falsches Format bei Türchen '.implode(', ', $missing) : 'Alle Bilder gefunden', $missing ? 'Erlaubt sind jpg, jpeg, png, webp, avif und gif.' : '');
}

$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES);
$labels = ['ok' => 'In Ordnung', 'hinweis' => 'Hinweis', 'fehler' => 'Bitte beheben'];
?>
<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Adventskalender prüfen</title>
    <style>
        body { margin: 0; padding: 2rem 1rem; font: 16px/1.5 system-ui, sans-serif; background: #f6f2e7; color: #3a342b; }
        main { max-width: 42rem; margin: 0 auto; }
        h1 { font-family: Georgia, serif; font-weight: 600; margin: 0 0 .25rem; }
        ul { list-style: none; padding: 0; margin: 1.5rem 0; display: grid; gap: .5rem; }
        li { background: #fffdf8; border-radius: .75rem; padding: .75rem 1rem; border-left: .35rem solid #b9b0a0; }
        li[data-status="ok"] { border-color: #458019; }
        li[data-status="hinweis"] { border-color: #d29b14; }
        li[data-status="fehler"] { border-color: #b3261e; }
        strong { display: block; }
        small { color: #6b6255; }
        .status { font-size: .8rem; text-transform: uppercase; letter-spacing: .04em; color: #6b6255; }
    </style>
</head>
<body>
<main>
    <h1>Adventskalender prüfen</h1>
    <p>Diese Seite verrät keine Inhalte. Grün heißt: alles in Ordnung.</p>
    <ul id="checks">
<?php foreach ($checks as [$status, $title, $detail]): ?>
        <li data-status="<?= $status ?>"><span class="status"><?= $labels[$status] ?></span><strong><?= $e($title) ?></strong><?php if ($detail): ?><small><?= $e($detail) ?></small><?php endif; ?></li>
<?php endforeach; ?>
<?php if (!$outside): ?>
        <li data-status="hinweis" id="protection"><span class="status">Wird geprüft …</span><strong>Schutz des Ordners inhalte</strong><small>Der Browser versucht, eine Testdatei aus dem Ordner direkt abzurufen.</small></li>
<?php endif; ?>
    </ul>
</main>
<?php if (!$outside): ?>
<script>
(async () => {
    const item = document.getElementById('protection');
    const set = (status, label, detail) => {
        item.dataset.status = status;
        item.querySelector('.status').textContent = label;
        item.querySelector('small').textContent = detail;
    };

    try {
        const response = await fetch(<?= json_encode($base.'/inhalte/schutz-test.txt') ?>, { cache: 'no-store' });
        const text = response.ok ? await response.text() : '';

        if (text.includes('NICHT')) {
            set('fehler', 'Bitte beheben', 'Bilder und Texte sind vorab abrufbar. Der Server beachtet die .htaccess nicht (z. B. nginx). Server-Einstellung aus der README übernehmen oder die Inhalte außerhalb des Webverzeichnisses ablegen.');
        } else {
            set('ok', 'In Ordnung', 'Der Ordner ist von außen gesperrt (Antwort ' + response.status + ').');
        }
    } catch (e) {
        set('hinweis', 'Hinweis', 'Konnte nicht geprüft werden. Bitte von Hand aufrufen: <?= $e($base) ?>/inhalte/schutz-test.txt – dort darf der Satz nicht erscheinen.');
    }
})();
</script>
<?php endif; ?>
</body>
</html>
