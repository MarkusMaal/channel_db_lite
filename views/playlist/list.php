<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;

require_once(Yii::getAlias("@app/helpers/InitLang.php"));
require_once(Yii::getAlias("@app/helpers/LangUtils.php"));

$this->title = Yii::t('playlist', 'Esitusloendid') . (isset($_GET["page"])?" - ". Yii::t("videos", "Leht {0}", $_GET["page"]):"");
?>
<div class="text-center">
<?php $colorClass = "blurple"; ?>
<?= LinkPager::widget([
    'pagination' => $pagination,
    'linkOptions'=> ['class'=> 'text-white py-1 px-2 d-block'],
    'linkContainerOptions'=> ['class' => 'p-0 m-0', 'tag' => 'span'],
    'disabledPageCssClass' => 'disabled py-1 px-2',
    'activePageCssClass' => 'btn btn-'.$colorClass.' active',
    'pageCssClass' => 'btn btn-'.$colorClass,
    'options' => ['class'=> 'btn-group justify-content-center p-2', 'role' => 'group', 'tag' => 'div'],
    'firstPageCssClass' => 'btn btn-'.$colorClass,
    'lastPageCssClass' => 'btn btn-'.$colorClass,
    'nextPageCssClass' => 'btn btn-'.$colorClass,
    'prevPageCssClass' => 'btn btn-'.$colorClass,
    'firstPageLabel' => '&lt;&lt;',
    'lastPageLabel' => '&gt;&gt;',
    'nextPageLabel' => '&gt;',
    'prevPageLabel' => '&lt;'
    ]) ?>
    <a href="<?= Url::to(["/site/sync"]) ?>" class="btn btn-blurple"><?= Yii::t("playlist", "Sünkroniseerimine") ?></a>
</div>
<p class='text-center'><?= Yii::t('app', 'Leiti {0} vastet.', $pagination->totalCount) ?></p>
<?php if ($success) { Yii::$app->session->setFlash('success', Yii::t("playlist", 'Sünkroniseerimine õnnestus')); } ?>
<div class='row mx-auto'>
    <?php foreach ($playlists as $playlist): ?>
        <div class='col'>
            <!--<a href="<?= Url::to(["/playlist/view/", 'id' => $playlist->ID]) ?>" style="text-decoration: none;">-->
            <a href="https://www.youtube.com/playlist?list=<?=$playlist->YT_ID?>" style="text-decoration: none;">
                <div class='card my-5 mx-auto' style='width: 18rem;'>
                    <?php if (!isset($_COOKIE["thumbnails"]) || $_COOKIE["thumbnails"] != "false") {?>
                    <?php
                        $gal_entry = $gallery->where("ID = {$playlist->GALLERY_ID}")->one();
                        if (!file_exists(Yii::getAlias("@app/web/thumbs/P_" . $playlist->ID . ".jpg"))) {
                            $img = Yii::getAlias("@app/web/thumbs/P_{$playlist->ID}.jpg");
                            $url = $playlist->THUMBNAIL;
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $output = curl_exec($ch);
                            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            curl_close($ch);
                            $fp = fopen($img, "w");
                            fwrite($fp, $output);
                            fclose($fp);

                        }
                        $prefix = "";
                        echo '<img class="card-img-top" style="width: 100%;" src="' . Url::to("@web/thumbs/P_".$playlist->ID.".jpg", true) . '"/>';
                        $chnames = explode(" / ", $gal_entry->getAttributes()["Kanal"]);
                        $chname = end($chnames);
                    ?>
                    <?php } ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= $playlist->TITLE ?></h5>
                        <p class="card-text"><?= $chname ?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
