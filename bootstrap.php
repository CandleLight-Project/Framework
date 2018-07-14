<?php

namespace CandleLight;

define('CDL_ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define('CDL_CONFIG', CDL_ROOT . 'config' . DIRECTORY_SEPARATOR);
define('CDL_TYPES', CDL_ROOT . 'types' . DIRECTORY_SEPARATOR);
define('CDL_APP', CDL_ROOT . 'app' . DIRECTORY_SEPARATOR);
define('CDL_VENDOR', CDL_ROOT . 'vendor' . DIRECTORY_SEPARATOR);

require_once CDL_VENDOR . 'autoload.php';
require_once CDL_APP . 'load.php';

$app = new App();
$app->initDb(new Loader(CDL_CONFIG . "database.json"));
$app->initTypes(new MultiLoader(CDL_TYPES . '*.json'));
$app->load();
$app->run();