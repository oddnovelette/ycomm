<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 23.08.2017
 * Time: 17:43
 */

namespace backend\controllers\items;

use application\forms\Items\ParametersForm;
use application\models\Items\Parameter;
use application\services\Items\ParameterService;
use backend\forms\ParameterSearch;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class ParameterController
 * @package controllers\items
 */
class ParameterController extends Controller
{
    private $service;

    /**
     * ParameterController constructor.
     * @param string $id
     * @param Module $module
     * @param ParameterService $service
     * @param array|null $config
     */
    public function __construct(string $id, Module $module, ParameterService $service, array $config = null)
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
        $searchModel = new ParameterSearch();
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
            'parameter' => $this->findModel($id),
        ]);
    }
    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new ParametersForm();
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $characteristic = $this->service->create($form);
                return $this->redirect(['view', 'id' => $characteristic->id]);
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
        $parameter = $this->findModel($id);
        $form = new ParametersForm($parameter);
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($parameter->id, $form);
                return $this->redirect(['view', 'id' => $parameter->id]);
            } catch (\DomainException $e) {
                \Yii::$app->errorHandler->logException($e);
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'parameter' => $parameter,
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
        return $this->redirect(['index']);
    }
    /**
     * @param integer $id
     * @return Parameter, the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) : Parameter
    {
        if (($model = Parameter::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}