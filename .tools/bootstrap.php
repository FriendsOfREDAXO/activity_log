<?php

unset($REX);
$REX['REDAXO'] = true;
$REX['HTDOCS_PATH'] = '../../../../';
$REX['BACKEND_FOLDER'] = 'redaxo';
$REX['LOAD_PAGE'] = true;

require __DIR__.'../../../../core/boot.php';
require __DIR__.'../../../../core/packages.php';

// use original error handlers of the tools
rex_error_handler::unregister();
