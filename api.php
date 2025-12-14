<?php

/**
 * Grundlegende PHP REST API
 * Bietet die Endpunkte: /isAlive, /version, /help
 */

// 1. **Header setzen:** Wir stellen sicher, dass die Antwort JSON ist
header('Content-Type: application/json');

// 2. **Service-Informationen**
$service_version = "1.0.0";
$api_name = "Basic Utility API";

// Wir extrahieren den Teil nach dem Skriptnamen (api.php)
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];

// 3. **Pfad-Analyse (Routing)**
// Wir nutzen REQUEST_URI, um den angefragten Pfad zu bestimmen.
// Beispiel: Wenn die URL http://deinserver/api.php/isAlive ist,
// dann ist $path_info '/api.php/isAlive' (oder nur '/isAlive' je nach Serverkonfiguration).

$path_info = '';

// Prüfen, ob der Server PATH_INFO korrekt gesetzt hat (häufig bei Mod_Rewrite der Fall)
if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] !== '/') {
    $path_info = $_SERVER['PATH_INFO'];
} 
// Alternativ, falls PATH_INFO leer ist, analysieren wir die REQUEST_URI (Fallback)
else {
    $request_uri = $_SERVER['REQUEST_URI'];
    // Entfernen des Basispfads (/api) und möglichen Query-Strings
    // Passe '/api' an, falls du einen anderen Pfad in .htaccess gewählt hast
    $path_info = str_replace('/api', '', $request_uri); 
    $path_info = strtok($path_info, '?');
}

// Bereinigen und Splitten des Pfades
$path_parts = array_values(array_filter(explode('/', $path_info))); 

// Der erste Teil sollte unser Endpunkt sein (z.B. 'isAlive')
$endpoint = isset($path_parts[0]) ? $path_parts[0] : '';

// Der erste Teil sollte unser Endpunkt sein (z.B. 'isAlive')
$endpoint = isset($path_parts[0]) ? $path_parts[0] : '';

// 4. **Funktions-Implementierung (Dispatcher)**
switch ($endpoint) {
    case 'isAlive':
        // **Funktion 1: isAlive** - Überprüft, ob der Service läuft
        $response = [
            'status' => 'OK',
            'message' => 'Service is up and running.',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        http_response_code(200); // HTTP 200 OK
        break;

    case 'version':
        // **Funktion 2: version** - Liefert die aktuelle Versionsnummer
        $response = [
            'status' => 'OK',
            'service_name' => $api_name,
            'version' => $service_version
        ];
        http_response_code(200);
        break;

    case 'help':
        // **Funktion 3: help** - Rudimentäre Dokumentation der Endpunkte
        $response = [
            'status' => 'OK',
            'service_name' => $api_name,
            'description' => 'A basic REST API providing utility information.',
            'endpoints' => [
                '/isAlive' => 'Checks if the service is operational. Returns status and timestamp.',
                '/version' => 'Returns the current version number of the service.',
                '/help' => 'Shows this help message (documentation of available endpoints).',
                '/' => 'Same as /help (default endpoint).'
            ]
        ];
        http_response_code(200);
        break;
        
    case '':
        // Default-Route (z.B. wenn nur api.php aufgerufen wird)
        // Wir leiten auf 'help' weiter
        $endpoint = 'help';
        // Fall-through zum 'help' case oben...
        // Da wir nicht wirklich fall-through wollen, rufen wir den 'help' case neu auf
        // Besser: Wir kopieren einfach die help-Funktionalität, oder nutzen eine Funktion
        
        // Da wir keinen expliziten Fall-through haben (wie in anderen Sprachen), 
        // wiederholen wir hier die Hilfe-Antwort oder gehen zu einem Default-Handler.
        $response = [
            'status' => 'OK',
            'service_name' => $api_name,
            'message' => 'Welcome to the API. Use /help to see available endpoints.',
            'endpoints' => [
                '/isAlive' => 'Checks if the service is operational.',
                '/version' => 'Returns the current version number.',
                '/help' => 'Shows this help message.'
            ]
        ];
        http_response_code(200);
        break;

    default:
        // Ungültiger Endpunkt (404 Not Found)
        $response = [
            'status' => 'Error',
            'message' => "Endpoint '{$endpoint}' not found.",
            'error_code' => 404
        ];
        http_response_code(404); // HTTP 404 Not Found
        break;
}

// 5. **Antwort senden**
echo json_encode($response, JSON_PRETTY_PRINT);
exit;

/* 
Angenommen, du hast die Datei api.php im Root-Verzeichnis deines Webservers (oder in einem Unterordner).
1. URL-Struktur:
• Du greifst auf die Endpunkte zu, indem du den Namen des Endpunkts direkt nach der Skript-URL anhängst.
2. Beispiele:
• Basis-URL: http://deinserver/api.php
• isAlive: http://deinserver/api.php/isAlive
• version: http://deinserver/api.php/version
• help: http://deinserver/api.php/help
💡 Erklärungen zum Code
• header('Content-Type: application/json');: Dies ist sehr wichtig. Es teilt dem Client (Browser, App, etc.) mit, dass die Antwort im JSON-Format vorliegt.
• Routing (Pfadanalyse):
• Wir verwenden $_SERVER['REQUEST_URI'] und $_SERVER['SCRIPT_NAME'], um den Teil der URL zu isolieren, der den gewünschten Endpunkt enthält (z.B. /isAlive).
• Der $endpoint (z.B. 'isAlive') wird dann in einem switch-Statement ausgewertet.
• switch ($endpoint): Dies ist das Herzstück des Routing/Dispatching. Je nachdem, welcher Endpunkt angefragt wurde, wird der entsprechende Code-Block ausgeführt.
• http_response_code(): Setzt den korrekten HTTP-Status-Code.
• 200 OK: Bei Erfolg (isAlive, version, help).
• 404 Not Found: Wenn der Endpunkt unbekannt ist.
• json_encode($response): Konvertiert das PHP-Array $response in einen validen JSON-String, der an den Client gesendet wird. Die Konstante JSON_PRETTY_PRINT ist optional, macht das JSON aber lesbarer.
  */
