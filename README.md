# Activity Log für REDAXO 5

[![PHP checks](https://github.com/FriendsOfREDAXO/activity_log/actions/workflows/phpunit.yml/badge.svg)](https://github.com/FriendsOfREDAXO/activity_log/actions/workflows/phpunit.yml)
[![PHP-CS-Fixer](https://github.com/FriendsOfREDAXO/activity_log/actions/workflows/cs-fixer.yml/badge.svg)](https://github.com/FriendsOfREDAXO/activity_log/actions/workflows/cs-fixer.yml)

Protokolliert Aktivitäten im REDAXO-Backend und stellt sie übersichtlich dar. Eigene Addons können beliebige Log-Einträge schreiben. Darüber hinaus lassen sich bekannte REDAXO-Ereignisse (Artikel, Medien, User usw.) automatisch über Extension Points erfassen.

![activity_log](https://user-images.githubusercontent.com/2708231/163674949-76762489-3217-4d2f-8bbc-d89494f723c7.png)

---

## Installation

Über den REDAXO-Installer oder manuell in `redaxo/src/addons/activity_log` entpacken und im Backend installieren.

**Voraussetzungen:**
- REDAXO ≥ 5.20.0
- PHP ≥ 8.2

---

## Verwendung

### Einfacher Log-Eintrag

```php
use FriendsOfRedaxo\ActivityLog\Activity;

Activity::message('Hallo Welt!')->log();
```

### Mit Typ und verursachendem Benutzer

```php
Activity::message('Datensatz wurde verändert')
    ->type(Activity::TYPE_WARNING)
    ->causer(rex::getUser())
    ->log();
```

### Mit Quellen-Angabe

```php
Activity::message('Import abgeschlossen')
    ->type(Activity::TYPE_ADD)
    ->source('mein-addon')
    ->log();
```

### Fluent-API – alle Methoden

| Methode | Beschreibung |
|---|---|
| `Activity::message(string $msg)` | Pflichtfeld – Text des Log-Eintrags |
| `->type(string $type)` | Typ des Eintrags (siehe Konstanten unten) |
| `->causer(?rex_user $user)` | Benutzer, der die Aktion ausgelöst hat |
| `->source(string $source)` | Herkunft des Eintrags (z. B. Addon-Name) |
| `->log()` | Schreibt den Eintrag in die Datenbank |

---

## Typen / Konstanten

```php
Activity::TYPE_INFO      // Allgemeine Information
Activity::TYPE_NOTICE    // Hinweis
Activity::TYPE_WARNING   // Warnung
Activity::TYPE_ERROR     // Fehler
Activity::TYPE_CRITICAL  // Kritisch
Activity::TYPE_DEBUG     // Debug-Ausgabe
Activity::TYPE_ADD       // Hinzugefügt
Activity::TYPE_UPDATE    // Aktualisiert
Activity::TYPE_EDIT      // Bearbeitet
Activity::TYPE_DELETE    // Gelöscht
```

Tabellenzeilen werden je nach Typ farblich hervorgehoben (rot = Error/Delete, gelb = Warning, lila = Critical, grün = Add). Light- und Dark-Mode werden unterstützt.

---

## Extension Points

In den **Einstellungen** des Addons kann die automatische Protokollierung für folgende REDAXO-Ereignisse aktiviert werden:

| Bereich | Ereignisse |
|---|---|
| **Artikel** | Hinzugefügt, Aktualisiert, Status geändert, Gelöscht |
| **Kategorie** | Hinzugefügt, Aktualisiert, Status geändert, Gelöscht |
| **Slice** | Hinzugefügt, Aktualisiert, Gelöscht, Verschoben |
| **Medien** | Hinzugefügt, Aktualisiert, Gelöscht |
| **Meta Info** | Aktualisiert |
| **Benutzer** | Hinzugefügt, Aktualisiert, Gelöscht |
| **Sprache (Clang)** | Hinzugefügt, Aktualisiert, Gelöscht |
| **Template** | Hinzugefügt, Aktualisiert, Gelöscht |
| **Modul** | Hinzugefügt, Aktualisiert, Gelöscht |
| **YForm** | Manager-Ereignisse (nur wenn YForm installiert) |

Die internen Handler setzen die `source`-Spalte automatisch (z. B. `article`, `media`, `yform`).

---

## Quelle (source)

Jeder Log-Eintrag kann eine Quelle tragen. Sie erscheint als eigene Spalte in der Übersicht und kann als Filter genutzt werden. Eigene Addons sollten den Addon-Namen als Quelle setzen:

```php
Activity::message('Datensatz importiert')
    ->type(Activity::TYPE_ADD)
    ->source('mein-addon')
    ->log();
```

---

## Filter & Suche

Die Übersicht bietet ein vollständiges Filterformular:

- **Freitextsuche** in der Nachricht
- **Typ-Filter** (Dropdown)
- **Quellen-Filter** (Dropdown)
- **Benutzer-Filter** (Dropdown)

Filter bleiben beim Blättern (Pager), beim Löschen einzelner Einträge und beim Massenlöschen erhalten.

---

## Admins von der Protokollierung ausschließen

In den **Einstellungen** kann die Option **„Protokollierung für Administratoren deaktivieren"** aktiviert werden. Ist sie gesetzt, werden Aktionen von REDAXO-Admins nicht im Activity Log erfasst.

---

## Einträge löschen

### Manuell im Backend
Einzelne Einträge oder alle Einträge lassen sich direkt in der Übersicht löschen.

### Cronjob
Über das **Cronjob-Addon** können Einträge automatisiert nach einem wählbaren Zeitraum gelöscht werden.

### Console
```bash
php redaxo/bin/console activity:clear
```

---

## Berechtigungen

Das Addon definiert die Berechtigung `activity_log[]`. Nur Benutzer mit dieser Berechtigung (bzw. Admins) können das Activity Log einsehen. Die Einstellungsseite ist ausschließlich Administratoren vorbehalten.

---

## Abwärtskompatibilität

Die alten Klassennamen sind als BC-Stubs erhalten und können weiterhin verwendet werden – ein Update bestehenden Codes ist **nicht erforderlich**:

| Alt | Neu (empfohlen) |
|---|---|
| `rex_activity` | `FriendsOfRedaxo\ActivityLog\Activity` |
| `rex_activity_log_cronjob` | `FriendsOfRedaxo\ActivityLog\ActivityLogCronjob` |
| `activity_clear` (Console) | `FriendsOfRedaxo\ActivityLog\ActivityClear` |

---

## Lizenz

MIT – siehe [LICENSE](LICENSE)

```php
use FriendsOfRedaxo\ActivityLog\Activity;

Activity::message('Hello World!')->type(Activity::TYPE_INFO)->log();
Activity::message('He did something :O')->type(Activity::TYPE_WARNING)->causer(rex::getUser())->log();

// Optional: Quelle setzen (erscheint als Filter-Spalte im Backend)
Activity::message('Datensatz importiert')->type(Activity::TYPE_ADD)->source('mein-addon')->log();
```

> **Hinweis zur Abwärtskompatibilität:** Die alte Klasse `rex_activity` bleibt als BC-Stub erhalten und kann weiter verwendet werden – ein Update bestehenden Codes ist nicht erforderlich.

![activity_log](https://user-images.githubusercontent.com/2708231/163674949-76762489-3217-4d2f-8bbc-d89494f723c7.png)

---

#### Einträge regelmäßig löschen:

Einträge können jederzeit manuell im Activity Log gelöscht werden.

Weiter gibt es die Möglichkeit die Einträge über das **Cronjob Addon** automatisiert zu löschen. Es stehen verschiedene Zeiträume zur Auswahl.

Über den Console-Befehl `activity:clear` können die Einträge auch manuell gelöscht werden.

---

#### Quelle (Source):

Jedem Log-Eintrag kann optional eine Quelle zugewiesen werden. Diese erscheint als eigene Spalte in der Übersicht und kann als Filter genutzt werden.

Die internen Extension-Point-Handler setzen die Quelle automatisch (z. B. `article`, `media`, `yform`). Für eigene Logs einfach `->source()` in der Kette ergänzen:

```php
Activity::message('Import abgeschlossen')
    ->type(Activity::TYPE_INFO)
    ->source('mein-addon')
    ->log();
```

---

#### Admins von der Protokollierung ausschließen:

In den Einstellungen kann die Option **„Protokollierung für Administratoren deaktivieren"** aktiviert werden. Ist diese Option gesetzt, werden Aktionen von REDAXO-Admins nicht im Activity Log erfasst.

---

#### Typen:

- TYPE_INFO
- TYPE_WARNING
- TYPE_ERROR
- TYPE_NOTICE
- TYPE_CRITICAL
- TYPE_DEBUG
- TYPE_ADD
- TYPE_UPDATE
- TYPE_EDIT
- TYPE_DELETE

---

#### Extension Points:

In den Einstellungen kann ein Log für folgende Extension Points aktiviert werden.

**Article**
- Article added
- Article updated
- Article status change
- Article deleted

**Category**
- Category added
- Category updated
- Category status change
- Category deleted

**Slice**
- Slice added
- Slice updated
- Slice deleted
- Slice moved

**Media**
- Media added
- Media updated
- Media deleted

**Meta Info**
- Meta updated

**User**
- User added
- User updated
- User deleted

**Language**
- Clang added
- Clang updated
- Clang deleted

**Template**
- Template added
- Template updated
- Template deleted

**Module**
- Module added
- Module updated
- Module deleted