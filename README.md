# Activity Log für REDAXO 5

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

#### Extension Points:

In den Einstellungen kann ein Log für folgende Extension Points aktiviert werden.

**Article**
Article added
- Article updated
- Article status change
- Article deleted

**Category**
- Category added
- Category updated
- Category deleted

**Slice**
- Slice added
- Slice updated
- Slice deleted

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