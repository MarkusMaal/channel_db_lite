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
$preurl = Url::to(["/video/adv-search/"]);
$ord = isset($_GET["ord"]) ? $_GET["ord"] : "DESC";
$nord = $ord == "ASC" ? "DESC" : "ASC";
echo "<a class=\"btn btn-secondary m-2 text-center\" href=\"".Filters::AddFilter($preurl, "ord", $nord) . (!empty($_GET["ord"])?(($_GET["ord"] == "DESC")?"\">Õigetpidi järjestus":"\">Tagurpidi järjestus"):"\">Õigetpidi järjestus") . "</a>";
if (!isset($_GET["cat"])) {
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
<?php
}
if (!isset($_GET["ch"])) {
?>
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
<?php
}
$o = "<p>Filtrid:&nbsp;";
$hasFilters = false;
if (isset($_GET["search"])) { $o .= "<a href='".Filters::ClearFilter($preurl, "search")."'>"."<span class='badge bg-secondary mx-2'>".$_GET["search"]."</span></a>"; $hasFilters = true; }
if (isset($_GET["ch"])) { $o .= "<a href='".Filters::ClearFilter($preurl, "ch")."'>"."<span class='badge bg-secondary mx-2'>Kanal: ".$_GET["ch"]."</span></a>"; $hasFilters = true; }
if (isset($_GET["del"])) { $o .= "<a href='".Filters::ClearFilter($preurl, "del")."'>"."<span class='badge bg-secondary mx-2'> ".($_GET["del"]=="1"?"Kustutatud":"Ei ole kustutatud")."</span></a>"; $hasFilters = true; }
if (isset($_GET["live"])) { $o .= "<a href='".Filters::ClearFilter($preurl, "live")."'>"."<span class='badge bg-secondary mx-2'> ".($_GET["live"]=="1"?"Otseülekanne":"Ei ole otseülekanne")."</span></a>"; $hasFilters = true; }
if (isset($_GET["hd"])) { $o .= "<a href='".Filters::ClearFilter($preurl, "hd")."'>"."<span class='badge bg-secondary mx-2'> ".($_GET["hd"]=="1"?"Kõrge kvaliteet":"Madal kvaliteet")."</span></a>"; $hasFilters = true; }
if (isset($_GET["sub"])) { $o .= "<a href='".Filters::ClearFilter($preurl, "sub")."'>"."<span class='badge bg-secondary mx-2'> ".($_GET["hd"]=="1"?"Sisaldab subtiitreid":"Subtiitriteta")."</span></a>"; $hasFilters = true; }
if (isset($_GET["pub"])) { $o .= "<a href='".Filters::ClearFilter($preurl, "pub")."'>"."<span class='badge bg-secondary mx-2'> ".($_GET["hd"]=="1"?"Avalik":"Pole avalik")."</span></a>"; $hasFilters = true; }
if (isset($_GET["cat"])) { $o .= "<a href='".Filters::ClearFilter($preurl, "cat")."'>"."<span class='badge bg-secondary mx-2'> Kategooria: ".$_GET["cat"]."</span></a>"; $hasFilters = true; }
if (isset($_GET["q"])) { $o .= "<a href='". Filters::ClearFilter($preurl, "q")."'>"."<span class='badge bg-secondary mx-2'>Märksõna(d): ".$_GET["q"]."</span></a>"; $hasFilters = true; }
$o .= "</p>";
if ($hasFilters) {
    echo $o;
}
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
