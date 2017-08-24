<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model application\forms\Items\ParametersForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parameter-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-body">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'type')->dropDownList($model->typeList()) ?>
            <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'required')->checkbox() ?>
            <?= $form->field($model, 'default')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'textVariants')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
