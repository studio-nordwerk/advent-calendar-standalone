#!/usr/bin/env bash
# Startet den Kalender auf Apache (mit und ohne .htaccess), nginx, Caddy und dem PHP-Testserver
# und prüft, dass Inhalte vor ihrem Tag nicht abrufbar sind. Braucht Docker.
#
#   tests/server-check.sh
set -euo pipefail

cd "$(dirname "$0")/servers"
SITE="$(mktemp -d)"
export SITE COMPOSE_PROJECT_NAME=adventskalender-check
trap 'docker compose down -v --remove-orphans >/dev/null 2>&1 || true; rm -rf "$SITE"' EXIT

# Kopie des Repos; Testdatum erlauben, damit sich auch „offen“ prüfen lässt
cp -R ../../. "$SITE/"
rm -rf "$SITE/.git"
cat > "$SITE/adventskalender/inhalte/kalender.local.php" <<'PHP'
<?php
$config = require __DIR__.'/kalender.php';
$config['jahr'] = 2026;
$config['demo'] = false;
$config['testdatum_erlaubt'] = true;
return $config;
PHP
chmod -R a+rX "$SITE"

docker compose up -d --quiet-pull >/dev/null
sleep 4

failures=0
status() { curl -s -o /dev/null -w '%{http_code}' "$1"; }
body() { curl -s "$1"; }
ok() { printf '  ok      %s\n' "$1"; }
fail() { printf '  FEHLER  %s\n' "$1"; failures=$((failures + 1)); }

blocked() { # URL darf den Inhalt nicht zeigen
    local code; code=$(status "$1")
    if [ "$code" = 200 ] && [ -n "$(body "$1")" ]; then fail "$2 ist abrufbar ($1)"; else ok "$2 gesperrt ($code)"; fi
}
contains() { # Antwort muss Text enthalten
    if body "$1" | grep -q -- "$2"; then ok "$3"; else fail "$3 ($1)"; fi
}

check() {
    local name=$1 base=$2 prefix=$3
    echo "$name"
    contains "$base$prefix/beispiel.php" "data-advent-calendar=\"$prefix/adventskalender/tuer.php\"" "Kalender eingebunden, Adresse $prefix/adventskalender"
    contains "$base$prefix/beispiel.php" "$prefix/adventskalender/advent-calendar.js" "Skript eingebunden"
    blocked "$base$prefix/adventskalender/inhalte/schutz-test.txt" "Testdatei"
    blocked "$base$prefix/adventskalender/inhalte/bilder/muster-08-weihnachten.jpg" "Bild von Türchen 24"
    blocked "$base$prefix/adventskalender/inhalte//bilder/muster-08-weihnachten.jpg" "Bild mit doppeltem Schrägstrich"
    blocked "$base$prefix/adventskalender/%69nhalte/schutz-test.txt" "Testdatei, kodierte Adresse"
    blocked "$base$prefix/adventskalender/inhalte/kalender.php" "kalender.php"
    blocked "$base$prefix/adventskalender/inhalte/bilder/" "Ordnerliste der Bilder"
    blocked "$base$prefix/adventskalender/lib/config.php" "lib/config.php"
    contains "$base$prefix/adventskalender/tuer.php?day=24" '"state":"early"' "Türchen 24 heute zu"
    [ "$(status "$base$prefix/adventskalender/tuer.php?day=24&bild=1")" = 404 ] && ok "Bild 24 heute 404" || fail "Bild 24 heute abrufbar"
    contains "$base$prefix/adventskalender/tuer.php?day=24&heute=2026-12-24" '"state":"open"' "Türchen 24 am 24.12. offen"
    [ "$(status "$base$prefix/adventskalender/tuer.php?day=24&bild=1&heute=2026-12-24")" = 200 ] && ok "Bild 24 am 24.12. da" || fail "Bild 24 am 24.12. fehlt"
    contains "$base$prefix/adventskalender/tuer.php?day=24&heute=2026-02-31" '"state":"early"' "ungültiges Testdatum wird ignoriert"
    contains "$base$prefix/adventskalender/pruefen.php" "Testdatum ist erlaubt" "Prüfseite warnt vor Testdatum"
}

check "Apache mit .htaccess, Unterordner /shop" http://localhost:8101 /shop
check "Apache ohne .htaccess (examples/apache-vhost.conf)" http://localhost:8102 ""
check "nginx (examples/nginx.conf)" http://localhost:8103 ""
check "Caddy" http://localhost:8104 ""
check "PHP-Testserver (examples/php-server.php)" http://localhost:8105 ""

echo "nginx ohne Sperre (Gegenprobe: muss abrufbar sein)"
if [ "$(status http://localhost:8106/adventskalender/inhalte/schutz-test.txt)" = 200 ]; then ok "Gegenprobe: ungeschützt erkannt"; else fail "Gegenprobe greift nicht"; fi

echo
if [ "$failures" -gt 0 ]; then echo "$failures Fehler"; exit 1; fi
echo "Alles geschützt."
