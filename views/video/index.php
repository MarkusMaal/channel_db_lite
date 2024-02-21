<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

/** @var yii\web\View $this */

$this->title = 'Videod' . (isset($_GET["page"])?" - Leht ".$_GET["page"]:"");
$baseurl = str_replace($_SERVER["DOCUMENT_ROOT"], "", Yii::$app->basePath);

function ClearFilter($preurl, $name) {
    $name = str_replace("+", "%2B", $name);
    if (!isset($_GET[$name])) {return $preurl;}
    return "<a href='".str_replace("&".$name."=".$_GET[$name], "", str_replace("?".$name."=".$_GET[$name], "", $preurl))."'>";
}

function AddFilter($preurl, $name, $value) {
    $preurl = str_replace("'>", "", str_replace("<a href='", "", ClearFilter($preurl, $name)));
    if (str_contains($preurl, "?")) {
        return $preurl."&$name=$value";
    } else {
        return $preurl."?$name=$value";
    }
}
?>
<div class="mx-auto text-center">
<?php
$url = $_SERVER["REQUEST_URI"];
$params = "?".substr($url, strrpos($url, '?') + 1);
$pref = (str_contains($params, "=")?"&":"?");
$preurl = str_replace("&ord=".($_GET["ord"]??"DESC"), "", $url);
echo "<a class=\"btn btn-secondary m-2 text-center\" href=\"$preurl{$pref}ord=" . (!empty($_GET["ord"])?(($_GET["ord"] == "DESC")?"ASC\">Õigetpidi järjestus":"DESC\">Tagurpidi järjestus"):"ASC\">Õigetpidi järjestus") . "</a>";
if (!isset($_GET["cat"])) {
?>
<div class="dropdown d-inline">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="categorySelectButton" data-bs-toggle="dropdown" aria-expanded="false">
        Kategooria
    </button>
    <ul class="dropdown-menu" aria-labelledby="categorySelectButton">

        <?php foreach ($categories as $category) { ?>
            <li><a class="dropdown-item" href="<?= AddFilter(str_replace("video/index", "video/adv-search", $preurl), "cat", $category->Category); ?>"><?= $category->Category ?></a></li>
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
            <li><a class="dropdown-item" href="<?= AddFilter(str_replace("video/index", "video/adv-search", $preurl), "ch", str_replace("+", "%2B", $channel->Kanal)); ?>"><?= $channel->Kanal ?></a></li>
        <?php } ?>
    </ul>
</div>
<?php
}
$o = "<p>Filtrid:&nbsp;";
$hasFilters = false;
if (isset($_GET["search"])) { $o .= ClearFilter($preurl, "search")."<span class='badge bg-secondary mx-2'>".$_GET["search"]."</span></a>"; $hasFilters = true; }
if (isset($_GET["ch"])) { $o .= ClearFilter($preurl, "ch")."<span class='badge bg-secondary mx-2'>Kanal: ".$_GET["ch"]."</span></a>"; $hasFilters = true; }
if (isset($_GET["del"])) { $o .= ClearFilter($preurl, "del")."<span class='badge bg-secondary mx-2'> ".($_GET["del"]=="1"?"Kustutatud":"Ei ole kustutatud")."</span></a>"; $hasFilters = true; }
if (isset($_GET["live"])) { $o .= ClearFilter($preurl, "live")."<span class='badge bg-secondary mx-2'> ".($_GET["live"]=="1"?"Otseülekanne":"Ei ole otseülekanne")."</span></a>"; $hasFilters = true; }
if (isset($_GET["hd"])) { $o .= ClearFilter($preurl, "hd")."<span class='badge bg-secondary mx-2'> ".($_GET["hd"]=="1"?"Kõrge kvaliteet":"Madal kvaliteet")."</span></a>"; $hasFilters = true; }
if (isset($_GET["sub"])) { $o .= ClearFilter($preurl, "sub")."<span class='badge bg-secondary mx-2'> ".($_GET["hd"]=="1"?"Sisaldab subtiitreid":"Subtiitriteta")."</span></a>"; $hasFilters = true; }
if (isset($_GET["pub"])) { $o .= ClearFilter($preurl, "pub")."<span class='badge bg-secondary mx-2'> ".($_GET["hd"]=="1"?"Avalik":"Pole avalik")."</span></a>"; $hasFilters = true; }
if (isset($_GET["cat"])) { $o .= ClearFilter($preurl, "cat")."<span class='badge bg-secondary mx-2'> Kategooria: ".$_GET["cat"]."</span></a>"; $hasFilters = true; }
if (isset($_GET["q"])) { $o .= ClearFilter($preurl, "q")."<span class='badge bg-secondary mx-2'>Märksõna(d): ".$_GET["q"]."</span></a>"; $hasFilters = true; }
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
