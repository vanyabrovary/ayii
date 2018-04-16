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
    
     ## save list
    public function actionSavel()
    {
        ## take json collection
        $hash = json_decode(Yii::$app->request->params_json(), true);

        ## is this array?
        if(gettype($hash) != 'array') throw new HttpException(400, 'WANT ARRAY');

        ## for models list
        $i = 0;

        foreach ($hash as $itm) { $i++;
            ## Decides to UPDATE
            if( isset( $itm["id"] ) ) {
                $model[$i] = call_user_func( $this->model . '::findOne', $itm["id"] );

            }

            ## Decides to INSERT
            if( !isset($model[$i]) )  {
                $model[$i] = new $this->model;

            }

            ## set values and validate
            $model[$i]->attributes = $itm;
            $model[$i]->validate();

            ## if row data is not valid, set error to $invalid[] array
            foreach($model[$i]->errors as $err){
                $invalid[] = $err;
            }

        }

        ## if errors exists
        if( isset($invalid) ) return $invalid;

        ## start transaction
        $connection  = \Yii::$app->db;
        $transaction = $connection->beginTransaction();

        foreach( $model as $mod ) {
            try {
                $mod->save();
                $model_hash[] = $mod;
            }
            catch (\yii\db\Exception $e) {
                $errors = 1;
            }
        }

        if(isset($errors)){
            $transaction->rollBack();

        } else {
            $transaction->commit();

        }

        return $model_hash;

    }  

    
    public function actionUnfold()
    {
        $this->_join('unfold');

    }


    public function actionExpand()
    {
        $this->_join('expand');

    }


    public function actionExpandleft()
    {
        $this->_join('expandleft');

    }

    
    private function _join($type)
    {
        list($from, $to) = preg_split('/:/', $this->arg["m"] ); // from:to

        $q = (new Query())->select("json_map( (row_to_json($from.*) || json_agg($to.*)::text ) ) AS item")->from("$from");

        if( $type == 'expand' ) {
            $q->innerJoin("$to", "$from.id = $to.$from" . "_id");

        }

        if( $type == 'unfold' ) {
            $q->innerJoin("$to", "$to.id   = $from.$to" . "_id");

        }

        if( $type == 'expandleft' ) {
            $q->leftJoin("$to", "$from.id = $to.$from" . "_id");

        }

        if( $this->_where() ) $q->where( $this->arg["_where"] );

        print '['.implode(',', array_column( $q->groupBy("$from.id")->all(), 'item') ).']';

    }


    
    
}
