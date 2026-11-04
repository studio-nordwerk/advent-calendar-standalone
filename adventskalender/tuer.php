<?php

declare(strict_types=1);

/*
 * Liefert ein Türchen, aber nur, wenn sein Tag gekommen ist.
 *
 *   tuer.php?day=5          → {"state":"open","html":"…","thumb":"…"} oder {"state":"early"} / {"state":"past"} / {"state":"empty"}
 *   tuer.php?day=5&bild=1   → das Bild des Türchens (nur, wenn es offen ist)
 *
 * Dieselbe Antwort liefert auch das Contao-Paket. Wer den Kalender in eine andere
 * Umgebung einbaut (z. B. Astro), muss nur diese kleine Schnittstelle nachbauen.
 */

require __DIR__.'/lib/DoorRules.php';
require __DIR__.'/lib/config.php';

use HeikeWiese\ContaoAdventCalendarBundle\Calendar\DoorRules;

$config = adventskalender_config();
$day = (int) ($_GET['day'] ?? 0);
$timezone = new DateTimeZone($config['zeitzone'] ?? 'Europe/Berlin');
$today = new DateTimeImmutable('now', $timezone);
$testDate = '';

if (($config['testdatum_erlaubt'] ?? false) && preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) ($_GET['heute'] ?? ''))) {
    $testDate = $_GET['heute'];
    $today = new DateTimeImmutable($testDate, $timezone);
}

$state = DoorRules::state($day, $today, (int) ($config['jahr'] ?? 0), (bool) ($config['vergangene_offen'] ?? true), (bool) ($config['demo'] ?? false));
$door = $config['tuerchen'][$day] ?? null;

header('Cache-Control: private, no-store');
header('X-Robots-Tag: noindex');

// Bild ausliefern
if (isset($_GET['bild'])) {
    $file = $door['bild'] ?? '';
    $path = realpath(__DIR__.'/inhalte/bilder/'.basename($file));

    if (DoorRules::OPEN !== $state || !$file || !$path || !is_file($path)) {
        http_response_code(404);
        exit;
    }

    header('Content-Type: '.(mime_content_type($path) ?: 'application/octet-stream'));
    header('Content-Length: '.filesize($path));
    readfile($path);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

if (DoorRules::OPEN !== $state) {
    echo json_encode(['state' => $state]);
    exit;
}

if (!$door) {
    echo json_encode(['state' => 'empty']);
    exit;
}

$e = static fn (?string $s): string => htmlspecialchars((string) $s, ENT_QUOTES);
$image = !empty($door['bild'])
    ? $_SERVER['SCRIPT_NAME'].'?day='.$day.'&bild=1'.($testDate ? '&heute='.$testDate : '')
    : null;

ob_start();
?>
<article class="advent-calendar__door-content">
<?php if (!empty($door['dachzeile'])): ?>    <p class="advent-calendar__kicker"><?= $e($door['dachzeile']) ?></p>
<?php endif; ?>
    <h2 class="advent-calendar__headline"><?= $e($door['ueberschrift'] ?? '') ?></h2>
<?php if ($image): ?>    <figure><img src="<?= $e($image) ?>" alt="" loading="lazy"></figure>
<?php endif; ?>
<?php if (!empty($door['text'])): ?>    <div class="advent-calendar__text"><?= $door['text'] ?></div>
<?php endif; ?>
<?php if (!empty($door['preis'])): ?>    <p class="advent-calendar__price"><span class="advent-calendar__price-new"><?= $e($door['preis']) ?></span><?php if (!empty($door['alter_preis'])): ?> <s class="advent-calendar__price-old"><?= $e($door['alter_preis']) ?></s><?php endif; ?></p>
<?php endif; ?>
<?php if (!empty($door['code'])): ?>    <p class="advent-calendar__coupon"><span>Ihr Code:</span><button type="button" class="advent-calendar__code" data-copy="<?= $e($door['code']) ?>" title="Code kopieren"><?= $e($door['code']) ?></button></p>
<?php endif; ?>
<?php if (!empty($door['link'])): ?>    <p class="advent-calendar__action"><a class="advent-calendar__button" href="<?= $e($door['link']) ?>"<?= !empty($door['neues_fenster']) ? ' target="_blank" rel="noopener"' : '' ?>><?= $e(($door['button'] ?? '') ?: 'Zum Angebot') ?></a></p>
<?php endif; ?>
</article>
<?php
echo json_encode(['state' => 'open', 'html' => ob_get_clean(), 'thumb' => $image]);
