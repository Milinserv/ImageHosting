<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($model, 'image[]')->fileInput(['multiple' => true,'maxlength' => true]) ?>

<div class="form-group">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

