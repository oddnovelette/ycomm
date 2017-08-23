<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 23.08.2017
 * Time: 06:21
 */

namespace backend\controllers\items;

use application\forms\Items\CategoryForm;
use application\models\Items\Category;
use application\services\Items\CategoryService;
use backend\forms\CategorySearch;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class CategoryController
 * @package controllers\items
 */
class CategoryController extends Controller
{
    private $service;

    /**
     * CategoryController constructor.
     * @param string $id
     * @param Module $module
     * @param CategoryService $service
     * @param array|null $config
     */
    public function __construct(string $id, Module $module, CategoryService $service, array $config = null)
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
        $searchModel = new CategorySearch();
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
        return $this->render('view', [
            'category' => $this->findModel($id),
        ]);
    }
    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new CategoryForm();
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $category = $this->service->create($form);
                \Yii::$app->session->setFlash('success', 'Successfully created');
                return $this->redirect(['view', 'id' => $category->id]);
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
        $category = $this->findModel($id);
        $form = new CategoryForm($category);
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($category->id, $form);
                \Yii::$app->session->setFlash('success', 'Successfully updated');
                return $this->redirect(['view', 'id' => $category->id]);
            } catch (\DomainException $e) {
                \Yii::$app->errorHandler->logException($e);
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'category' => $category,
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
            \Yii::$app->errorHandler->logException($e);
            \Yii::$app->session->setFlash('error', $e->getMessage());
        }
        \Yii::$app->session->setFlash('info', 'Successfully deleted');
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) : Category
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}