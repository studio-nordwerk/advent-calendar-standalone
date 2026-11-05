<?php

/*
 * Wo liegen die Inhalte (kalender.php und der Ordner bilder)?
 *
 * Ab Werk im Ordner adventskalender/inhalte, geschützt per .htaccess (Apache).
 *
 * Am sichersten liegen sie außerhalb des Webverzeichnisses, also dort, wo kein Besucher hinkommt –
 * egal, welcher Server läuft. Dafür den Ordner inhalte neben das Webverzeichnis verschieben,
 * z. B. in adventskalender-inhalte umbenennen und hier den vollständigen Pfad eintragen:
 *
 *   return '/home/kunde/adventskalender-inhalte';
 *
 * Liegt adventskalender direkt im Webverzeichnis, geht auch: return dirname(__DIR__, 2).'/adventskalender-inhalte';
 * Die Prüfseite adventskalender/pruefen.php zeigt, ob der Pfad stimmt.
 */

return __DIR__.'/inhalte';
