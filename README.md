# Adventskalender – ohne Contao

24 Türchen für jede Webseite mit PHP: Tagesangebote, Gutscheine, Rezepte oder kleine Geschichten.
Keine Datenbank, kein jQuery, keine Cookies.

**Kein Mogeln:** Texte und Bilder liegen in einem gesperrten Ordner. Ein Türchen kommt erst an seinem Tag über `tuer.php` auf die Seite – der Server prüft das Datum, nicht der Rechner des Besuchers.

## Was Sie brauchen

- Webspace mit PHP 8.1 oder neuer (fast jedes Hosting-Paket)
- Zugang per FTP oder über den Dateimanager Ihres Hosters

## So geht's

1. **Herunterladen:** oben auf GitHub „Code“ → „Download ZIP“ und entpacken.
2. **Hochladen:** den Ordner `adventskalender` per FTP in das Webverzeichnis Ihrer Seite laden (dorthin, wo auch Ihre Startseite liegt).
3. **Türchen füllen:** in `adventskalender/inhalte/kalender.php` die 24 Türchen eintragen. Ab Werk stehen dort Beispiele (Seifen, Düfte, Pflege für Männer, Gutscheine, Tipps) – einfach überschreiben. Bilder in `adventskalender/inhalte/bilder` legen (Querformat, etwa 1200 × 800 Pixel, JPG oder WebP), die Beispielbilder `muster-*.jpg` dürfen weg.
4. **Einbinden:** auf der Seite, auf der der Kalender erscheinen soll, diese eine Zeile einfügen. Stylesheet und Skript kommen von selbst mit.

   ```php
   <?php include $_SERVER['DOCUMENT_ROOT'].'/adventskalender/einbinden.php'; ?>
   ```

   Die Seite muss dafür auf `.php` enden. Eine fertige Beispielseite ist `beispiel.php`. Ist Ihre Seite kein PHP (reines HTML, Baukasten), laden Sie `beispiel.php` mit hoch, passen Überschrift und Farben an und verlinken sie aus Ihrem Menü.
5. **Prüfen:** `https://ihre-seite.de/adventskalender/pruefen.php` aufrufen. Die Seite zeigt, ob alles bereit ist und ob Ihre Inhalte geschützt sind. Alles grün? Fertig.

## Wo läuft Ihre Seite?

Damit niemand die Türchen vorab sieht, muss der Server den Ordner `adventskalender/inhalte` sperren. Die Prüfseite (Schritt 5) sagt Ihnen, ob das klappt.

| Ihr Server | Was zu tun ist |
|---|---|
| Übliches Hosting-Paket mit Apache oder LiteSpeed (die meisten Anbieter) | Nichts. Die mitgelieferten `.htaccess`-Dateien sperren die Ordner. |
| nginx (auch Plesk mit „nur nginx“) | Die Sperre aus [`examples/nginx.conf`](examples/nginx.conf) in die Server-Einstellung übernehmen oder Ihren Hoster darum bitten. |
| Caddy | Siehe [`examples/Caddyfile`](examples/Caddyfile). |
| Eigener Apache mit `AllowOverride None` | Siehe [`examples/apache-vhost.conf`](examples/apache-vhost.conf). |
| Docker | [`examples/docker/compose.yaml`](examples/docker/compose.yaml) startet den Kalender mit dem offiziellen Image `php:apache`; die `.htaccess` wirkt dort. |
| Zum Ausprobieren auf dem eigenen Rechner | `php -S localhost:8000 examples/php-server.php`, dann `http://localhost:8000/beispiel.php`. Nicht für den echten Betrieb. |

Unsicher? Rufen Sie `https://ihre-seite.de/adventskalender/inhalte/schutz-test.txt` auf. Steht dort ein Satz, ist der Ordner **nicht** geschützt. „Forbidden“, „Not Found“ oder eine Fehlerseite heißen: alles gut.

### Am sichersten: Inhalte außerhalb des Webverzeichnisses

Viele Hoster erlauben Ordner neben dem Webverzeichnis, an die kein Besucher herankommt. Dann spielt der Server keine Rolle mehr:

1. Den Ordner `adventskalender/inhalte` eine Ebene über Ihr Webverzeichnis verschieben und in `adventskalender-inhalte` umbenennen.
2. In `adventskalender/inhalte-ort.php` die Zeile `return dirname(__DIR__, 2).'/adventskalender-inhalte';` eintragen.
3. Mit der Prüfseite kontrollieren: Sie meldet dann „Inhalte liegen außerhalb des Webverzeichnisses“.

## Vorschau und Testen

