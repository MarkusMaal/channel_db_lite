<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
require_once(Yii::getAlias("@app/helpers/InitLang.php"));
require_once(Yii::getAlias("@app/helpers/LangUtils.php"));
require_once(dirname(__DIR__, 2)."/helpers/Filters.php");

/** @var yii\web\View $this */

$this->title = Yii::t('videos', 'Videod');
$baseurl = str_replace($_SERVER["DOCUMENT_ROOT"], "", Yii::$app->basePath);

?>
<p><?= Yii::t('app', 'Leiti {0} vastet.', count($videos)) ?></p>
<table class='table'>
    <tr>
        <?php foreach ($cols as $col): ?>
            <th><?= Html::encode($col) ?></th>
        <?php endforeach; ?>
    </tr>
    <?php foreach ($videos as $video): ?>
    <tr>
        <?php foreach ($cols as $col): ?>
            <td><?= nl2br(Html::encode($video[$col])) ?></td>
        <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
</table>
