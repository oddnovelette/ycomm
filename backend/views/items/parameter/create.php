<?php

/* @var $this yii\web\View */
/* @var $model application\forms\Items\ParametersForm */

$this->title = 'Create item parameter';
$this->params['breadcrumbs'][] = ['label' => 'Parameters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameter-create">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
