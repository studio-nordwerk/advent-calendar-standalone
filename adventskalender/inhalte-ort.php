<?php

/*
 * Wo liegen die Inhalte (kalender.php und der Ordner bilder)?
 *
 * Ab Werk im Ordner adventskalender/inhalte, geschützt per .htaccess (Apache).
 *
 * Am sichersten liegen sie außerhalb des Webverzeichnisses, also dort, wo kein Besucher hinkommt –
 * egal, welcher Server läuft. Dafür den Ordner inhalte eine Ebene über das Webverzeichnis
 * verschieben, ihn z. B. in adventskalender-inhalte umbenennen und hier eintragen:
 *
 *   return dirname(__DIR__, 2).'/adventskalender-inhalte';
 *
 * (dirname(__DIR__, 2) ist der Ordner über dem Webverzeichnis, wenn adventskalender direkt darin liegt.)
 */

return __DIR__.'/inhalte';
