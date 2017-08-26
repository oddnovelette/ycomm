<?php

use kartik\file\FileInput;
use application\models\Items\ParameterValue;
use yii\bootstrap\ActiveForm;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $item application\models\Items\Item */
/* @var $imagesForm application\forms\Items\ImageForm */
/* @var $variationsProvider yii\data\ActiveDataProvider */

$this->title = $item->name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $item->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $item->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">Main item info</div>
                <div class="box-body">
                    <?= DetailView::widget([
                        'model' => $item,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'label_id',
                                'value' => ArrayHelper::getValue($item, 'label.name'),
                            ],
                            'code',
                            'name',
                            [
                                'attribute' => 'price_new',
                                'value' => \backend\forms\PricingFormatter::format($item->price_new),
                            ],
                            [
                                'attribute' => 'price_old',
                                'value' => \backend\forms\PricingFormatter::format($item->price_old),
                            ],
                            [
                                'attribute' => 'category_id',
                                'value' => ArrayHelper::getValue($item, 'category.name'),
                            ],
                            [
                                'label' => 'Other categories',
                                'value' => implode(', ', ArrayHelper::getColumn($item->categories, 'name')),
                            ],
                            [
                                'label' => 'Tags',
                                'value' => implode(', ', ArrayHelper::getColumn($item->tags, 'name')),
                            ],
                        ],
                    ]) ?>
                    <br />
                    <p>
                        <?= Html::a('Change Price', ['price', 'id' => $item->id], ['class' => 'btn btn-primary']) ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">

            <div class="box box-default">
                <div class="box-header with-border">Parameters</div>
                <div class="box-body">

                    <?= DetailView::widget([
                        'model' => $item,
                        'attributes' => array_map(function (ParameterValue $value) {
                            return [
                                'label' => $value->parameter->name,
                                'value' => $value->value,
                            ];
                        }, $item->values),
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">Text for description</div>
        <div class="box-body">
            <?= Yii::$app->formatter->asNtext($item->text) ?>
        </div>
    </div>

    <div class="box" id="modifications">
        <div class="box-header with-border">Variations</div>
        <div class="box-body">
            <p>
                <?= Html::a('Add parameter', ['items/variation/create', 'item_id' => $item->id], ['class' => 'btn btn-success']) ?>
            </p>
            <?= GridView::widget([
                'dataProvider' => $variationsProvider,
                'columns' => [
                    'code',
                    'name',
                    [
                        'attribute' => 'price',
                        'value' => function (\application\models\Items\Variation $model) {
                            return \backend\forms\PricingFormatter::format($model->price);
                        },
                    ],
                    [
                        'class' => ActionColumn::class,
                        'controller' => 'items/variation',
                        'template' => '{update} {delete}',
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <div class="box">
        <div class="box-header with-border">SEO attributes</div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $item,
                'attributes' => [
                    [
                        'attribute' => 'meta.title',
                        'value' => $item->meta->title,
                    ],
                    [
                        'attribute' => 'meta.description',
                        'value' => $item->meta->description,
                    ],
                    [
                        'attribute' => 'meta.keywords',
                        'value' => $item->meta->keywords,
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="box" id="images">
        <div class="box-header with-border">Images for items</div>
        <div class="box-body">

            <div class="row">
                <?php foreach ($item->images as $image): ?>
                    <div class="col-md-2 col-xs-3" style="text-align: center;">
                        <div class="btn-group">
                            <?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span>', ['move-image-up', 'id' => $item->id, 'image_id' => $image->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                            ]); ?>
                            <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete-image', 'id' => $item->id, 'image_id' => $image->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                                'data-confirm' => 'Remove image?',
                            ]); ?>
                            <?= Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', ['move-image-down', 'id' => $item->id, 'image_id' => $image->id], [
                                'class' => 'btn btn-default',
                                'data-method' => 'post',
                            ]); ?>
                        </div>
                        <div>
                            <?= Html::a(
                                Html::img($image->getThumbFileUrl('file', 'thumb')),
                                $image->getUploadedFileUrl('file'),
                                ['class' => 'thumbnail', 'target' => '_blank']
                            ) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php $form = ActiveForm::begin([
                'options' => ['enctype'=>'multipart/form-data'],
            ]); ?>

            <?= $form->field($imagesForm, 'files[]')->label(false)->widget(FileInput::class, [
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => true,
                ]
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Upload', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
