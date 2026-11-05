<?php

declare(strict_types=1);

require_once __DIR__.'/DoorRules.php';

/**
 * Ordner mit kalender.php und bilder/. Steht in adventskalender/inhalte-ort.php und lässt sich dort
 * außerhalb des Webverzeichnisses legen.
 */
function adventskalender_inhalte_dir(): string
{
    $dir = require dirname(__DIR__).'/inhalte-ort.php';

    return rtrim((string) $dir, '/\\');
}

/**
 * Lädt die Inhalte. Eine Datei kalender.local.php hat Vorrang (zum Testen, wird nicht ausgeliefert).
 */
function adventskalender_config(): array
{
    $dir = adventskalender_inhalte_dir();
    $file = is_file($dir.'/kalender.local.php') ? $dir.'/kalender.local.php' : $dir.'/kalender.php';

    if (!is_file($file)) {
        throw new RuntimeException('Adventskalender: '.$file.' fehlt. Bitte den Pfad in adventskalender/inhalte-ort.php prüfen.');
    }

    return require $file;
}

/**
 * Heutiges Datum in der Zeitzone des Kalenders. Mit 'testdatum_erlaubt' => true gilt ?heute=JJJJ-MM-TT.
 *
 * @return array{0: DateTimeImmutable, 1: string} Datum und, falls gesetzt, das gültige Testdatum
 */
function adventskalender_today(array $config): array
{
    $timezone = new DateTimeZone($config['zeitzone'] ?? 'Europe/Berlin');
    $requested = (string) ($_GET['heute'] ?? '');

    if (($config['testdatum_erlaubt'] ?? false) && '' !== $requested) {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $requested, $timezone);

        if ($date && $date->format('Y-m-d') === $requested) {
            return [$date, $requested];
        }
    }

    return [new DateTimeImmutable('now', $timezone), ''];
}

/**
 * Öffentliche Adresse des Ordners adventskalender, z. B. '/adventskalender' oder '/shop/adventskalender'.
 * Wird aus dem Webverzeichnis ermittelt; 'adresse' in kalender.php hat Vorrang.
 */
function adventskalender_url(array $config): string
{
    if (!empty($config['adresse'])) {
        return rtrim((string) $config['adresse'], '/');
    }

    $root = realpath((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $dir = realpath(dirname(__DIR__));

    if ($root && $dir) {
        $root = rtrim(str_replace('\\', '/', $root), '/');
        $dir = str_replace('\\', '/', $dir);

        if (str_starts_with($dir.'/', $root.'/')) {
            // Ordnernamen mit Leer- oder Sonderzeichen (#, ?, %) für die Adresse kodieren
            return implode('/', array_map('rawurlencode', explode('/', substr($dir, strlen($root)))));
        }
    }

    return 'adventskalender';
}
