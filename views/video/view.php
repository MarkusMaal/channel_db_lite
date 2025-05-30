<?php
use yii\helpers\Html;
use yii\helpers\Url;
/** @var yii\web\View $this */
require_once(Yii::getAlias("@app/helpers/InitLang.php"));
require_once(Yii::getAlias("@app/helpers/LangUtils.php"));

$this->title = Yii::t("videos", "Videod") . ' - '.LangUtils::GetMuiTitle(Yii::$app->language, $video);
$check = "<span style=\"display: inline-block; width: 2em;\">&#x2714;</span>";
$cross = "<span style=\"display: inline-block; width: 2em;\">&#x274C;</span>";
?>
<div class='container mt-2 mb-2'>
    <div class='card mx-auto' style="width: 90%;">
        <a class="card-img-top text-center" href="<?= Url::to("@web/thumbs/".$video->ID.".jpg", true) ?>">
            <?php if (file_exists(Yii::getAlias("@app/web/thumbs/".$video->ID.".jpg"))) { ?>
            <img width="500" src="<?= Url::to("@web/thumbs/".$video->ID.".jpg", true) ?>">
            <?php } ?>
        </a>
        <div class="card-body">
            <h1 class="card-title"><?= LangUtils::GetMuiTitle(Yii::$app->language, $video) ?></h1>
            <p><?= LangUtils::GetMuiChannel(Yii::$app->language, $video) ?></p>
            <hr>
            <p><?= nl2br(LangUtils::GetMuiDesc(Yii::$app->language, $video)) ?></p>
            <hr>
            <p>
                <?= Yii::t('videos', 'Avaldati'); ?>: <?= date_format(new DateTime($video->Kuupäev), "d.m.y"); ?><br>
                <?= Yii::t('videos', 'Failinimi'); ?>: <?= Html::encode($video->Filename) ?><br>
                <?= Yii::t('videos', 'Kategooria'); ?>: <?= Html::encode((Yii::$app->language == "et-EE"?$video->Category:$video->CategoryMUI_en)) ?>
            </p>
            <br>
            <?php

            $vexts = ["mp4", "mov", "mkv", "wmv"];
            foreach ($vexts as $ext) {
                if (file_exists($_SERVER["DOCUMENT_ROOT"] . Yii::getAlias("@web/stream/" . $video->ID . "." . $ext))) {
                    echo '<a class="btn btn-blurple mx-2" target="_blank" href="'. Url::to("@web/stream/".$video->ID.".".$ext, true) . '">'.Yii::t('videos', 'Vaata/Laadi alla').'</a>';
                }
            }
            if ($video->URL != "N/A") { echo '<a class="btn btn-blurple mx-2" target="_blank" href="'.Html::encode($video->URL).'">'.Yii::t('videos', 'Ava video ({0})', ['YouTube']).'</a>'; }
            if ($video->URL != "N/A") { echo '<a class="btn btn-blurple mx-2" target="_blank" href="'.Html::encode(str_replace("https://www.youtube.com/", "https://invidious.markustegelane.eu/", $video->URL)).'">'.Yii::t('videos', 'Ava video ({0})', ['Invidious']).'</a>'; }
            if ($video->OdyseeURL != "N/A") { echo '<a class="btn btn-blurple mx-2" target="_blank" href="'.Html::encode($video->OdyseeURL).'">'.Yii::t('videos', 'Ava video ({0})', ['Odysee']).'</a>'; }
            if (file_exists($_SERVER["DOCUMENT_ROOT"] . Yii::getAlias("@web/json/" . $video->ID . ".json"))) {
                echo '<a class="btn btn-blurple mx-2" target="_blank" href="'. Url::to("@web/json/".$video->ID.".json", true) . '">'.Yii::t('videos', 'JSON dump').'</a>';
            }
            ?>
            <a class="btn btn-blurple mx-2" target="_blank" onclick="window.history.back();"><?= Yii::t('app', 'Tagasi'); ?></a>
            <br>
            <h2 class="mt-3"><?= Yii::t('app', 'Attribuudid'); ?></h2>
            <ul style="list-style-type: none; margin: 0; padding: 0;">
                <li><?= (($video->Kustutatud)?$check:$cross) ?><?= Yii::t('videos', 'Kustutatud') ?></li>
                <li><?= (($video->Subtiitrid)?$check:$cross) ?><?= Yii::t('videos', 'Subtiitrid') ?></li>
                <li><?= (($video->Avalik)?$check:$cross) ?><?= Yii::t('videos', 'Avalik') ?></li>
                <li><?= (($video->Ülekanne)?$check:$cross) ?><?= Yii::t('videos', 'Ülekanne') ?></li>
                <li><?= (($video->HD)?$check:$cross) ?><?= Yii::t('videos', 'HD') ?></li>
            </ul>
            <?php
                $tags = explode(",", $video->Tags);
            ?>
            <h2 class="mt-3"><?=!empty(trim($video->Tags))?Yii::t("videos", "Sildid"):""?></h2>
            <p>
                <?php

	                require_once Yii::getAlias("@app/helpers/Comments.php");
                    foreach ($tags as $tag) {
                        $tag = trim($tag);
                        echo "<a href='".Url::to(["/video/adv-search/", 'q' => $tag])."'><span class=\"bg-secondary mx-1 badge badge-pill badge-primary\">".$tag."</span></a>";
                    }
                ?>
            </p>
            <?php
            if (!$comments || isset($_COOKIE["force_reload"])) {
                if (isset($_COOKIE["force_reload"])) {
                    setcookie("force_reload", "", time() - 3600);
                    setcookie("force_reload2", "1", time() + 3600);
                }
                if (CheckComments($video->URL, $video->ID)) {
                    echo "<script>location.reload();</script>";
                }
            }
            if ($comments) {
                echo '<h2 class="mt-3">'. Yii::t("app", "Kommentaarid") .'</h2>';
                foreach ($comments as $comment) {
                    DisplayComments($comment, $video->ID);
                }
            }
            ?>
        </div>
    </div>
</div>