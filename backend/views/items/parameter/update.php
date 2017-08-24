<?php

/* @var $this yii\web\View */
/* @var $parameter application\models\Items\Parameter */
/* @var $model application\forms\Items\ParametersForm */

$this->title = 'Update item parameter: ' . $parameter->name;
$this->params['breadcrumbs'][] = ['label' => 'Parameters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $parameter->name, 'url' => ['view', 'id' => $parameter->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="characteristic-update">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
