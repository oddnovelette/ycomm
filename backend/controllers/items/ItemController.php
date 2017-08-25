<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 25.08.2017
 * Time: 06:15
 */

namespace backend\controllers\items;

use application\forms\Items\{
    ImageForm,
    ItemCreateForm,
    ItemEditForm,
    PriceForm
};
use application\models\Items\{Item, Variation};

use application\services\Items\ItemService;
use backend\forms\ItemSearch;
use yii\base\Module;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ItemController extends Controller
{
    private $service;

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
                    'delete-image' => ['POST'],
                    'move-image-up' => ['POST'],
                    'move-image-down' => ['POST'],
                ],
            ],
        ];
    }
    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $item = $this->findModel($id);
        $variationsProvider = new ActiveDataProvider([
            'query' => $item->getVariations()->orderBy('name'),
            'key' => function (Variation $variation) use ($item) {
                return [
                    'item_id' => $item->id,
                    'id' => $variation->id,
                ];
            },
            'pagination' => false,
        ]);
        $imagesForm = new ImageForm();
        if ($imagesForm->load(\Yii::$app->request->post()) && $imagesForm->validate()) {
            try {
                $this->service->uploadImages($item->id, $imagesForm);
                return $this->redirect(['view', 'id' => $item->id]);
            } catch (\DomainException $e) {
                \Yii::$app->errorHandler->logException($e);
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('view', [
            'item' => $item,
            'variationsProvider' => $variationsProvider,
            'imagesForm' => $imagesForm,
        ]);
    }
    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new ItemCreateForm();
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $item = $this->service->create($form);
                return $this->redirect(['view', 'id' => $item->id]);
            } catch (\DomainException $e) {
                \Yii::$app->errorHandler->logException($e);
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', ['model' => $form]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $item = $this->findModel($id);
        $form = new ItemEditForm($item);
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($item->id, $form);
                return $this->redirect(['view', 'id' => $item->id]);
            } catch (\DomainException $e) {
                \Yii::$app->errorHandler->logException($e);
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'item' => $item,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     */
    public function actionPrice($id)
    {
        $item = $this->findModel($id);
        $form = new PriceForm($item);
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->changePrice($item->id, $form);
                return $this->redirect(['view', 'id' => $item->id]);
            } catch (\DomainException $e) {
                \Yii::$app->errorHandler->logException($e);
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('price', [
            'model' => $form,
            'item' => $item,
        ]);
    }
    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $e) {
            \Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }
    /**
     * @param integer $id
     * @param $image_id
     * @return mixed
     */
    public function actionDeleteImage($id, $image_id)
    {
        try {
            $this->service->deleteImage($id, $image_id);
        } catch (\DomainException $e) {
            \Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['view', 'id' => $id, '#' => 'images']);
    }

    /**
     * @param integer $id
     * @param $image_id
     * @return mixed
     */
    public function actionMoveImageUp($id, $image_id)
    {
        $this->service->moveImageUp($id, $image_id);
        return $this->redirect(['view', 'id' => $id, '#' => 'images']);
    }

    /**
     * @param integer $id
     * @param $image_id
     * @return mixed
     */
    public function actionMoveImageDown($id, $image_id)
    {
        $this->service->moveImageDown($id, $image_id);
        return $this->redirect(['view', 'id' => $id, '#' => 'images']);
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