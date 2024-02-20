<?php
use yii\helpers\Html;
$check = "<span style=\"display: inline-block; width: 2em;\">&#x2714;</span>";
$cross = "<span style=\"display: inline-block; width: 2em;\">&#x274C;</span>";
?>
<div class='container mt-2 mb-2'>
    <div class='card mx-auto' style="width: 90%;">
        <div class="card-body">
            <h1 class="card-title"><?= Html::encode("{$idea->Video}") ?></h1>
            <p><?= Html::encode("{$idea->Kanal}") ?></p>
            <hr>
            <p><?= nl2br(Html::encode("{$idea->Kirjeldus}")) ?></p>
            <hr>
            <a class="btn btn-primary mx-2" target="_blank" onclick="window.navigation.back();">Tagasi</a>
            <br>
            <br>
            <h2 class="mt-4">Attribuudid</h2>
            <ul class="mb-3" style="list-style-type: none; margin: 0; padding: 0;">
                <li><?= (($idea->Valmis)?$check:$cross) ?>Valmis</li>
                <li><?= (($idea->Ülekanne)?$check:$cross) ?>Ülekanne</li>
            </ul>
        </div>
    </div>
</div>