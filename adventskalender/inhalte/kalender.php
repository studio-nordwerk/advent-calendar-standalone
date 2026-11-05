<?php

/*
 * Inhalte des Adventskalenders
 *
 * Dieser Ordner ist für Besucher gesperrt (.htaccess bzw. Server-Einstellung, siehe README). Texte und Bilder kommen
 * erst an ihrem Tag über tuer.php auf die Seite – vorab lässt sich nichts ansehen.
 *
 * Bilder in den Ordner inhalte/bilder legen (Querformat, etwa 1200 × 800 Pixel, JPG oder WebP)
 * und hier nur den Dateinamen eintragen.
 */

return [
    // Kalenderjahr. 0 = jedes Jahr wieder
    'jahr' => 2026,

    // true = vergangene Türchen bleiben offen, false = nur das heutige Türchen geht auf
    'vergangene_offen' => true,

    // Vorschau: true = bis zum 30. November sind alle Türchen offen, zum Vorzeigen und Prüfen.
    // Am 1. Dezember endet die Vorschau von selbst. Sind schon echte Gutscheincodes eingetragen,
    // lieber false: Sonst sind sie bis dahin für alle sichtbar.
    'demo' => false,

    // Türchen gemischt anordnen (wie ein echter Kalender)
    'gemischt' => true,

    // Leichter Schneefall
    'schnee' => true,

    // Hintergrund: 'weihnachtsstern' = mitgelieferter Weihnachtsstern, ein eigenes Bild als
    // öffentlicher Pfad (z. B. '/bilder/winter.jpg') oder '' für keinen Hintergrund
    'hintergrund' => 'weihnachtsstern',

    'zeitzone' => 'Europe/Berlin',

    // Nur zum Testen: erlaubt seite.php?heute=2026-12-10. Für den echten Einsatz auf false!
    'testdatum_erlaubt' => false,

    // Öffentliche Adresse des Ordners adventskalender. Leer = wird selbst erkannt.
    // Nur nötig, wenn Bilder oder Türchen nicht laden, z. B. '/shop/adventskalender'
    'adresse' => '',

    // Felder je Türchen:
    //   dachzeile      kleine Zeile über der Überschrift, z. B. 'Unser Tagesangebot'
    //   ueberschrift   Pflicht
    //   bild           Dateiname in inhalte/bilder, z. B. 'tag-01.jpg' (leer = Stern statt Bild)
    //   text           HTML erlaubt, z. B. '<p>Zwei, drei Sätze.</p>'
    //   preis          z. B. '24,95 €'
    //   alter_preis    wird durchgestrichen
    //   code           Gutscheincode, lässt sich mit einem Klick kopieren
    //   link           z. B. 'https://www.ihr-shop.de/angebot'
    //   button         Text auf dem Knopf
    //   neues_fenster  true = Link öffnet in neuem Fenster
    // Ein Türchen weglassen = Hinweis „Hier ist heute leider nichts drin.“
    // Die Türchen unten sind ein Beispiel (Seifen, Düfte, Pflege für Männer) – einfach überschreiben.
    // Beispielbilder: KI-generiert mit Gemini. Link '#' durch die Adresse im eigenen Shop ersetzen.
    'tuerchen' => [
        1 => [
            'dachzeile' => 'Unser Tagesangebot',
            'ueberschrift' => 'Naturseifen im Dreierpack',
            'bild' => 'muster-01-seife.jpg',
            'text' => '<p>Lavendel, Honig und eine ganz milde ohne Duft – von Hand gesiedet und in Papier verpackt.</p>',
            'preis' => '14,90 €',
            'alter_preis' => '18,00 €',
            'code' => '',
            'link' => '#',
            'button' => 'Zum Angebot',
            'neues_fenster' => false,
        ],
        2 => [
            'dachzeile' => 'Gutschein',
            'ueberschrift' => '10 % auf Ihre ganze Bestellung',
            'bild' => '',
            'text' => '<p>Gilt heute und morgen für alles im Shop. Einfach den Code an der Kasse eingeben.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => 'ADVENT10',
            'link' => '#',
            'button' => 'Jetzt einlösen',
            'neues_fenster' => false,
        ],
        3 => [
            'dachzeile' => 'Gut zu wissen',
            'ueberschrift' => 'Geschenke schön und ohne Plastik verpacken',
            'bild' => '',
            'text' => '<p>Packpapier und Paketschnur, ein schönes Tuch zum Knoten oder ein Tannenzweig statt Schleife: So sieht jedes Geschenk liebevoll aus – und der Müll bleibt klein.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => '',
            'link' => '',
            'button' => '',
            'neues_fenster' => false,
        ],
        4 => [
            'dachzeile' => 'Duft der Woche',
            'ueberschrift' => 'Eau de Parfum „Winterholz“',
            'bild' => 'muster-02-parfum.jpg',
            'text' => '<p>Warm und würzig: Zedernholz, Orange und ein Hauch Zimt. 50 ml im schlichten Glasflakon.</p>',
            'preis' => '39,00 €',
            'alter_preis' => '49,00 €',
            'code' => '',
            'link' => '#',
            'button' => 'Zum Angebot',
            'neues_fenster' => false,
        ],
        5 => [
            'dachzeile' => 'Kleine Freude',
            'ueberschrift' => 'Eine Duftprobe gratis',
            'bild' => '',
            'text' => '<p>Zu jeder Bestellung legen wir Ihnen heute eine Probe unseres neuen Parfüms dazu.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => '',
            'link' => '#',
            'button' => 'Zum Shop',
            'neues_fenster' => false,
        ],
        6 => [
            'dachzeile' => 'Nikolaus – für Männer',
            'ueberschrift' => 'Bartpflege-Set',
            'bild' => 'muster-03-bart.jpg',
            'text' => '<p>Bartöl, Bartbalsam und ein Kamm aus Holz. Pflegt, duftet dezent und passt in jeden Stiefel.</p>',
            'preis' => '29,90 €',
            'alter_preis' => '36,00 €',
            'code' => '',
            'link' => '#',
            'button' => 'Zum Angebot',
            'neues_fenster' => false,
        ],
        7 => [
            'dachzeile' => 'Gutschein',
            'ueberschrift' => 'Heute versandkostenfrei',
            'bild' => '',
            'text' => '<p>Ab 30 € Bestellwert verschicken wir Ihre Bestellung heute ohne Versandkosten.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => 'FREIVERSAND',
            'link' => '#',
            'button' => 'Jetzt einlösen',
            'neues_fenster' => false,
        ],
        8 => [
            'dachzeile' => 'Geschenkidee',
            'ueberschrift' => 'Wichteln für unter 10 Euro',
            'bild' => '',
            'text' => '<p>Ein Stück Seife, ein Lippenbalsam oder ein Duftsäckchen für den Kleiderschrank: kleine Dinge, über die sich jeder freut.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => '',
            'link' => '#',
            'button' => 'Zur Auswahl',
            'neues_fenster' => false,
        ],
        9 => [
            'dachzeile' => 'Gemütlich',
            'ueberschrift' => 'Duftkerze „Zimt & Orange“',
            'bild' => 'muster-04-kerze.jpg',
            'text' => '<p>Aus Rapswachs im Glas, brennt rund 30 Stunden und duftet nach Weihnachtsmarkt.</p>',
            'preis' => '12,50 €',
            'alter_preis' => '15,90 €',
            'code' => '',
            'link' => '#',
            'button' => 'Zum Angebot',
            'neues_fenster' => false,
        ],
        10 => [
            'dachzeile' => 'Für ihn',
            'ueberschrift' => 'Festes Shampoo für Männer',
            'bild' => '',
            'text' => '<p>Mit Zedernholz und Minze, reicht so lange wie zwei Flaschen und braucht keine Verpackung aus Plastik.</p>',
            'preis' => '8,90 €',
            'alter_preis' => '',
            'code' => '',
            'link' => '#',
            'button' => 'Zum Angebot',
            'neues_fenster' => false,
        ],
        11 => [
            'dachzeile' => 'Gutschein',
            'ueberschrift' => '5 € geschenkt',
            'bild' => '',
            'text' => '<p>Ab 40 € Bestellwert ziehen wir an der Kasse 5 € ab. Gilt bis Sonntag.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => 'FUENF',
            'link' => '#',
            'button' => 'Jetzt einlösen',
            'neues_fenster' => false,
        ],
        12 => [
            'dachzeile' => 'Zum Selbermachen',
            'ueberschrift' => 'Duftende Orangen mit Nelken',
            'bild' => '',
            'text' => '<p>Eine Orange rundherum mit Gewürznelken spicken, nach Wunsch ein Band herumbinden – fertig. Duftet wochenlang und ist ein schönes Mitbringsel.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => '',
            'link' => '',
            'button' => '',
            'neues_fenster' => false,
        ],
        13 => [
            'dachzeile' => 'Zeit für sich',
            'ueberschrift' => 'Badesalz mit Rosenblüten',
            'bild' => 'muster-05-badesalz.jpg',
            'text' => '<p>Meersalz, Rosenblüten und ein paar Tropfen Rosenöl. Für ein warmes Bad an kalten Abenden.</p>',
            'preis' => '9,90 €',
            'alter_preis' => '12,50 €',
            'code' => '',
            'link' => '#',
            'button' => 'Zum Angebot',
            'neues_fenster' => false,
        ],
        14 => [
            'dachzeile' => 'Service',
            'ueberschrift' => 'Geschenkverpackung gratis',
            'bild' => '',
            'text' => '<p>Bis Weihnachten verpacken wir jede Bestellung auf Wunsch kostenlos als Geschenk. Einfach in der Kasse ankreuzen.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => '',
            'link' => '#',
            'button' => 'Zum Shop',
            'neues_fenster' => false,
        ],
        15 => [
            'dachzeile' => 'Duft der Woche',
            'ueberschrift' => 'Parfüm-Probierset',
            'bild' => '',
            'text' => '<p>Fünf Düfte zum Kennenlernen, je 2 ml. Den Preis rechnen wir beim Kauf einer großen Flasche wieder an.</p>',
            'preis' => '12,00 €',
            'alter_preis' => '',
            'code' => '',
            'link' => '#',
            'button' => 'Zum Angebot',
            'neues_fenster' => false,
        ],
        16 => [
            'dachzeile' => 'Gutschein',
            'ueberschrift' => 'Drei Seifen, nur zwei bezahlen',
            'bild' => '',
            'text' => '<p>Legen Sie drei Seifen in den Warenkorb – die günstigste schenken wir Ihnen.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => 'SEIFE3',
            'link' => '#',
            'button' => 'Jetzt einlösen',
            'neues_fenster' => false,
        ],
        17 => [
            'dachzeile' => 'Für Männer',
            'ueberschrift' => 'Rasierset mit Rasierseife',
            'bild' => 'muster-06-rasur.jpg',
            'text' => '<p>Rasierpinsel, Rasierhobel aus Metall und eine Schale Rasierseife. Für eine Rasur wie beim Barbier.</p>',
            'preis' => '44,00 €',
            'alter_preis' => '54,00 €',
            'code' => '',
            'link' => '#',
            'button' => 'Zum Angebot',
            'neues_fenster' => false,
        ],
        18 => [
            'dachzeile' => 'Gut zu wissen',
            'ueberschrift' => 'So hält Ihre Seife länger',
            'bild' => '',
            'text' => '<p>Nach dem Waschen gut abtropfen lassen und auf eine Seifenschale mit Rillen legen. Trocken gelagert hält ein Stück Naturseife oft zwei Monate.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => '',
            'link' => '',
            'button' => '',
            'neues_fenster' => false,
        ],
        19 => [
            'dachzeile' => 'Handpflege',
            'ueberschrift' => 'Handcreme mit Sheabutter',
            'bild' => '',
            'text' => '<p>Zieht schnell ein und pflegt raue Winterhände. Klein genug für jede Handtasche.</p>',
            'preis' => '7,90 €',
            'alter_preis' => '',
            'code' => '',
            'link' => '#',
            'button' => 'Zum Angebot',
            'neues_fenster' => false,
        ],
        20 => [
            'dachzeile' => 'Geschenkidee',
            'ueberschrift' => 'Geschenkbox nach Ihrem Wunsch',
            'bild' => 'muster-07-geschenkbox.jpg',
            'text' => '<p>Seife, Kerze und ein kleiner Duft in einer Box mit Holzwolle – Sie wählen aus, wir packen schön ein.</p>',
            'preis' => '34,90 €',
            'alter_preis' => '',
            'code' => '',
            'link' => '#',
            'button' => 'Box zusammenstellen',
            'neues_fenster' => false,
        ],
        21 => [
            'dachzeile' => 'Letzte Chance',
            'ueberschrift' => 'Heute bis 12 Uhr bestellen',
            'bild' => '',
            'text' => '<p>Was heute Mittag bestellt ist, kommt noch vor Weihnachten bei Ihnen an.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => '',
            'link' => '#',
            'button' => 'Zum Shop',
            'neues_fenster' => false,
        ],
        22 => [
            'dachzeile' => 'Last minute',
            'ueberschrift' => 'Geschenkgutschein zum Ausdrucken',
            'bild' => '',
            'text' => '<p>Sofort per E-Mail, mit Wunschbetrag und Ihrem persönlichen Gruß. Passt immer.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => '',
            'link' => '#',
            'button' => 'Gutschein kaufen',
            'neues_fenster' => false,
        ],
        23 => [
            'dachzeile' => 'Gutschein',
            'ueberschrift' => '15 % auf Ihr Lieblingsstück',
            'bild' => '',
            'text' => '<p>Ein Artikel Ihrer Wahl, 15 % günstiger. Gilt bis Heiligabend.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => 'LIEBLING15',
            'link' => '#',
            'button' => 'Jetzt einlösen',
            'neues_fenster' => false,
        ],
        24 => [
            'dachzeile' => 'Frohe Weihnachten',
            'ueberschrift' => 'Danke, dass Sie bei uns einkaufen!',
            'bild' => 'muster-08-weihnachten.jpg',
            'text' => '<p>Wir wünschen Ihnen ruhige, schöne Feiertage. Zwischen den Jahren sind wir vom 27. bis 30. Dezember für Sie da.</p>',
            'preis' => '',
            'alter_preis' => '',
            'code' => '',
            'link' => '',
            'button' => '',
            'neues_fenster' => false,
        ],
    ],
];
