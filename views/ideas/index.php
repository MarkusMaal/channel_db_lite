<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
require_once(dirname(__DIR__, 2)."/helpers/Filters.php");
/** @var yii\web\View $this */

$this->title = 'Ideed' . (isset($_GET["page"])?" - Leht ".$_GET["page"]:"");
$preurl =Yii::$app->request->getUrl();
$ord = isset($_GET["ord"]) ? $_GET["ord"] : "DESC";
$nord = $ord == "ASC" ? "DESC" : "ASC";
?>
<div class="mx-auto text-center">
<?= "<a class=\"btn btn-secondary m-2 text-center\" href=\"" . Filters::AddFilter($preurl, "ord", $nord) . "\">". (($nord == "ASC")?"Õigetpidi järjestus":"Tagurpidi järjestus") . "</a>" ?>
<div class="dropdown d-inline">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="categorySelectButton" data-bs-toggle="dropdown" aria-expanded="false">
        Klass
    </button>
    <ul class="dropdown-menu" aria-labelledby="categorySelectButton">

        <?php foreach ($classes as $class) { ?>
            <li><a class="dropdown-item" href="<?= Filters::AddFilter($preurl, "class", $class->Klass); ?>"><?= $class->Klass ?></a></li>
        <?php } ?>
    </ul>
</div>
<div class="dropdown d-inline ms-2">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="categorySelectButton" data-bs-toggle="dropdown" aria-expanded="false">
        Kanal
    </button>
    <ul class="dropdown-menu" aria-labelledby="categorySelectButton">

        <?php foreach ($channels as $channel) { ?>
            <li><a class="dropdown-item" href="<?= Filters::AddFilter($preurl, "ch", $channel->Kanal); ?>"><?= $channel->Kanal ?></a></li>
        <?php } ?>
    </ul>
</div>
<?php
$filterset = [
    "q" => [
        "type" => "string",
        "label" => "Märksõna(d)"
    ],
    "done" => [
        "type" => "boolean",
        "label" => "Valmis"
    ],
    "class" => [
        "type" => "string",
        "label" => "Klass"
    ],
    "live" => [
        "type" => "boolean",
        "label" => "Otseülekanne"
    ],
    "ch" => [
        "type" => "string",
        "label" => "Kanal"
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