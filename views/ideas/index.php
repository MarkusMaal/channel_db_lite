<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
/** @var yii\web\View $this */

$this->title = 'Ideed' . (isset($_GET["page"])?" - Leht ".$_GET["page"]:"");
?>
<div class="mx-auto text-center">
<?= "<a class=\"btn btn-secondary m-2 text-center\" href=\"?ord=" . (!empty($_GET["ord"])?(($_GET["ord"] == "DESC")?"ASC\">Õigetpidi järjestus":"DESC\">Tagurpidi järjestus"):"ASC\">Õigetpidi järjestus") . "</a>" ?>
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
                <a href="view/<?= $idea->id ?>" style="text-decoration: none;">
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