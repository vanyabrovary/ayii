<?php
use cyneek\yii2\routes\components\{Route};
use yii\web\{HttpException, Response};

// GROUP WHERE
Route::get('/v2/{m}/groupview/{cols}/{vars}',  'rest/groupview',   ['before' => 'is_allow'] );

// EXTEND
Route::get('/v2/{m}/{id}/child/{child}',       'rest/child',       ['before' => 'is_allow'] );
Route::get('/v2/{m}/delete/{id}',              'rest/delete',      ['before' => 'is_allow'] );

// WHERE
Route::get('/v2/{m}/whereview/{vars}',         'rest/whereview',   ['before' => 'is_allow'] );

// GROUP
Route::get('/v2/{m}/group/{cols}',             'rest/group',       ['before' => 'is_allow'] );

// JOIN WHERE
Route::get('/v2/{m}/expand/{vars}',            'rest/expand',      ['before' => 'is_allow'] );
Route::get('/v2/{m}/unfold/{vars}',            'rest/unfold',      ['before' => 'is_allow'] );
Route::get('/v2/{m}/expandleft/{vars}',        'rest/expandleft',  ['before' => 'is_allow'] );

// JOIN
Route::get('/v2/{m}/expand',                   'rest/expand',      ['before' => 'is_allow'] );
Route::get('/v2/{m}/unfold',                   'rest/unfold',      ['before' => 'is_allow'] );
Route::get('/v2/{m}/expandleft',               'rest/expandleft',  ['before' => 'is_allow'] );
Route::post('/v2/{m}/savel',                   'rest/savel',       ['before' => 'is_allow'] );

// HTTP 1.1
Route::get('/v2/{m}/{id}',                     'rest/load',        ['before' => 'is_allow'] );
Route::get('/v2/{m}',                          'rest/list',        ['before' => 'is_allow'] );
Route::put('/v2/{m}/{id}',                     'rest/save',        ['before' => 'is_allow'] );
Route::post('/v2/{m}/{id}',                    'rest/save',        ['before' => 'is_allow'] );

Route::delete('/v2/{m}/{id}',                  'rest/delete',      ['before' => 'is_allow'] );
Route::delete('/v2/{m}',                       'rest/delete',      ['before' => 'is_allow'] );


Route::filter('is_allow', function () {
    Yii::$app->response->format = Response::FORMAT_JSON;
    if ( in_array( Route::input('m'), Yii::$app->params['acl'] ) )  {
        return true;
    } else {
        throw new HttpException(400, "BAD REQUEST " . Route::input('m') );
    }
    return false;
});
