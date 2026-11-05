<?php

declare(strict_types=1);

/*
 * Regeln, wann ein Türchen aufgeht. Ohne Abhängigkeiten:  php tests/DoorRulesTest.php
 */

require dirname(__DIR__).'/adventskalender/lib/DoorRules.php';

use Nordwerk\AdventCalendar\DoorRules;

$failed = 0;
$check = static function (string $name, mixed $actual, mixed $expected) use (&$failed): void {
    if ($actual !== $expected) {
        ++$failed;
        echo "FEHLER  $name: erwartet ".var_export($expected, true).', bekommen '.var_export($actual, true)."\n";
    }
};
$at = static fn (string $date): DateTimeImmutable => new DateTimeImmutable($date.' 12:00', new DateTimeZone('Europe/Berlin'));

// Vor dem Tag zu, am Tag offen
$check('5. Dezember, Tür 6', DoorRules::state(6, $at('2026-12-05'), 2026), DoorRules::EARLY);
$check('5. Dezember, Tür 5', DoorRules::state(5, $at('2026-12-05'), 2026), DoorRules::OPEN);
$check('30. November, Tür 1', DoorRules::state(1, $at('2026-11-30'), 2026), DoorRules::EARLY);

// Vergangene Türen: offen oder wieder zu
$check('vergangene offen', DoorRules::state(3, $at('2026-12-05'), 2026, true), DoorRules::OPEN);
$check('vergangene zu', DoorRules::state(3, $at('2026-12-05'), 2026, false), DoorRules::PAST);

// Ungültige Tage
$check('Tag 0', DoorRules::state(0, $at('2026-12-24'), 2026), DoorRules::EARLY);
$check('Tag 25', DoorRules::state(25, $at('2026-12-25'), 2026, true, true), DoorRules::EARLY);

// Festes Jahr: im Folgejahr bleibt der alte Kalender offen, im Vorjahr zu
$check('Vorjahr', DoorRules::state(1, $at('2025-12-24'), 2026), DoorRules::EARLY);
$check('Folgejahr', DoorRules::state(24, $at('2027-01-02'), 2026), DoorRules::OPEN);

// Jahr 0: jedes Jahr neu
$check('Jahr 0, Januar', DoorRules::state(1, $at('2027-01-02'), 0), DoorRules::EARLY);
$check('Jahr 0, Dezember', DoorRules::state(1, $at('2027-12-01'), 0), DoorRules::OPEN);

// Vorschau: bis 30. November alles offen, ab 1. Dezember normale Regeln
$check('Vorschau im November', DoorRules::state(24, $at('2026-11-15'), 2026, true, true), DoorRules::OPEN);
$check('Vorschau endet am 1. Dezember', DoorRules::state(24, $at('2026-12-01'), 2026, true, true), DoorRules::EARLY);
$check('Vorschau, 1. Dezember, Tür 1', DoorRules::state(1, $at('2026-12-01'), 2026, true, true), DoorRules::OPEN);
$check('Vorschau aktiv im November', DoorRules::demoActive($at('2026-11-30'), 2026, true), true);
$check('Vorschau aus im Dezember', DoorRules::demoActive($at('2026-12-01'), 2026, true), false);
$check('Vorschau ausgeschaltet', DoorRules::demoActive($at('2026-11-01'), 2026, false), false);

// Mischung: immer alle 24, für denselben Kalender gleich
$order = DoorRules::order(42, true);
$sorted = $order;
sort($sorted);
$check('alle 24 Türchen', $sorted, range(1, 24));
$check('gleiche Reihenfolge', DoorRules::order(42, true), $order);
$check('ungemischt', DoorRules::order(42, false), range(1, 24));

echo $failed ? "$failed Fehler\n" : "Alle Regeln in Ordnung\n";
exit($failed ? 1 : 0);
