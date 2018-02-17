<?php
use cyneek\yii2\routes\components\Route;
use yii\web\{HttpException, Response};

Route::get(    '/',         'rest/index' );
Route::get(    '/{m}',      'rest/list',   ['before' => 'is_allow'] );
Route::get(    '/{m}/{id}', 'rest/load',   ['before' => 'is_allow'] );
Route::post(   '/{m}/{id}', 'rest/save',   ['before' => 'is_allow'] );
Route::delete( '/{m}/{id}', 'rest/delete', ['before' => 'is_allow'] );


Route::filter('is_allow', function () {
    Yii::$app->response->format = Response::FORMAT_JSON;

    if ( in_array( Route::input('m'), Yii::$app->params['acl'] ) )  {
        return true;

    } else {
        throw new HttpException(400, "BAD REQUEST " . Route::input('m') );

    }

    return false;

});
