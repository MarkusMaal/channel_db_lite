<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */

$this->title = 'Videod' . (isset($_GET["page"])?" - Leht ".$_GET["page"]:"");
$baseurl = str_replace($_SERVER["DOCUMENT_ROOT"], "", Yii::$app->basePath);
?>
<div class="mx-auto text-center">
<?= "<a class=\"btn btn-secondary m-2 text-center\" href=\"?ord=" . (!empty($_GET["ord"])?(($_GET["ord"] == "DESC")?"ASC\">Õigetpidi järjestus":"DESC\">Tagurpidi järjestus"):"ASC\">Õigetpidi järjestus") . "</a>" ?>
</div>
<?= LinkPager::widget([
    'pagination' => $pagination,
    'linkOptions'=> ['class'=> 'page-link'],
    'disabledPageCssClass' => 'page-link disabled',
    'options' => ['class'=> 'pagination justify-content-center p-2'],
    'firstPageLabel' => '&lt;&lt;',
    'lastPageLabel' => '&gt;&gt;',
    'nextPageLabel' => '&gt;',
    'prevPageLabel' => '&lt;'
    ]) ?>
<p class='text-center'>Leiti <?= $pagination->totalCount ?> vastet.</p>
<div class='row mx-auto'>
    <?php foreach ($videos as $video): ?>
        <div class='col'>
            <a href="view/<?= $video->ID ?>" style="text-decoration: none;">
                <div class='card my-5 mx-auto' style='width: 18rem;'>
                    <img class="card-img-top" style="width: 100%;" src="../thumbs/<?= $video->ID ?>.jpg">
                    <div class="card-body">
                        <h5 class="card-title"><?= Html::encode("{$video->Video}") ?></h5>
                        <p class="card-text"><?= $video->Kanal ?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
