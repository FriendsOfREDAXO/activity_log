# Activity Log für REDAXO 5
### :construction: ACHTUNG: ALPHA-Version - Bitte nur zum Testen benutzen.

Eine einfache Möglichkeit um Aktivitäten zu loggen.
Die Logs werden im System unter Activity Log angezeigt.

```php
rex_activity::message('Hello World!')->type(rex_activity::TYPE_INFO)->log();
rex_activity::message('He did something :O')->type(rex_activity::TYPE_WARNING)->causer(rex::getUser())->log();
```

![activity_log](https://user-images.githubusercontent.com/2708231/163674949-76762489-3217-4d2f-8bbc-d89494f723c7.png)


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
- [ ] Liste der Logs aus den Einstellungen anlegen
