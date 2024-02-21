<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
/** @var yii\web\View $this */

$this->title = 'Kanali gallerii' . (isset($_GET["page"])?" - Leht ".$_GET["page"]:"");
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
                <a href="view/<?= $channel->ID ?>" style="text-decoration: none;">
                    <div class='card my-5 mx-auto' style='width: 18rem;'>
                        <img class="card-img-top" style="width: 100%;" src="<?=
                    (file_exists("gallery/logos/{$channel->ID}/$logoid.png")?"logos/{$channel->ID}/$logoid.png":"")
                    ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= Html::encode("{$channel->Kanal}") ?></h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>