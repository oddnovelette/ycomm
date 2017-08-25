<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 25.08.2017
 * Time: 05:22
 */

namespace backend\controllers\items;

use application\forms\Items\VariationForm;
use application\models\Items\Item;
use application\services\Items\ItemService;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class VariationController
 * @package backend\controllers\items
 */
class VariationController extends Controller
{
    private $service;

    /**
     * VariationController constructor.
     * @param string $id
     * @param Module $module
     * @param ItemService $service
     * @param array|null $config
     */
    public function __construct(string $id, Module $module, ItemService $service, array $config = null)
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function behaviors() : array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect('items/item');
    }

    /**
     * @param $item_id
     * @return mixed
     */
    public function actionCreate($item_id)
    {
        $variation = $this->findModel($item_id);
        $form = new VariationForm();
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->addVariation($variation->id, $form);
                return $this->redirect(['items/variation/view', 'id' => $variation->id, '#' => 'variations']);
            } catch (\DomainException $e) {
                \Yii::$app->errorHandler->logException($e);
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'variation' => $variation,
            'model' => $form,
        ]);
    }

    /**
     * @param integer $item_id
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($item_id, $id)
    {
        $item = $this->findModel($item_id);
        $variation = $item->getVariation($id);
        $form = new VariationForm($variation);
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->editVariation($item->id, $variation->id, $form);
                return $this->redirect(['items/item/view', 'id' => $item->id, '#' => 'variations']);
            } catch (\DomainException $e) {
                \Yii::$app->errorHandler->logException($e);
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'item' => $item,
            'model' => $form,
            'variation' => $variation,
        ]);
    }

    /**
     * @param $item_id
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($item_id, $id)
    {
        $item = $this->findModel($item_id);
        try {
            $this->service->removeVariation($item->id, $id);
        } catch (\DomainException $e) {
            \Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['items/item/view', 'id' => $item->id, '#' => 'variations']);
    }

    /**
     * @param integer $id
     * @return Item, the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) : Item
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}