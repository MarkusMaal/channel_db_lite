<?php
namespace app\models;

use yii\db\ActiveRecord;

class Video extends ActiveRecord {

    public $has_thumbnail = "N/A";
    public $local_stream = "N/A";
    public $ytdlp_meta = "N/A";
    public $est_subs = "N/A";
    public $eng_subs = "N/A";

    public function rules()
    {
        return [
            [['has_thumbnail', 'local_stream', "ytdlp_meta", "est_subs", "eng_subs"], 'safe'],
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
        $fields[] = "ytdlp_meta";
        $fields[] = "est_subs";
        $fields[] = "eng_subs";

        return $fields;
    }
}