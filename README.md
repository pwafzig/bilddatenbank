# bilddatenbank

Freies Bilddatenbanksystem für Bildjournalisten und kleine Bildagenturen.

### Zielgruppe und Funktionsweise 

Die Bilddatenbank ist ein einfach gehaltenes Tool für Bildjournalisten und kleine Bildagenturen, um ein Online-Bildarchiv aufzubauen und den Kunden möglichst einfachen Zugriff auf die eigenen Bilder zu bieten. Sie basiert auf der Idee, fertig beschriftete Bilder einfach per FTP auf den Webserver zu schieben und den Rest automatisiert abzuarbeiten. Deshalb verfügt die Bilddatenbank nur über wenige Menüs im Backend, hauptsächlich um Benutzer anzulegen und die hochgeladenen Bilder verwalten bzw. wieder löschen zu können.

Bilder werden per FTP übertragen und müssen IPTC-Informationen enthalten. Diese werden per Cronjob ausgelesen und in die Datenbank eingefügt. Die Galerien und Einzelansichten erstellen sich ausschließlich aus den IPTC-Daten in den Bildern.

### Features

- Hintergrund-Daemon zum Einlesen von Bildern
- Automatische Kategorie-Bildung und Detailsansichten von Bildern
- Umfangreiche Volltextsuche über alle Datenbankfelder
- Adminoberfläche für das Löschen von Bildern, die Verwaltung von Stammdaten, Anlage von Benutzern, Abruf von Statistiken und das Anlegen von Backups
- Downloadfunktion für registrierte Benutzer
- Lightboxfunktion zum Massendownload von Bildern
- Zusammenstellung von Bildauswahlen und Versand per Downloadlink (auch für nicht-registrierte Benutzer)
- RSS-Feed für die Inhalte der Bilddatenbank

### Voraussetzungen Webserver

Als Vorbereitung zur Installation muss geklärt werden, ob der Webserver die notwendigen Voraussetzungen erfüllt:

- Betriebssystem: Linux
- Webserversoftware: Apache / Lighthttpd
- Datenbanksystem: MySQL
- Skriptsprache: PHP 5 mit Shellzugriff, nicht per FastCGI **(aktuell nicht mit PHP-Versionen >= 7 kompatibel)**
- PHP-Erweiterungen: GBLib, Zip, ZLib, EXIF, PCRE
- Cronjobs
- Apache URL-Rewriting
- Shellzugang mit Webserverrechten
- Verwaltung der Datenbanken

### Know Bugs

- Kompatibilität zu neueren PHP-Versionen (>= PHP 7)
- Absicherung Adminbereich (Empfehlung: .htaccess-Schutz auf /admin)
- Stabilität Bildimport daemon.php - bei leeren IPTC-Felder ist der Import nicht ausreichend fehlertolerant

### Lizenz
Verfügbar unter der GNU General Public License 3 (https://www.gnu.org/licenses/gpl-3.0.html).

