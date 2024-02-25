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
                        <img class="card-img-top" style="width: 100%;" src="<?= Url::to("@web/gallery/logos/".$channel->ID."/".$logoid.".png", true) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= Html::encode("{$channel->Kanal}") ?></h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>