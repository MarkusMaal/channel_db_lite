<?php
use app\models\GeneralComments;
use yii\helpers\Html;
function DisplayComments($comment, $page, $depth = 0) {
	global $tabIndex;
    $thread = 1;
	$authuser = "";
	$pref = "";
	$suf = "";
	if (($comment["hide"] == 1) && (empty($_SESSION)))
	{
		return;
	}
	$dp = 50 * $depth;
	$custstyle = "\"float: right; font-style: normal;\"";
	$custstyle2 = "\"float: left; font-style: normal;\"";
	echo '<div class="card mb-1" style="margin-left: ' . $dp . 'px;">';
	$usrname = $comment["NAME"];
	echo '<div class="card-header">'.Html::encode($usrname).'</div>';
	echo '<p class="card-body mb-0">' . Html::encode($comment["COMMENT"]) .'</p>';
	echo '<div class="d-flex">';
    echo '<a class="ms-3 pb-2 text-blurple" style=' . $custstyle2 . '>' . Yii::t("comments", "Meeldib") . ': ' . $comment["likes"] . '</a>';
	if ($comment["heart"] == 1) {
		echo '<a class="ms-3 pb-2 text-blurple" style=' . $custstyle2 . '>' . Yii::t("comments", "Süda") . '</a>';
	}
	echo '</div>';
	echo '</div>';
    // SELECT * FROM general_comments WHERE PAGE_ID = $page AND THREAD = $thread AND REPLY = 1 AND REPLY_PARENT = {$comment["ID"]}
    $replies = GeneralComments::find()->where(['PAGE_ID' => $page])->andWhere(["THREAD" => "1"])->andWhere(["REPLY" => "1"])->andWhere(["REPLY_PARENT" => $comment["ID"]])->all();
	if ($replies) {
		foreach ($replies as $rep) {
			DisplayComments($rep, $page, $depth + 1);
		}
	}
}

/**
 * @throws \yii\db\Exception
 */
function SaveComment($name, $comment, $likes, $page_id, $hide = 0, $heart = 0, $dislikes = 0, $reply = 0, $reply_parent = null, $thread = 1) {
    try {
        $q = "INSERT INTO general_comments (NAME, COMMENT, THREAD, REPLY, REPLY_PARENT, PAGE_ID, likes, dislikes, heart, hide) VALUES (:name, :comment, :thread, :reply, :reply_parent, :page_id, :likes, :dislikes, :heart, :hide)";
        $parameters = [
            "name" => $name,
            "comment" => str_replace("@@", "@", str_replace("<br>", "\n", html_entity_decode($comment))),
            "thread" => $thread,
            'reply' => $reply,
            'reply_parent' => $reply_parent,
            'page_id' => $page_id,
            'likes' => $likes,
            'dislikes' => $dislikes,
            'heart' => $heart,
            'hide' => $hide,
        ];
        Yii::$app->db->createCommand($q, $parameters)->execute();
        $q = "SELECT ID FROM general_comments ORDER BY ID DESC LIMIT 1";
        return Yii::$app->db->createCommand($q)->queryOne()["ID"];
    } catch (Exception $e) {
        $q = "SELECT ID FROM general_comments ORDER BY ID DESC LIMIT 1";
        return Yii::$app->db->createCommand($q)->queryOne()["ID"];
    }
}

/**
 * @throws \yii\db\Exception
 */
function CheckComments($url, $page_id) {
    try {
        $comments = getAllComments(explode("=", $url)[1]);
        $threads = $comments[0]["items"];
        $ret = false;
        if (count($comments) > 0) {
            $q1 = "DELETE FROM general_comments WHERE THREAD = 1 AND PAGE_ID = :page_id";
            $parameters = [
                'page_id' => $page_id,
            ];
            Yii::$app->db->createCommand($q1, $parameters)->execute();
        }
        foreach ($threads as $thread) {
            $id = SaveComment($thread["snippet"]["topLevelComment"]["snippet"]["authorDisplayName"], $thread["snippet"]["topLevelComment"]["snippet"]["textDisplay"], $thread["snippet"]["topLevelComment"]["snippet"]["likeCount"], $page_id);
            $ret = true;
            if ($thread["snippet"]["totalReplyCount"] > 0) {
                $replies = getReplies($thread["snippet"]["topLevelComment"]["id"]);
                foreach ($replies[0]["items"] as $reply) {
                    SaveComment($reply["snippet"]["authorDisplayName"], $reply["snippet"]["textDisplay"], $reply["snippet"]["likeCount"], $page_id, 0, 0, 0, 1, $id);
                }
            }
        }
        return $ret;
    } catch (Exception $e) {
        return false;
    }
}

function getAllComments($videoId,$pageToken=null,$maxResults=20){
    $youtube_key = file_exists(Yii::getAlias("@app/API_KEY.DAT")) ? file_get_contents(Yii::getAlias("@app/API_KEY.DAT")) : null;
    if ($youtube_key == null) {
        return null;
    }
    $url = "https://www.googleapis.com/youtube/v3/commentThreads";

    static $all =[];
    $params =[
        'key' => $youtube_key,
        'part' => 'snippet',
        'maxResults' => $maxResults,
        'videoId' => $videoId,
        'pageToken' => $pageToken
    ];

    $call = $url.'?'.http_build_query($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $call);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    $data = NULL;
    $data = json_decode($output,true);
    $all[] = $data;
    if(isset($data['nextPageToken'])){
        if($data['nextPageToken'] != NULL ){
            $pageToken = $data['nextPageToken'];
            getAllComments($videoId,$pageToken,$maxResults);
        }
    }
    curl_close($ch);
    return $all;


}

function getReplies($parentId, $pageToken = null, $maxResults = 20) {
    $youtube_key = file_exists(Yii::getAlias("@app/API_KEY.DAT")) ? file_get_contents(Yii::getAlias("@app/API_KEY.DAT")) : null;
    if ($youtube_key == null) {
        return null;
    }
    $url = "https://www.googleapis.com/youtube/v3/comments";

    static $all =[];
    $params =[
        'key' => $youtube_key,
        'part' => 'snippet',
        'maxResults' => $maxResults,
        'parentId' => $parentId,
        'pageToken' => $pageToken
    ];

    $call = $url.'?'.http_build_query($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $call);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    $data = NULL;
    $data = json_decode($output,true);
    $all[] = $data;
    if(isset($data['nextPageToken'])){
        if($data['nextPageToken'] != NULL ){
            $pageToken = $data['nextPageToken'];
            getReplies($parentId,$pageToken,$maxResults);
        }
    }
    curl_close($ch);
    return $all;
}