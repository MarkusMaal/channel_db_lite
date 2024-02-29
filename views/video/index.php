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
// excludes sutff, that can't be reasonably sorted (i.e. booleans)
$unsortables = ["Kustutatud", "Subtiitrid", "Avalik", "Ülekanne", "HD", "PRIVATE", "Translated"];
?>
<div class="dropdown d-inline ms-2">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="sortButton" data-bs-toggle="dropdown" aria-expanded="false">
    <?= Yii::t('app', 'Sorteeri'); ?>
    </button>
    <ul class="dropdown-menu" aria-labelledby="sortButton">
        <?php foreach($cols as $col) {
            if (!in_array($col, $unsortables)) {
                echo '<li><a class="dropdown-item" href="'. Filters::AddFilter($preurl, "sort", $col) .'">'.Html::encode($col).'</a></li>';
            }
        } ?>
    </ul>
</div>
<div class="dropdown d-inline ms-2">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="reportButton" data-bs-toggle="dropdown" aria-expanded="false">
    <?= Yii::t('app', 'Raport'); ?>
    </button>
    <ul class="dropdown-menu" aria-labelledby="reportButton">
            <li><a class="dropdown-item" href="<?= str_replace("video/adv-search", "video/report", $preurl); ?>"><?= Yii::t("app", "Kuva") ?></a></li>
            <li><a class="dropdown-item" href="<?= Filters::AddFilter(str_replace("video/adv-search", "video/report", $preurl), "save", "true"); ?>"><?= Yii::t("app", "Laadi alla") ?></a></li>
    </ul>
</div>
<?php
echo Filters::DisplayFilters($filterset);
?>
</div>
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
</div>
<p class='text-center'><?= Yii::t('app', 'Leiti {0} vastet.', $pagination->totalCount) ?></p>
<div class='row mx-auto'>
    <?php foreach ($videos as $video): ?>
        <div class='col'>
            <a href="<?= Url::to(["/video/view/", 'id' => $video->ID]) ?>" style="text-decoration: none;">
                <div class='card my-5 mx-auto' style='width: 18rem;'>
                    <?php if (!isset($_COOKIE["thumbnails"]) || $_COOKIE["thumbnails"] != "false") {?>
                    <?php
                        if (!file_exists(Yii::getAlias("@app/web/thumbs/" . $video->ID . ".jpg"))) {
                            $img = Yii::getAlias("@app/web/thumbs/{$video->ID}.jpg");
                            $url = 'http://img.youtube.com/vi/' . str_replace('https://www.youtube.com/watch?v=', '', $video->URL) . '/maxresdefault.jpg';
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url); 
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
                            $output = curl_exec($ch);   
                            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            if ($http_status == "404") {
                                curl_close($ch);
                                $url = 'http://img.youtube.com/vi/' . str_replace('https://www.youtube.com/watch?v=', '', $video->URL) . '/hqdefault.jpg';
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url); 
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                $output = curl_exec($ch);
                                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                curl_close($ch);
                                if ($http_status == "404") {
                                    $url = 'http://img.youtube.com/vi/' . str_replace('https://www.youtube.com/watch?v=', '', $video->URL) . '/sddefault.jpg';
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL, $url); 
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    $output = curl_exec($ch);
                                    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                    curl_close($ch);
                                }
                                $fp = fopen("$img", "w");
                                fwrite($fp, $output);
                                fclose($fp);
                            } else {
                                curl_close($ch);
                                $fp = fopen($img, "w");
                                fwrite($fp, $output);
                                fclose($fp);
                            }
                            
                        }
                        $prefix = "";
                        echo '<img class="card-img-top" style="width: 100%;" src="' . Url::to("@web/thumbs/".$video->ID.".jpg", true) . '"/>';
                    ?>
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
