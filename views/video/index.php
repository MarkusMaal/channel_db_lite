<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
require_once(Yii::getAlias("@app/helpers/InitLang.php"));
require_once(Yii::getAlias("@app/helpers/LangUtils.php"));
require_once(dirname(__DIR__, 2)."/helpers/Filters.php");

/** @var yii\web\View $this */

$this->title = Yii::t('videos', 'Videod') . (isset($_GET["page"])?" - ". Yii::t("videos", "Leht {0}", $_GET["page"]):"");
$baseurl = str_replace($_SERVER["DOCUMENT_ROOT"], "", Yii::$app->basePath);

?>
<div class="mx-auto text-center">
<?php
$preurl = Yii::$app->request->getUrl();
$ord = isset($_GET["ord"]) ? $_GET["ord"] : "DESC";
$nord = $ord == "ASC" ? "DESC" : "ASC";
echo "<a class=\"btn btn-secondary m-2 text-center\" href=\"".Filters::AddFilter($preurl, "ord", $nord) . (!empty($_GET["ord"])?(($_GET["ord"] == "DESC")?"\">".Yii::t("app", "Õigetpidi järjestus")."":"\">".Yii::t("app", "Tagurpidi järjestus").""):"\">".Yii::t("app", "Õigetpidi järjestus")."") . "</a>";
?>
<div class="dropdown d-inline">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="categorySelectButton" data-bs-toggle="dropdown" aria-expanded="false">
        <?= Yii::t('videos', 'Kategooria'); ?>
    </button>
    <ul class="dropdown-menu" aria-labelledby="categorySelectButton">

        <?php foreach ($categories as $category) { ?>
            <li><a class="dropdown-item" href="<?= Filters::AddFilter(str_replace("video/index", "video/adv-search", $preurl), "cat", $category->Category); ?>"><?= LangUtils::GetMuiCategory(Yii::$app->language, $category) ?></a></li>
        <?php } ?>
    </ul>
</div>
<div class="dropdown d-inline ms-2">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="categorySelectButton" data-bs-toggle="dropdown" aria-expanded="false">
    <?= Yii::t('videos', 'Kanal'); ?>
    </button>
    <ul class="dropdown-menu" aria-labelledby="categorySelectButton">

        <?php foreach ($channels as $channel) { ?>
            <li><a class="dropdown-item" href="<?= Filters::AddFilter(str_replace("video/index", "video/adv-search", $preurl), "ch", str_replace("+", "%2B", $channel->Kanal)); ?>"><?= LangUtils::GetMuiChannel(Yii::$app->language, $channel) ?></a></li>
        <?php } ?>
    </ul>
</div>
<div class="dropdown d-inline ms-2">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="yearSelectButton" data-bs-toggle="dropdown" aria-expanded="false">
        <?= Yii::t('videos', 'Aasta'); ?>
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
        "label" => Yii::t("videos", "Märksõna(d)")
    ],
    "ch" => [
        "type" => "string",
        "label" => Yii::t("videos", "Kanal")
    ],
    "del" => [
        "type" => "boolean",
        "label" => Yii::t("videos", "Kustutatud")
    ],
    "live" => [
        "type" => "boolean",
        "label" => Yii::t("videos", "Otseülekanne")
    ],
    "hd" => [
        "type" => "boolean",
        "label" => Yii::t("videos", "Kõrge kvaliteet")
    ],
    "sub" => [
        "type" => "boolean",
        "label" => Yii::t("videos", "Subtiitrid")
    ],
    "pub" => [
        "type" => "boolean",
        "label" => Yii::t("videos", "Avalik")
    ],
    "cat" => [
        "type" => "string",
        "label" => Yii::t("videos", "Kategooria")
    ],
    "year" => [
        "type" => "string",
        "label" => Yii::t("videos", "Aasta")
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
<p class='text-center'><?= Yii::t('app', 'Leiti {0} vastet.', $pagination->totalCount) ?></p>
<div class='row mx-auto'>
    <?php foreach ($videos as $video): ?>
        <div class='col'>
            <a href="<?= Url::to(["/video/view/", 'id' => $video->ID]) ?>" style="text-decoration: none;">
                <div class='card my-5 mx-auto' style='width: 18rem;'>
                    <?php if (!isset($_COOKIE["thumbnails"]) || $_COOKIE["thumbnails"] != "false") {?>
                    <img class="card-img-top" style="width: 100%;" src="<?= Url::to("@web/thumbs/".$video->ID.".jpg", true)?>">
                    <?php } ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= LangUtils::GetMuiTitle(Yii::$app->language, $video) ?></h5>
                        <p class="card-text"><?= $video->Kanal ?></p>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
