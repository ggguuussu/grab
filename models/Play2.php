<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "play2".
 *
 * @property integer $id
 * @property integer $number
 * @property string $start
 * @property string $end
 * @property integer $status
 * @property integer $type
 * @property integer $time
 * @property integer $cycle
 */
class Play2 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'play2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'start', 'end', 'status', 'type', 'cycle'], 'required'],
            [['number', 'status', 'type', 'time', 'cycle'], 'integer'],
            [['start', 'end'], 'string', 'max' => 3]
        ];
    }

    /**
     * 获取类型
     * @param $type
     * @return string
     */
    public static function getType($type){
        if($type == 1){
            return "连号";
        }
        return "非连号";
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => '报警期数',
            'start' => '报警开始时间',
            'end' => '报警结束时间',
            'status' => '报警状态',
            'type' => '类型',
            'time' => 'Time',
            'cycle' => '周期',
        ];
    }
}
