<?php

declare(strict_types=1);

/*
 * Zeichnet den Adventskalender auf eine PHP-Seite:
 *
 *   <link rel="stylesheet" href="adventskalender/advent-calendar.css">
 *   …
 *   <?php include __DIR__.'/adventskalender/einbinden.php'; ?>
 *   …
 *   <script src="adventskalender/advent-calendar.js" defer></script>
 *
 * Für reine HTML-Seiten steht ein fertiger Ausschnitt in der README.md.
 */

require_once __DIR__.'/lib/DoorRules.php';
require_once __DIR__.'/lib/config.php';

(static function (): void {
    $config = adventskalender_config();
    $base = $GLOBALS['adventskalender_pfad'] ?? 'adventskalender';
    $year = (int) ($config['jahr'] ?? 0) ?: (int) (new DateTimeImmutable('now', new DateTimeZone($config['zeitzone'] ?? 'Europe/Berlin')))->format('Y');
    $days = HeikeWiese\ContaoAdventCalendarBundle\Calendar\DoorRules::order(crc32($config['jahr'].'adventskalender'), (bool) ($config['gemischt'] ?? true));
    $e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES);
    $demo = (bool) ($config['demo'] ?? false);
    $background = (string) ($config['hintergrund'] ?? '');
    // 'weihnachtsstern' = mitgeliefertes Bild „Weihnachtsstern“ (KI-generiert mit Gemini)
    $poinsettia = 'weihnachtsstern' === $background;
    if ($poinsettia) {
        $background = $base.'/hintergrund-weihnachtsstern.jpg';
    }
    $testDate = ($config['testdatum_erlaubt'] ?? false) && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) ($_GET['heute'] ?? '')) ? $_GET['heute'] : '';
    ?>
<div class="advent-calendar<?= $background ? ' advent-calendar--image' : '' ?><?= $poinsettia ? ' advent-calendar--poinsettia' : '' ?>"
     data-advent-calendar="<?= $e($base) ?>/tuer.php<?= $testDate ? '?heute='.$testDate : '' ?>"
     data-year="<?= $year ?>"<?= $testDate ? ' data-today="'.$testDate.'"' : '' ?>
     data-keep-open="<?= ($config['vergangene_offen'] ?? true) ? 1 : 0 ?>"
     data-demo="<?= $demo ? 1 : 0 ?>"
     <?= ($config['schnee'] ?? true) ? 'data-snow' : '' ?>
     data-text-early="<p class=&quot;advent-calendar__kicker&quot;>Noch ein bisschen Geduld</p><h2>Dieses Türchen öffnet sich am %s. Dezember.</h2><p>Bis dahin: Schauen Sie gern ins heutige Türchen.</p>"
     data-text-past="<p class=&quot;advent-calendar__kicker&quot;>Schon vorbei</p><h2>Dieses Türchen hat sich wieder geschlossen.</h2><p>Das heutige Türchen wartet auf Sie.</p>"
     data-text-empty="<h2>Hier ist heute leider nichts drin.</h2>"
     data-text-error="<h2>Das hat gerade nicht geklappt.</h2><p>Bitte versuchen Sie es gleich noch einmal.</p>">
<?php if ($background): ?>
    <div class="advent-calendar__background" aria-hidden="true"><figure><img src="<?= $e($background) ?>" alt=""></figure></div>
<?php endif; ?>
<?php if ($demo): ?>
    <p class="advent-calendar__demo">Vorschau: Alle Türchen sind zum Ausprobieren geöffnet.</p>
<?php endif; ?>
    <ol class="advent-calendar__grid">
<?php foreach ($days as $day): ?>
        <li class="advent-calendar__item<?= 24 === $day ? ' advent-calendar__item--big' : '' ?>"><button type="button" class="advent-calendar__door" data-day="<?= $day ?>" aria-haspopup="dialog" aria-label="Türchen <?= $day ?>"><span class="advent-calendar__number"><?= $day ?></span></button></li>
<?php endforeach; ?>
    </ol>
    <dialog class="advent-calendar__dialog" aria-label="Türchen">
        <form method="dialog" class="advent-calendar__close-form"><button class="advent-calendar__close" aria-label="Schließen">×</button></form>
        <div class="advent-calendar__content" aria-live="polite"></div>
    </dialog>
</div>
<?php
})();
