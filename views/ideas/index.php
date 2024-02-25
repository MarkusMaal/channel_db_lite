<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
require_once(dirname(__DIR__, 2)."/helpers/Filters.php");
require_once(Yii::getAlias("@app/helpers/InitLang.php"));
/** @var yii\web\View $this */

$this->title = Yii::t("ideas", "Ideed") . (isset($_GET["page"])?" - ".Yii::t("ideas", "Leht {0}", $_GET["page"]):"");
$preurl =Yii::$app->request->getUrl();
$ord = isset($_GET["ord"]) ? $_GET["ord"] : "DESC";
$nord = $ord == "ASC" ? "DESC" : "ASC";
?>
<div class="mx-auto text-center">
<?php
echo "<a class=\"btn btn-secondary m-2 text-center\" href=\"".Filters::AddFilter($preurl, "ord", $nord) . (!empty($_GET["ord"])?(($_GET["ord"] == "DESC")?"\">".Yii::t("app", "Õigetpidi järjestus")."":"\">".Yii::t("app", "Tagurpidi järjestus").""):"\">".Yii::t("app", "Õigetpidi järjestus")."") . "</a>";
?><div class="dropdown d-inline">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="classSelectButton" data-bs-toggle="dropdown" aria-expanded="false">
        <?= Yii::t("ideas", "Klass") ?>
    </button>
    <ul class="dropdown-menu" aria-labelledby="classSelectButton">

        <?php foreach ($classes as $class) { ?>
            <li><a class="dropdown-item" href="<?= Filters::AddFilter($preurl, "class", $class->Klass); ?>"><?= $class->Klass ?></a></li>
        <?php } ?>
    </ul>
</div>
<div class="dropdown d-inline ms-2">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="channelSelectButton" data-bs-toggle="dropdown" aria-expanded="false">
        <?= Yii::t("ideas", "Kanal") ?>
    </button>
    <ul class="dropdown-menu" aria-labelledby="channelSelectButton">

        <?php foreach ($channels as $channel) { ?>
            <li><a class="dropdown-item" href="<?= Filters::AddFilter($preurl, "ch", $channel->Kanal); ?>"><?= $channel->Kanal ?></a></li>
        <?php } ?>
    </ul>
</div>
<?php
$filterset = [
    "q" => [
        "type" => "string",
        "label" => Yii::t("ideas", "Märksõna(d)")
    ],
    "done" => [
        "type" => "boolean",
        "label" => Yii::t("ideas", "Valmis")
    ],
    "class" => [
        "type" => "string",
        "label" => Yii::t("ideas", "Klass")
    ],
    "live" => [
        "type" => "boolean",
        "label" => Yii::t("ideas", "Otseülekanne")
    ],
    "ch" => [
        "type" => "string",
        "label" => Yii::t("ideas", "Kanal")
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
    <ul class='list-group list-group-flush'>
        <?php foreach ($ideas as $idea): ?>
            <li class='list-group-item d-flex justify-content-between align-items-start'>
                <a href="<?= Url::to(["/ideas/view/", "id" => $idea->id]) ?>" style="text-decoration: none;">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"><?= $idea->Video ?></div>
                        <?= $idea->Kanal ?>
                    </div>
                </a>
                <span class="badge bg-primary rounded-pill"><?= $idea->Klass ?></span>
        </li>
        <?php endforeach; ?>
    </div>
</div>