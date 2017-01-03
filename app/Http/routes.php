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

//URL for UpdatePUshService
$app->get('version','UpdateController@getLastVersion');//check version
$app->post('upload','UpdateController@upload');//upload zip file
$app->get('download','UpdateController@download');//download zip file


//URL for LogsPUllService
$app->get('check','LogsController@check');//check now pull log
$app->post('logUpload','LogsController@logUpload');//upload log file .txt
$app->get('getFile','LogsController@getFileName');//get all current file names
$app->get('logDownload','LogsController@logDownload');//download log file
$app->get('branch','LogsController@getAllBranch');//get all branchers
$app->get('terminal','LogsController@getAllTerminal');//get all terminal by branch