**Vorschau:** Mit `'demo' => true` in `kalender.php` sind bis zum 30. November alle Türchen offen, zum Vorzeigen und Prüfen. Am 1. Dezember endet die Vorschau von selbst, ab dann gilt das Datum. Ab Werk ist sie aus. Tragen Sie echte Gutscheincodes ein, lassen Sie die Vorschau aus, sonst sind die Codes bis zum 1. Dezember für alle sichtbar.

**Bestimmten Tag testen:**

1. In `kalender.php` `'testdatum_erlaubt' => true` setzen.
2. Die Seite mit `?heute=2026-12-05` aufrufen: Türchen 1 bis 5 gehen auf, Türchen 6 sagt „Noch ein bisschen Geduld“.
3. Danach `testdatum_erlaubt` wieder auf `false`. Die Prüfseite erinnert Sie daran.

## Einstellungen in kalender.php

| Einstellung | Bedeutung |
|---|---|
| `jahr` | Kalenderjahr, `0` = jedes Jahr wieder |
| `vergangene_offen` | `true`: vergangene Türchen bleiben offen, `false`: nur das heutige geht auf |
| `demo` | Vorschau: `true` = bis 30. November alle Türchen offen, endet am 1. Dezember von selbst |
| `gemischt` | Türchen gemischt anordnen wie bei einem echten Kalender |
| `schnee` | leichter Schneefall (entfällt bei „Bewegung reduzieren“) |
| `hintergrund` | `'weihnachtsstern'` = mitgelieferter Weihnachtsstern (Standard), ein eigenes Bild als öffentlicher Pfad, z. B. `'/bilder/winter.jpg'`, oder `''` für keinen Hintergrund |
| `zeitzone` | Ab Werk `'Europe/Berlin'`. Danach richtet sich, wann ein Türchen aufgeht |
| `testdatum_erlaubt` | nur zum Testen, siehe oben. Für den echten Einsatz `false` |
| `adresse` | Öffentliche Adresse des Ordners `adventskalender`. Leer lassen, sie wird selbst erkannt. Nur nötig, wenn Türchen oder Bilder nicht laden, z. B. `'/shop/adventskalender'` |

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

## Lieber fertig eingerichtet?

Wir richten den Kalender auf Ihrer Seite ein, gestalten ihn passend zu Ihrem Auftritt oder bauen Ihnen eine neue Website: [nordwerk.studio](https://nordwerk.studio)

## Für Entwickler

### Die Schnittstelle

Der Kalender im Browser fragt `tuer.php?day=N` und erwartet JSON:

| Antwort | Bedeutung |
|---|---|
| `{"state":"open","html":"<article>…</article>","thumb":"/pfad/zum/bild.jpg"}` | Türchen ist offen; `thumb` darf `null` sein |
| `{"state":"early"}` | Tag noch nicht erreicht |
| `{"state":"past"}` | Tag vorbei und vergangene Türchen sind zu |
| `{"state":"empty"}` | für diesen Tag gibt es keinen Inhalt |

`tuer.php?day=N&bild=1` liefert das Bild des Türchens, aber nur, wenn es offen ist (sonst 404). Alle Antworten tragen `Cache-Control: private, no-store`, damit kein Zwischenspeicher ein Türchen vorab herausgibt.

Dieselbe Schnittstelle nutzt das Contao-Paket. Wer den Kalender in eine andere Umgebung einbaut – etwa eine Astro-Seite mit Server-Endpunkt oder eine Cloudflare-Funktion –, liefert das Markup aus `einbinden.php` aus, zeigt `data-advent-calendar` auf den eigenen Endpunkt und baut nur diese Antwort nach. Die Regel, wann ein Türchen aufgeht, steht in `lib/DoorRules.php` (wenige Zeilen, leicht zu übertragen). Wichtig dabei: Inhalte künftiger Türchen dürfen weder im ausgelieferten HTML noch in statischen Dateien stehen.

### Tests

```bash
php tests/DoorRulesTest.php      # Regeln: wann geht welches Türchen auf
tests/server-check.sh            # Schutz auf Apache, nginx, Caddy und dem PHP-Testserver (braucht Docker)
```

Ohne lokales PHP: `docker run --rm -v "$PWD":/app -w /app php:8.3-cli php tests/DoorRulesTest.php`

## Bildnachweis

`adventskalender/hintergrund-weihnachtsstern.jpg` (Weihnachtsstern) und die Beispielbilder `inhalte/bilder/muster-*.jpg`: KI-generiert mit Gemini, für dieses Paket erstellt.

## Lizenz

MIT, siehe [LICENSE](LICENSE). Kostenlos nutzen, anpassen und weitergeben – der Hinweis „© Nordwerk“ bleibt dabei erhalten.
© 2026 Nordwerk, [nordwerk.studio](https://nordwerk.studio)
