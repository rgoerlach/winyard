cURL ist ein Kommandozeilenwerkzeug (und eine Bibliothek) zur Übertragung von Daten mit URL-Syntax und wird häufig zum Testen von APIs verwendet.
Voraussetzungen
Ersetze in den folgenden Beispielen http://deinserver/api.php durch die tatsächliche URL deiner hochgeladenen api.php-Datei.
1. 🟢 isAlive testen
Dieser Endpunkt überprüft, ob der Service online und betriebsbereit ist.
Befehl
curl -X GET http://deinserver/api.php/isAlive

Erwartete Ausgabe (JSON)
{
    "status": "OK",
    "message": "Service is up and running.",
    "timestamp": "YYYY-MM-DD HH:MM:SS"
}

2. 🔢 version testen
Dieser Endpunkt liefert die aktuelle Version des Dienstes.
Befehl
curl -X GET http://deinserver/api.php/version

Erwartete Ausgabe (JSON)
{
    "status": "OK",
    "service_name": "Basic Utility API",
    "version": "1.0.0"
}

3. ❓ help testen
Dieser Endpunkt zeigt die rudimentäre Dokumentation der verfügbaren Routen.
Befehl
curl -X GET http://deinserver/api.php/help

Erwartete Ausgabe (JSON)

{
    "status": "OK",
    "service_name": "Basic Utility API",
    "description": "A basic REST API providing utility information.",
    "endpoints": {
        "\/isAlive": "Checks if the service is operational. Returns status and timestamp.",
        "\/version": "Returns the current version number of the service.",
        "\/help": "Shows this help message (documentation of available endpoints).",
        "\/" : "Same as /help (default endpoint)."
    }
}

4. 🛑 Ungültigen Endpunkt testen (Fehlerbehandlung)
Dieser Test überprüft, ob deine API den korrekten 404-Statuscode zurückgibt, wenn ein Endpunkt nicht existiert.
Befehl
curl -i -X GET http://deinserver/api.php/nonExistingRoute

Erwartete Ausgabe (Auszug)
Beachte den Header: Es sollte HTTP/1.1 404 Not Found angezeigt werden.



