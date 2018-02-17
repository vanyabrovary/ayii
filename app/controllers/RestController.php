<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;

class RestController extends Controller
{

    private $arg;
    private $model;

    public function init()
    {
        $this->arg   = Yii::$app->request->params();
        $this->model = 'app\models\\' . $this->arg["m"]; //' for mc

    }


    public function actionList()
    {
        return call_user_func( $this->model . '::find')->asArray()->all();

    }


    public function actionLoad()
    {
        return call_user_func( $this->model . '::findOne', $this->arg["id"] );

    }


    public function actionChild()
    {
        $func = $this->arg["child"];
        return call_user_func( $this->model . '::findOne', $this->arg["id"] )->$func;

    }


    public function actionSave()
    {
        if(isset($this->arg["id"])) {
            $model  = call_user_func( $this->model . '::findOne', $this->arg["id"] );
        }

        if(!isset($model)) {
            $model  = new $this->model;
        }

        $model->attributes = $this->arg;
        $model->save();

        if($model->errors)      { throw new HttpException(400, implode(' ', $model->getFirstErrors('id') )); }

        return $model;

    }


    public function actionDelete()
    {
        $model = call_user_func( $this->model . '::findOne', $this->arg["id"] );

        if($model) {
            $model->delete();
        } else {
            throw new HttpException(400, '0 ROWS DELETED');
        }

        return ['message' => 'deleted'];

    }

}
