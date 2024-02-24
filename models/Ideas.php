<?php
namespace app\models;

use yii\db\ActiveRecord;

class Ideas extends ActiveRecord {
    public static function tableName()
    {
        return 'channel_ideas';
    }
}