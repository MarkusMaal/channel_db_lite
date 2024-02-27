<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
require_once(dirname(__DIR__, 2)."/helpers/Filters.php");
require_once(Yii::getAlias("@app/helpers/InitLang.php"));
/** @var yii\web\View $this */

$this->title = Yii::t('gallery', 'Kanali galerii') . (isset($_GET["page"])?" - ".Yii::t("gallery", "Leht {0}", $_GET["page"]):"");
$preurl = Yii::$app->request->getUrl();
$ord = isset($_GET["ord"]) ? $_GET["ord"] : "DESC";
$nord = $ord == "ASC" ? "DESC" : "ASC";
?>
<div class="mx-auto text-center">
<?php
echo "<a class=\"btn btn-secondary m-2 text-center\" href=\"".Filters::AddFilter($preurl, "ord", $nord) . (!empty($_GET["ord"])?(($_GET["ord"] == "DESC")?"\">".Yii::t("app", "Õigetpidi järjestus")."":"\">".Yii::t("app", "Tagurpidi järjestus").""):"\">".Yii::t("app", "Õigetpidi järjestus")."") . "</a>";
?><?php
$filterset = [
    "q" => [
        "type" => "string",
        "label" => Yii::t("gallery", "Märksõna(d)")
    ],
    "del" => [
        "type" => "boolean",
        "label" => Yii::t("gallery", "Kustutatud")
    ],
];
echo Filters::DisplayBooleanSelectors($preurl, $filterset);
echo Filters::DisplayFilters($filterset);
?>
</div>

<div class="text-center">
<?php $colorClass = "skyblue"; ?>
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
<p class='text-center'><?= Yii::t("app", "Leiti {0} vastet.", $pagination->totalCount) ?></p>
<div class='container'>
    <div class='row mx-auto'>
        <?php foreach ($channels as $channel): ?>
            <?php
                $logoid = 1;
                while (file_exists("gallery/logos/".$channel->ID."/".$logoid.".png")) {
                    $logoid++;
                }
                $logoid--;
            ?>
            <div class='col'>
                <a href="<?= Url::to(["/gallery/view/", "id" => $channel->ID]) ?>" style="text-decoration: none;">
                    <div class='card my-5 mx-auto' style='width: 18rem;'>
                        <?php if (!isset($_COOKIE["thumbnails"]) || $_COOKIE["thumbnails"] != "false") {?>
                        <img class="card-img-top" style="width: 100%;" src="<?= Url::to("@web/gallery/logos/".$channel->ID."/".$logoid.".png", true) ?>">
                        <?php } ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= Html::encode("{$channel->Kanal}") ?></h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>