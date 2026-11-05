<?php

/*
 * Nur zum Ausprobieren auf dem eigenen Rechner – nicht für den echten Betrieb.
 *
 *   php -S localhost:8000 examples/php-server.php
 *
 * Danach http://localhost:8000/beispiel.php öffnen. Der eingebaute PHP-Server liest keine
 * .htaccess; diese Datei sperrt die Ordner inhalte und lib stattdessen.
 */

$path = rawurldecode((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
$path = preg_replace('#/+#', '/', str_replace('\\', '/', $path));

if (preg_match('#/adventskalender/(inhalte|lib)(/|$)#i', $path) || str_contains($path, '/../') || str_contains($path, '/./')) {
    http_response_code(404);
    exit;
}

return false;
