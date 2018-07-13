<?php

namespace CandleLight;


define('CDL_ROOT', __DIR__ . DIRECTORY_SEPARATOR);
define('CDL_CONFIG', CDL_ROOT . 'config' . DIRECTORY_SEPARATOR);
define('CDL_TYPES', CDL_ROOT . 'types' . DIRECTORY_SEPARATOR);
define('CDL_APP', CDL_ROOT . 'app' . DIRECTORY_SEPARATOR);
define('CDL_VENDOR', CDL_ROOT . 'vendor' . DIRECTORY_SEPARATOR);

require_once CDL_VENDOR . 'autoload.php';
require_once CDL_APP . 'load.php';

//echo '<pre>';

$app = new App();
$app->initDb(new Loader(CDL_CONFIG . "database.json"));
$app->initTypes(new MultiLoader(CDL_TYPES . '*.json'));
$app->load();
$app->run();

//$post = $app->getTypes()['post'];
//$route = new TypeRoute($post);
//$route->apply();

//print_r($route);

//$post = $app->getTypes()['post'];
//$test = $post([
//    'title'=>'title',
//    'content'=>'content'
//]);
//$test->title = uniqid();
//$test->content = "lorem Ipsum";
//$test->save();


//use Illuminate\Database\Eloquent\Model;
//class Test extends Model{
//    protected $connection = 'default';
//    protected $table = 'test';
//    protected $fillable = [
//        'title',
////        'content'
//    ];
//}
//
//$test = Test::find(1);
//$test->title = uniqid();
////$test->content = uniqid('content-',true);
//$test->save();
//print_r($test);