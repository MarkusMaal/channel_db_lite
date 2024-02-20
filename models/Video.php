<?php
namespace app\models;

use yii\db\ActiveRecord;

class Video extends ActiveRecord {
    public static function tableName()
    {
        return 'channel_db';
    }
}