<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Strategy extends ActiveRecord
{
    public static function tableName() :string
    {
        return 'strategy';
    }


    public function rules() :array
    {
        return [
            ['id',            'integer'],
            ['priority',      'integer'],
            ['name',          'string',   'max' => 255],
            [ ['is_public',   'is_main', 'is_not'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            // #### safe

            [ ['id', 'is_public', 'is_main', 'is_not', 'priority'], 'safe'],
            // #### 'required'

            ['name','required'],
            // #### unique


            [ ['name'], 'unique', 'targetAttribute' => ['name'] ]

        ];
    }

}
