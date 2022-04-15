# Activity Log für REDAXO 5
### :construction: ACHTUNG: ALPHA-Version - Bitte nur zum Testen benutzen.

Eine einfache Möglichkeit um Aktivitäten zu loggen.
Die Logs werden im System unter Activity Log angezeigt.

```php
rex_activity::message('Hello World!')->type(rex_activity::TYPE_INFO)->log();
rex_activity::message('He did something :O')->type(rex_activity::TYPE_WARNING)->causer(rex::getUser())->log();
```
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

#### TODO:
- [ ] Einstellungen um gewisse Events zu loggen (SLICE_ADD, SLICE_UPDATE...)
- [x] Cronjob um Logs zu löschen
- [ ] Liste filtern
