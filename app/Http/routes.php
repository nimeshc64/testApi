<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$app->get('/', function () use ($app) {
    return $app->version();
});

//$app->get('version',function(){
//    return '3.2';
//});

$app->get('version','UpdateController@getLastVersion');
//$app->post('version1','UpdateController@saveVersion');

$app->post('upload','UpdateController@upload');
$app->get('download','UpdateController@download');

$app->post('inq','UpdateController@inq');
