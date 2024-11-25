<?php
namespace app\models;

use yii\db\ActiveRecord;

class Video extends ActiveRecord {

    public $has_thumbnail = "N/A";
    public $local_stream = "N/A";

    public function rules()
    {
        return [
            [['has_thumbnail', 'local_stream'], 'safe'],
        ];
    }
    public static function tableName()
    {
        return 'channel_db';
    }
    public function fields()
    {
        $fields = parent::fields();

        $fields[] = "has_thumbnail";
        $fields[] = "local_stream";

        return $fields;
    }
}