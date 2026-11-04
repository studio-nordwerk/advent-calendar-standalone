<?php

declare(strict_types=1);

// Lädt die Inhalte. Eine Datei inhalte/kalender.local.php hat Vorrang (zum Testen, wird nicht ausgeliefert).
function adventskalender_config(): array
{
    $dir = dirname(__DIR__).'/inhalte';

    return require is_file($dir.'/kalender.local.php') ? $dir.'/kalender.local.php' : $dir.'/kalender.php';
}
