<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
require_once(dirname(__DIR__, 2)."/helpers/Filters.php");

/** @var yii\web\View $this */

$this->title = 'Videod' . (isset($_GET["page"])?" - Leht ".$_GET["page"]:"");
$baseurl = str_replace($_SERVER["DOCUMENT_ROOT"], "", Yii::$app->basePath);

?>
<div class="mx-auto text-center">
<?php
$preurl = Yii::$app->request->getUrl();
$ord = isset($_GET["ord"]) ? $_GET["ord"] : "DESC";
$nord = $ord == "ASC" ? "DESC" : "ASC";
echo "<a class=\"btn btn-secondary m-2 text-center\" href=\"".Filters::AddFilter($preurl, "ord", $nord) . (!empty($_GET["ord"])?(($_GET["ord"] == "DESC")?"\">Õigetpidi järjestus":"\">Tagurpidi järjestus"):"\">Õigetpidi järjestus") . "</a>";
?>
<div class="dropdown d-inline">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="categorySelectButton" data-bs-toggle="dropdown" aria-expanded="false">
        Kategooria
    </button>
    <ul class="dropdown-menu" aria-labelledby="categorySelectButton">

        <?php foreach ($categories as $category) { ?>
            <li><a class="dropdown-item" href="<?= Filters::AddFilter(str_replace("video/index", "video/adv-search", $preurl), "cat", $category->Category); ?>"><?= $category->Category ?></a></li>
        <?php } ?>
    </ul>
</div>
<div class="dropdown d-inline ms-2">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="categorySelectButton" data-bs-toggle="dropdown" aria-expanded="false">
        Kanal
    </button>
    <ul class="dropdown-menu" aria-labelledby="categorySelectButton">

        <?php foreach ($channels as $channel) { ?>
            <li><a class="dropdown-item" href="<?= Filters::AddFilter(str_replace("video/index", "video/adv-search", $preurl), "ch", str_replace("+", "%2B", $channel->Kanal)); ?>"><?= $channel->Kanal ?></a></li>
        <?php } ?>
    </ul>
</div>
<div class="dropdown d-inline ms-2">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="yearSelectButton" data-bs-toggle="dropdown" aria-expanded="false">
        Aasta
    </button>
    <ul class="dropdown-menu" aria-labelledby="yearSelectButton">

        <?php 
        $distyrs = array();
        foreach ($years as $year) {
            $cyr = new DateTime($year->Kuupäev);
            if (!in_array($cyr->format("Y"), $distyrs)) {
                $distyrs[] = $cyr->format("Y");
            }
         }
         foreach ($distyrs as $year) { ?>
             <li><a class="dropdown-item" href="<?= Filters::AddFilter($preurl, "year", $year); ?>"><?= $year ?></a></li>
         <?php } ?>
    </ul>
</div>
<?php

$filterset = [
    "q" => [
        "type" => "string",
        "label" => "Märksõna(d)"
    ],
    "ch" => [
        "type" => "string",
        "label" => "Kanal"
    ],
    "del" => [
        "type" => "boolean",
        "label" => "Kustutatud"
    ],
    "live" => [
        "type" => "boolean",
        "label" => "Otseülekanne"
    ],
    "hd" => [
        "type" => "boolean",
        "label" => "Kõrge kvaliteet"
    ],
    "sub" => [
        "type" => "boolean",
        "label" => "Subtiitrid"
    ],
    "pub" => [
        "type" => "boolean",
        "label" => "Avalik"
    ],
    "cat" => [
        "type" => "string",
        "label" => "Kategooria"
    ],
    "year" => [
        "type" => "string",
        "label" => "Aasta"
    ],
];
echo Filters::DisplayBooleanSelectors($preurl, $filterset);
echo Filters::DisplayFilters($filterset);
?>
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
            <a href="<?= Url::to(["/video/view/", 'id' => $video->ID]) ?>" style="text-decoration: none;">
                <div class='card my-5 mx-auto' style='width: 18rem;'>
                    <img class="card-img-top" style="width: 100%;" src="<?= Url::to("@web/thumbs/".$video->ID.".jpg", true)?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= Html::encode("{$video->Video}") ?></h5>
                        <p class="card-text"><?= $video->Kanal ?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
