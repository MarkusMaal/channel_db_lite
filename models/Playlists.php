<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "channel_playlists".
 *
 * @property int $ID
 * @property string $YT_ID
 * @property string $TITLE
 * @property string $DESCRIPTION
 * @property int $GALLERY_ID
 * @property string $THUMBNAIL
 *
 * @property ChannelGallery $gALLERY
 */
class Playlists extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channel_playlists';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['YT_ID', 'TITLE', 'DESCRIPTION', 'GALLERY_ID', 'THUMBNAIL'], 'required'],
            [['GALLERY_ID'], 'integer'],
            [['YT_ID', 'THUMBNAIL'], 'string', 'max' => 255],
            [['TITLE'], 'string', 'max' => 1024],
            [['DESCRIPTION'], 'string', 'max' => 4096],
            [['GALLERY_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Gallery::class, 'targetAttribute' => ['GALLERY_ID' => 'ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'YT_ID' => 'Yt ID',
            'TITLE' => 'Title',
            'DESCRIPTION' => 'Description',
            'GALLERY_ID' => 'Gallery ID',
            'THUMBNAIL' => 'Thumbnail',
        ];
    }

    /**
     * Gets query for [[GALLERY]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGALLERY()
    {
        return $this->hasOne(ChannelGallery::class, ['ID' => 'GALLERY_ID']);
    }
}
