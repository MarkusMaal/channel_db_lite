<?php
use yii\helpers\Html;
use yii\helpers\Url;
/** @var yii\web\View $this */

$this->title = 'Videod - '.Html::encode($video->Video);
$check = "<span style=\"display: inline-block; width: 2em;\">&#x2714;</span>";
$cross = "<span style=\"display: inline-block; width: 2em;\">&#x274C;</span>";
?>
<div class='container mt-2 mb-2'>
    <div class='card mx-auto' style="width: 90%;">
        <a class="card-img-top text-center" href="<?= Url::to("@web/thumbs/".$video->ID.".jpg", true) ?>">
            <img width="500" src="<?= Url::to("@web/thumbs/".$video->ID.".jpg", true) ?>">
        </a>
        <div class="card-body">
            <h1 class="card-title"><?= Html::encode("{$video->Video}") ?></h1>
            <p><?= Html::encode("{$video->Kanal}") ?></p>
            <hr>
            <p><?= nl2br(Html::encode("{$video->Kirjeldus}")) ?></p>
            <hr>
            <p>
                Avaldati: <?= date_format(new DateTime($video->Kuupäev), "d.m.y"); ?><br>
                Failinimi: <?= Html::encode($video->Filename) ?><br>
                Kategooria: <?= Html::encode($video->Category) ?>
            </p>
            <br>
            <?php
            if ($video->URL != "N/A") { echo '<a class="btn btn-primary mx-2" target="_blank" href="'.Html::encode($video->URL).'">Ava video (YouTube)</a>'; }
            if ($video->OdyseeURL != "N/A") { echo '<a class="btn btn-primary mx-2" target="_blank" href="'.Html::encode($video->OdyseeURL).'">Ava video (Odysee)</a>'; }
            ?>
            <a class="btn btn-primary mx-2" target="_blank" onclick="window.navigation.back();">Tagasi</a>
            <br>
            <h2 class="mt-3">Attribuudid</h2>
            <ul style="list-style-type: none; margin: 0; padding: 0;">
                <li><?= (($video->Kustutatud)?$check:$cross) ?>Kustutatud</li>
                <li><?= (($video->Subtiitrid)?$check:$cross) ?>Subtiitrid</li>
                <li><?= (($video->Avalik)?$check:$cross) ?>Avalik</li>
                <li><?= (($video->Ülekanne)?$check:$cross) ?>Ülekanne</li>
                <li><?= (($video->HD)?$check:$cross) ?>HD</li>
            </ul>
            <?php
                $tags = explode(",", $video->Tags);
            ?>
            <h2 class="mt-3"><?=!empty(trim($video->Tags))?"Sildid":""?></h2>
            <p>
                <?php
                    foreach ($tags as $tag) {
                        $tag = trim($tag);
                        echo "<a href='".Url::to(["/video/adv-search/", 'q' => $tag])."'><span class=\"bg-secondary mx-1 badge badge-pill badge-primary\">".$tag."</span></a>";
                    }
                ?>
            </p>
        </div>
    </div>
</div>