# Changelog

## [1.0.0-beta1] – 2026-03-06

Diese Version bringt die lang geplante Code-Modernisierung mit vollständiger Namespace-Migration sowie mehrere Bugfixes, neue Features und modernisierte CI-Workflows.

### Breaking Changes (abwärtskompatibel via BC-Stubs)

- Alle Klassen wurden in den Namespace `FriendsOfRedaxo\ActivityLog\` verschoben.
  - `rex_activity` → `FriendsOfRedaxo\ActivityLog\Activity`
  - `rex_activity_log_cronjob` → `FriendsOfRedaxo\ActivityLog\ActivityLogCronjob`
  - `activity_clear` (Console-Befehl) → `FriendsOfRedaxo\ActivityLog\ActivityClear`
  - Extension-Point-Handler → `FriendsOfRedaxo\ActivityLog\EP\*`
- Die alten Klassennamen bleiben als leere BC-Stubs erhalten, bestehender Code funktioniert weiterhin.

### Neue Features

- **Quellen-Spalte (`source`)**: Neue DB-Spalte speichert die Herkunft jedes Log-Eintrags (z. B. `article`, `yform`, `media`). Alle internen EP-Handler setzen die Quelle automatisch. Eigene Logs können `->source('mein-addon')` in der Kette nutzen.
- **Filter: Suche, Typ, Quelle, Benutzer**: Vollständig überarbeitetes Filter-Formular mit freier Textsuche, alle Dropdowns mit Bootstrap-Selectpicker, sauberes Bootstrap-3-Zeilenlayout.
- **Filter-Persistenz über Paginierung und Löschvorgänge**: Aktive Filter bleiben beim Blättern (Pager), beim Löschen einzelner Einträge sowie beim Massenlöschen erhalten.
- **Zeilenfarben nach Log-Level**: Tabellenzeilen farblich hervorgehoben je nach Typ (Error/Delete = rot, Warning = gelb, Critical = lila, Add = grün). Light- und Dark-Mode unterstützt.
- **`disable_for_admins`-Option**: Admins können optional von der Protokollierung ausgenommen werden.
- **Source-Filterung im Cronjob**: `clearEntries()` und der Cronjob unterstützen optionale Filterung nach Quelle (#21).
- **Spaltenreihenfolge**: Quelle erscheint in der Übersicht direkt nach dem Datum.
- **User-Button nur für Admins**: Klickbare User-Buttons (Link zur Benutzerverwaltung) werden nur noch Administratoren angezeigt. Andere Benutzer sehen nur den Namen.

### Bugfixes

- **`clang_id` Undefined array key** (#46): Fallback auf `rex_clang::getCurrentId()` in `EP\Meta`.
- **`rex_sql_exception` beim Löschen von Artikeln/Kategorien**: DB-Lookup wird beim Typ `delete` übersprungen.
- **`NullPointerException` im Cronjob-Kontext**: `rex::getUser()` kann im Cronjob `null` sein – `isAdmin()`-Aufruf nur bei vorhandenem User.
- **XSS in EP-Handlern**: Alle dynamischen Werte (Artikel-, Kategorie-, Template-, Modul-, Sprachnamen) werden mit `rex_escape()` ausgegeben.
- **`?static` → `?self` in Fluent-Methoden**: Behebt `TypeError` bei PHPUnit-Tests mit dem BC-Stub `rex_activity`.

### Technisches

- PHP-Mindestanforderung auf **≥ 8.2** angehoben (in `composer.json` und `package.yml`).
- CI-Workflows modernisiert: PHP 8.4, `actions/checkout@v4`, `actions/setup-node@v4`, `composer install --no-scripts`, `git apply`-Schritt durch `config:set`-Befehle ersetzt.

### Abhängigkeiten

- `phpunit/phpunit` 9.6.21 → 9.6.34 (CVE-2026-24765)
- `nightwatch` 3.6.2 → 3.15.0
- `lodash` 4.17.21 → 4.17.23
- `geckodriver` 3.0.2 → 6.1.0

---

Ältere Versionen wurden nicht mit einem Changelog geführt.
