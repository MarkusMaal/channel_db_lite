<?php
use yii\helpers\Html;
?>
<p>You have enetered the following info:</p>

<ul>
    <li><label>Name</label>: <?= Html::encode($model->name) ?></li>
    <li><label>E-mail</label>: <?= Html::encode($model->email) ?></li>
</ul>