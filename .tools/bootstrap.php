<?php

unset($REX);
$REX['REDAXO'] = true;
$REX['HTDOCS_PATH'] = '../../../../';
$REX['BACKEND_FOLDER'] = 'redaxo';
$REX['LOAD_PAGE'] = false;

require __DIR__.'../../../../core/boot.php';
require __DIR__.'../../../../core/packages.php';

// In PHPUnit context, addon classes may not be autoloaded depending on setup state.
require_once __DIR__.'/../lib/Activity.php';
require_once __DIR__.'/../lib/rex_activity.php';

// use original error handlers of the tools
rex_error_handler::unregister();
