<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "general_comments".
 *
 * @property int $ID
 * @property string $NAME
 * @property string $COMMENT
 * @property int $THREAD
 * @property int $REPLY
 * @property int|null $REPLY_PARENT
 * @property int $PAGE_ID
 * @property int|null $likes
 * @property int|null $dislikes
 * @property int|null $heart
 * @property int|null $hide
 *
 * @property ClientRatings[] $clientRatings
 * @property CommentManagers[] $commentManagers
 * @property SentEmails[] $sentEmails
 */
class GeneralComments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'general_comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NAME', 'COMMENT', 'THREAD', 'PAGE_ID'], 'required'],
            [['THREAD', 'REPLY', 'REPLY_PARENT', 'PAGE_ID', 'likes', 'dislikes', 'heart', 'hide'], 'integer'],
            [['NAME'], 'string', 'max' => 255],
            [['COMMENT'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'NAME' => 'Name',
            'COMMENT' => 'Comment',
            'THREAD' => 'Thread',
            'REPLY' => 'Reply',
            'REPLY_PARENT' => 'Reply Parent',
            'PAGE_ID' => 'Page ID',
            'likes' => 'Likes',
            'dislikes' => 'Dislikes',
            'heart' => 'Heart',
            'hide' => 'Hide',
        ];
    }

    /**
     * Gets query for [[ClientRatings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientRatings()
    {
        return $this->hasMany(ClientRatings::class, ['CID' => 'ID']);
    }

    /**
     * Gets query for [[CommentManagers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommentManagers()
    {
        return $this->hasMany(CommentManagers::class, ['cid' => 'ID']);
    }

    /**
     * Gets query for [[SentEmails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSentEmails()
    {
        return $this->hasMany(SentEmails::class, ['COMMENT_ID' => 'ID']);
    }
}
