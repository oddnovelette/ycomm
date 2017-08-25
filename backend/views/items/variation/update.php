<?php

/* @var $this yii\web\View */
/* @var $item application\models\Items\Item */
/* @var $variation application\models\Items\Variation */
/* @var $model application\forms\Items\VariationForm */

$this->title = 'Update variation: ' . $variation->name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['items/item/index']];
$this->params['breadcrumbs'][] = ['label' => $item->name, 'url' => ['items/item/view', 'id' => $item->id]];
$this->params['breadcrumbs'][] = $variation->name;
?>
<div class="variation-update">
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
