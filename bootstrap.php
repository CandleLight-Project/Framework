<?php

namespace CandleLight;

define('CDL_ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define('CDL_CONFIG', CDL_ROOT . 'config' . DIRECTORY_SEPARATOR);
define('CDL_TYPES', CDL_ROOT . 'types' . DIRECTORY_SEPARATOR);
define('CDL_APP', CDL_ROOT . 'app' . DIRECTORY_SEPARATOR);
define('CDL_VENDOR', CDL_ROOT . 'vendor' . DIRECTORY_SEPARATOR);
define('CDL_VALIDATIONS', CDL_ROOT . 'validations' . DIRECTORY_SEPARATOR);

require_once CDL_VENDOR . 'autoload.php';
require_once CDL_APP . 'load.php';

// Initialize Application
$app = new App();

// Prepare Application
$app->initDb(new Loader(CDL_CONFIG . "database.json"));
$app->initTypes(new MultiLoader(CDL_TYPES . '*.json'));
$app->load();

// Load plugins and extensions
DirProvider::glob(CDL_VALIDATIONS . '*.php', 0, ['app'=>$app]);

// Start Applications
$app->run();