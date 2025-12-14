Das Entfernen des Skriptnamens (api.php) aus der URL zugunsten "schöner" URLs (Clean URLs) wird über eine .htaccess-Datei und das Apache-Modul mod_rewrite realisiert.
Hier ist, wie du das umsetzen kannst, um deine URLs von http://deinserver/api.php/isAlive zu http://deinserver/api/isAlive zu ändern.
📝 Schritt 1: Erstellung der .htaccess-Datei
Erstelle im selben Ordner, in dem sich deine api.php befindet, eine neue Datei namens .htaccess (Achte auf den Punkt am Anfang!).

🛠️ Schritt 2: Anpassung der PHP-Logik
Die gute Nachricht: Dein ursprünglicher PHP-Code muss nur minimal angepasst werden, da wir bereits $_SERVER['REQUEST_URI'] verwendet haben. Allerdings kann das Routing durch mod_rewrite oft direkt den Pfad in der Variable $_SERVER['PATH_INFO'] ablegen, was die Extraktion vereinfacht.
Wir optimieren den Pfad-Analyse-Teil in api.php, um robuster zu sein:
Geänderter Teil in api.php
Ersetze den gesamten Abschnitt 3. Pfad-Analyse durch diesen vereinfachten und robusteren Code:

// 3. **Pfad-Analyse (Routing) - Für Clean URLs optimiert**

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

Das Ergebnis (Clean URLs)
Nach diesen Änderungen kannst du deine API über die folgenden, viel "saubereren" URLs aufrufen:

Vorher (Old School)          Nachher (Clean URL)
./api.php/isAlive            ./api/isAlive
./api.php/Version            ./api/Version
./api.php/help               ./api/help

Dadurch sieht deine API moderner aus und ist für Entwickler leichter zu handhaben und zu dokumentieren.





