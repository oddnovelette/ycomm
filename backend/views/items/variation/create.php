<?php

/* @var $this yii\web\View */
/* @var $item application\models\Items\Item */
/* @var $model application\forms\Items\VariationForm */

$this->title = 'Create variation';
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['items/item/index']];
$this->params['breadcrumbs'][] = ['label' => $item->name, 'url' => ['items/item/view', 'id' => $item->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="variation-create">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
