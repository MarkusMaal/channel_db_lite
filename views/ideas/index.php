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
// excludes sutff, that can't be reasonably sorted (i.e. booleans)
$unsortables = ["Valmis", "Ülekanne"];
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
    <button class="btn btn-secondary dropdown-toggle" type="button" id="categorySelectButton" data-bs-toggle="dropdown" aria-expanded="false">
    <?= Yii::t('app', 'Raport'); ?>
    </button>
    <ul class="dropdown-menu" aria-labelledby="categorySelectButton">
            <li><a class="dropdown-item" href="<?= str_replace("ideas/adv-search", "ideas/report", $preurl); ?>"><?= Yii::t("app", "Kuva") ?></a></li>
            <li><a class="dropdown-item" href="<?= Filters::AddFilter(str_replace("ideas/adv-search", "ideas/report", $preurl), "save", "true"); ?>"><?= Yii::t("app", "Laadi alla") ?></a></li>
    </ul>
</div>
<?php
echo Filters::DisplayFilters($filterset);
?>
</div>


<div class="text-center">
<?php $colorClass = "orangellow"; ?>
<?= LinkPager::widget([
    'pagination' => $pagination,
    'linkOptions'=> ['class'=> 'text-black py-1 px-2 d-block'],
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
    <ul class='list-group list-group-flush'>
        <?php foreach ($ideas as $idea): ?>
            <li class='list-group-item d-flex justify-content-between align-items-start'>
                <a href="<?= Url::to(["/ideas/view/", "id" => $idea->id]) ?>" style="text-decoration: none;">
                    <div class="ms-2 me-auto text-black">
                        <div class="fw-bold"><?= $idea->Video ?></div>
                        <?= $idea->Kanal ?>
                    </div>
                </a>
                <span class="badge bg-orangellow text-black rounded-pill"><?= $idea->Klass ?></span>
        </li>
        <?php endforeach; ?>
    </div>
</div>