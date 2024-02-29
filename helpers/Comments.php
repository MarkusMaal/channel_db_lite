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