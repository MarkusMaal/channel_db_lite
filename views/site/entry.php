<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model,"name")->label("Your name") ?>
    <?= $form->field($model,"email")->label("Your e-mail address") ?>

    <div class="position-relative">
        <div class="form-group top-0 end-0 translate-start-x position-absolute">
            <?= Html::submitButton("Confirm", ["class"=> "btn btn-primary"]) ?>
        </div>
    </div>
<?php ActiveForm::end();