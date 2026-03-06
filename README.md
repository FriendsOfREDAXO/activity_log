# Activity Log für REDAXO 5

Eine einfache Möglichkeit um Aktivitäten zu loggen.
Die Logs werden im System unter Activity Log angezeigt.

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