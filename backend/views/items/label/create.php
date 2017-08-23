<?php

/* @var $this yii\web\View */
/* @var $model application\forms\Items\LabelForm */

$this->title = 'Create Label';
$this->params['breadcrumbs'][] = ['label' => 'Labels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="brand-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
