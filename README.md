
# 🎥 VODControl Api

Diese API ermöglicht das einfache Auslesen und Steuern von VOD-Dateien für Twitch/OBS-Setups, insbesondere für 24/7-Streaming-Formate mit automatisierten Overlay-Texten, Speicherinformationen und Playlist-Logik.

---

## 🧠 Integration mit Streamer.bot

Die API ist vollständig kompatibel mit [Streamer.bot](https://streamer.bot/) und kann über **Fetch URL**-Aktionen eingebunden werden. Damit kannst du:

- die `title.php`, `game.php`, `time_left.php`, `storage.php` etc. live im Stream anzeigen
- automatisiert den Twitch-Titel anpassen (z. B. bei VOD-Wechsel)
- per Chatbefehl VODs vorspulen, zurückspulen oder pausieren (`seek.php`)
- aus einem Archivordner einen vollständigen 24/7-VOD-Streamingkanal betreiben

Streamer.bot reagiert auf Trigger aus OBS/Twitch und ruft die entsprechenden API-Endpunkte auf.

---

## ⚙️ Voraussetzungen

- OBS-Studio
- OBS-Plugin [Tuna](https://obsproject.com/forum/resources/tuna-now-playing-widget-current-song.843/)
- [Streamer.bot](https://streamer.bot/)
- PHP (lokal empfohlen unter Windows mit WAMP/XAMPP)
- Curl + `cacert.pem` für HTTPS-Kommunikation (z. B. mit Pastebin)
- Schreib-/Lesezugriff auf:
  - VOD-Verzeichnis (UNC oder lokaler Pfad)
  - Lokale API-Dateien:
    - `api.txt` und `duration.txt` → **werden durch das OBS-Plugin [Tuna](https://obsproject.com/forum/resources/tuna-now-playing-widget-current-song.843/) erzeugt**
	
---

## 📁 Tuna Konfiguration
Trefft im OBS-Plugin "Tuna" bitte folgende Einstellungen      
	
---

## 📁 Zentrale Konfiguration

Alle Pfade, API-Keys und Dateinamen werden zentral in `vod-config.php` gesetzt.

Beispiel:

```php
return [
    'vod' => [
        'unc_path' => '\\server\freigabe\vod',
        'vodlist_output' => 'D:\Pfad\vodlist.txt',
        'file_extension' => 'mp4',
        'xspf_path' => 'D:\Pfad\playlist\playlist.xspf',
    ],
    'system' => [
        'timezone' => 'Europe/Berlin'
    ],
    'version' => [
        'current' => '1.0.3'
    ],
    'pastebin' => [
        'api_key' => 'DEIN_API_KEY',
        'vodlist_path' => 'D:\Pfad\vodlist.txt',
        'log_path' => 'D:\Pfad\pastebin_latest.txt'
    ],
    'files' => [
        'api' => 'D:\Pfad\api.txt',
        'duration' => 'D:\Pfad\duration.txt',
        'pastebin_latest' => 'D:\Pfad\pastebin_latest.txt'
    ]
];
```

---

## 📄 Übersicht der API-Skripte

| Datei               | Beschreibung |
|--------------------|--------------|
| `vodlist_to_txt.php`    | Erstellt eine VOD-Liste (`vodlist.txt`) mit allen VODs im VOD-Pfad, Titel wird bereinigt |
| `vodlistpastebin.php`   | Lädt die aktuelle VOD-Liste auf Pastebin hoch |
| `vodlisturl.php`        | Gibt die URL der letzten Pastebin-VOD-Liste zurück |
| `latest_vod.php`        | Zeigt den Dateinamen der neuesten `.mp4`-Datei im VOD-Verzeichnis an |
| `title.php`             | Zeigt den bereinigten VOD-Titel mit Datum oder einem Hinweis, wenn kein VOD läuft |
| `game.php`              | Gibt die aktuelle Kategorie zurück (z. B. "IRL") dies wird aus dem Title extrahiert |
| `storage.php`           | Gibt Gesamtspeicher, freien Speicher, belegten Speicher **und VOD-Anzahl** aus |
| `seek.php`              | Ermöglicht das Vorspulen oder Zurückspulen des aktuellen VODs |
| `time_uhr.php`          | Zeigt an bis wann das aktuelle VOD noch läuft |
| `time_left.php`         | Zeigt an, wie lange das aktuelle VOD noch läuft |
| `listplay.php`          | Erstellt eine XSPF-Playlist damit ein bestimmtes Video abgespielt werden kann |
| `version.php`           | Gibt die aktuelle API-Versionsnummer zurück |

---

## 🌐 Verwendung in OBS / Streamer.bot

Diese Endpunkte können direkt per URL in OBS (Browser-Quellen, Text-Dateien) oder über Streamer.bot (HTTP-Aktionen) eingebunden werden.

Beispiel:
```
http://localhost/api/title
http://localhost/api/storage
```

---

## 🔐 Sicherheit

Da die API keine Authentifizierung verwendet, wird empfohlen bei öffentlicher Nutzung:
- IP-Filter (z. B. im Router, via `.htaccess` oder Firewall)
- Zugriff nur im lokalen Netzwerk / über Streamer.bot

---

## 🤝 Credits

Entwickelt von romestylez für den Stream von **@smtxlosttv** (Twitch) – mit Fokus auf Automatisierung und einfache Integration.


---
