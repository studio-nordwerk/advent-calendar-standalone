# Adventskalender – ohne Contao

24 Türchen für jede Webseite mit PHP: Tagesangebote, Gutscheine, Rezepte oder kleine Geschichten.
Keine Datenbank, kein jQuery, keine Cookies.

**Kein Mogeln:** Texte und Bilder liegen in einem gesperrten Ordner. Ein Türchen kommt erst an seinem Tag über `tuer.php` auf die Seite – der Server prüft das Datum, nicht der Rechner des Besuchers.

## Was Sie brauchen

Webspace mit PHP 8.1 oder neuer (fast jedes Hosting-Paket) und Zugang per FTP.

## So geht's

1. Den Ordner `adventskalender` per FTP neben Ihre Webseite laden.
2. In `adventskalender/inhalte/kalender.php` die 24 Türchen eintragen. Ab Werk stehen dort Beispiele (Seifen, Düfte, Pflege für Männer, Gutscheine, Tipps) – einfach überschreiben, die Beispielbilder `muster-*.jpg` dürfen gelöscht werden. Die Bilder in `adventskalender/inhalte/bilder` legen (Querformat, etwa 1200 × 800 Pixel).
3. Auf der Seite, auf der der Kalender erscheinen soll:

   ```html
   <!-- im <head> -->
   <link rel="stylesheet" href="adventskalender/advent-calendar.css">

   <!-- dort, wo der Kalender stehen soll (die Seite muss auf .php enden) -->
   <?php include __DIR__.'/adventskalender/einbinden.php'; ?>

   <!-- vor </body> -->
   <script src="adventskalender/advent-calendar.js" defer></script>
   ```

   Eine fertige Beispielseite ist `beispiel.php`.
4. **Vor dem 1. Dezember:** in `kalender.php` `'demo' => false` setzen. Im Demo-Modus sind alle Türchen offen.

## Einstellungen in kalender.php

| Einstellung | Bedeutung |
|---|---|
| `jahr` | Kalenderjahr, `0` = jedes Jahr wieder |
| `vergangene_offen` | `true`: vergangene Türchen bleiben offen, `false`: nur das heutige geht auf |
| `demo` | `true`: alle Türchen offen, zum Vorzeigen. Geöffnete Türchen werden dabei nicht gemerkt |
| `gemischt` | Türchen gemischt anordnen wie bei einem echten Kalender |
| `schnee` | leichter Schneefall (entfällt bei „Bewegung reduzieren“) |
| `hintergrund` | `'weihnachtsstern'` = mitgelieferter Weihnachtsstern (Standard), ein eigenes Bild als öffentlicher Pfad, z. B. `'/bilder/winter.jpg'`, oder `''` für keinen Hintergrund |
| `testdatum_erlaubt` | nur zum Testen: dann zeigt `seite.php?heute=2026-12-10` den Kalender wie am 10. Dezember. Danach wieder `false`! |

## Testen vor dem Start

1. `'testdatum_erlaubt' => true` und `'demo' => false` setzen.
2. Die Seite mit `?heute=2026-12-05` aufrufen: Türchen 1 bis 5 gehen auf, Türchen 6 sagt „Noch ein bisschen Geduld“.
3. Danach `testdatum_erlaubt` wieder auf `false`.

## Gestaltung

Farben und Formen über CSS-Variablen in Ihrem eigenen Stylesheet:

```css
.advent-calendar {
    --ac-accent: #458019;          /* heutiges Türchen, Preise, Knopf */
    --ac-door-bg: #fffdf8;         /* Türchen */
    --ac-door-text: #3a342b;
    --ac-inside-bg: #e6f2d2;       /* hinter der geöffneten Tür, wenn kein Bild */
    --ac-radius: 1rem;
    --ac-door-size: 7rem;          /* Mindestgröße eines Türchens */
    --ac-number-font: Georgia, serif;
    background: #3a342b;           /* Fläche hinter den Türchen */
}
```

Für ein eigenes Hintergrundbild gibt es zusätzlich `--ac-top-space` (freier Streifen über den Türchen), `--ac-image-position` (Bildausschnitt), `--ac-image-height` (Bild nur oben, z. B. `min(100%, 34rem)`), `--ac-image-fill` (Farbe unter dem Bild) und `--ac-image-mask` (weicher Übergang, z. B. `linear-gradient(to bottom, #000 55%, transparent)`).

## Wichtig für den Schutz der Inhalte

Die Ordner `inhalte` und `lib` sind per `.htaccess` gesperrt. Das funktioniert auf Apache-Servern (die meisten Hosting-Pakete). Läuft Ihre Seite auf nginx, sperren Sie den Ordner `adventskalender/inhalte` bitte in der Server-Einstellung oder fragen Ihren Hoster. Prüfen können Sie es so: `https://ihre-seite.de/adventskalender/inhalte/kalender.php` muss „Forbidden“ oder eine leere Seite zeigen.

## Für Entwickler: die Schnittstelle

Der Kalender im Browser fragt `tuer.php?day=N` und erwartet JSON:

| Antwort | Bedeutung |
|---|---|
| `{"state":"open","html":"<article>…</article>","thumb":"/pfad/zum/bild.jpg"}` | Türchen ist offen; `thumb` darf `null` sein |
| `{"state":"early"}` | Tag noch nicht erreicht |
| `{"state":"past"}` | Tag vorbei und vergangene Türchen sind zu |
| `{"state":"empty"}` | für diesen Tag gibt es keinen Inhalt |

Dieselbe Schnittstelle nutzt das Contao-Paket. Wer den Kalender in eine andere Umgebung einbaut – etwa eine Astro-Seite mit einem Server-Endpunkt oder einer Cloudflare-Funktion –, liefert das Markup aus `einbinden.php` aus, zeigt `data-advent-calendar` auf den eigenen Endpunkt und baut nur diese Antwort nach. Die Regel, wann ein Türchen aufgeht, steht in `lib/DoorRules.php` (wenige Zeilen, leicht zu übertragen).

## Bildnachweis

`adventskalender/hintergrund-weihnachtsstern.jpg` (Weihnachtsstern) und die Beispielbilder `inhalte/bilder/muster-*.jpg`: KI-generiert mit Gemini, für dieses Paket erstellt.

## Lizenz

MIT, siehe [LICENSE](LICENSE). Kostenlos nutzen, anpassen und weitergeben – der Hinweis „© Nordwerk“ bleibt dabei erhalten.
© 2026 Nordwerk, [nordwerk.studio](https://nordwerk.studio)
