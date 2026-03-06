# Changelog

## [1.0.0-beta1] – 2026-03-06

Diese Version bringt eine grundlegende Code-Modernisierung mit vollständiger Namespace-Migration sowie mehrere Bugfixes.

### Breaking Changes (abwärtskompatibel via BC-Stubs)

- Alle Klassen wurden in den Namespace `FriendsOfREDAXO\ActivityLog\` verschoben.
  - `rex_activity` → `FriendsOfREDAXO\ActivityLog\Activity`
  - `rex_activity_log_cronjob` → `FriendsOfREDAXO\ActivityLog\ActivityLogCronjob`
  - `activity_clear` (Console-Befehl) → `FriendsOfREDAXO\ActivityLog\ActivityClear`
  - Extension-Point-Handler → `FriendsOfREDAXO\ActivityLog\EP\*`
- Die alten Klassennamen bleiben als leere BC-Stubs (`class rex_activity extends Activity {}`) erhalten, sodass bestehender Code weiterhin funktioniert.

### Neue Features

- **`disable_for_admins`-Option** (schon länger im Code, jetzt offiziell unterstützt): Admins können optional von der Protokollierung ausgenommen werden.

### Bugfixes

- **`clang_id` Undefined array key** (#46): In `EP\Meta` wurde `$params['clang_id']` ohne Prüfung verwendet. Jetzt wird `rex_clang::getCurrentId()` als Fallback genutzt.
- **`rex_sql_exception` beim Löschen von Artikeln/Kategorien** (#46): Beim Auslösen von `ART_DELETED`/`CAT_DELETED` wurde `rex_article::get()`/`rex_category::get()` aufgerufen, obwohl der Datensatz bereits gelöscht war. Dadurch kollidierte die interne SQL-Abfrage mit dem noch laufenden DELETE-Statement. Die Datenbankabfrage wird nun beim Typ `delete` vollständig übersprungen.

### Abhängigkeiten

- `phpunit/phpunit` 9.6.21 → 9.6.34 (behebt CVE-2026-24765)
- `nightwatch` 3.6.2 → 3.15.0
- `lodash` 4.17.21 → 4.17.23
- `geckodriver` 3.0.2 → 6.1.0

---

Ältere Versionen wurden nicht mit einem Changelog geführt.
