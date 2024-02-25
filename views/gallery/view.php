<?php
use yii\helpers\Html;
use yii\helpers\Url;
/** @var yii\web\View $this */
require_once(Yii::getAlias("@app/helpers/InitLang.php"));

$this->title = Yii::t("gallery", "Kanali galerii") . ' - '.Html::encode($channel->Kanal);
$check = "<span style=\"display: inline-block; width: 2em;\">&#x2714;</span>";
$cross = "<span style=\"display: inline-block; width: 2em;\">&#x274C;</span>";
?>
<div class='container mt-2 mb-2'>
    <div class='card mx-auto' style="width: 90%;">
        <div class="card-body">
            <h1 class="card-title"><?= Html::encode("{$channel->Kanal}") ?></h1>
            <p><?= Yii::t("gallery", "Loomiskuupäev") ?>: <?= date_format(new DateTime($channel->Loomiskuupäev), "d.m.y"); ?></p>
            <p><?= Yii::t("gallery", "Logode ajalugu") ?>:</p>
            <?php
                for ( $logoid = 1; $logoid < 999; $logoid++ ) {
                    if (file_exists("gallery/logos/".$channel->ID."/".$logoid.".png")) {
                        echo "<a href='".Url::to("@web/gallery/logos/{$channel->ID}/{$logoid}.png", true)."'>";
                        echo "<img src='".Url::to("@web/gallery/logos/{$channel->ID}/{$logoid}.png", true)."' style='width: 200px;'>";
                        echo "</a>";
                    }
                }
            ?>
            <hr>
            <p>
                <?= nl2br(Html::encode("{$channel->Kirjeldus}")) ?>
            </p>
            <hr>
            URL: <a target="_blank" class="text-decoration-none" href="<?= Html::encode("{$channel->URL}") ?>"><?= Html::encode("{$channel->URL}") ?></a>
            <br>
            <br>
            <a class="btn btn-primary" onclick="window.navigation.back();"><?= Yii::t("app", "Tagasi") ?></a>
            <h2 class="mt-5"><?= Yii::t("app", "Attribuudid") ?></h2>
            <ul class="mb-3" style="list-style-type: none; margin: 0; padding: 0;">
                <li><?= (($channel->Kustutatud)?$check:$cross) ?><?= Yii::t("gallery", "Kustutatud") ?></li>
            </ul>
        </div>
    </div>
</div>