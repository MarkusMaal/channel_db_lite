<?php
namespace app\models;

use yii\db\ActiveRecord;

class Gallery extends ActiveRecord {
    public static function tableName()
    {
        return 'channel_gallery';
    }
}