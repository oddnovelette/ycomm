<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 22.08.2017
 * Time: 00:57
 */

namespace backend\controllers\items;

use application\forms\Items\LabelForm;
use application\models\Items\Label;
use application\services\Items\LabelService;
use backend\forms\LabelSearch;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Class LabelController
 * @package backend\controllers
 */
class LabelController extends Controller
{
    private $service;

    /**
     * LabelController constructor.
     * @param string $id
     * @param Module $module
     * @param LabelService $service
     * @param array|null $config
     */
    public function __construct(string $id, Module $module, LabelService $service, array $config = null)
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
        $searchModel = new LabelSearch();
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
            'label' => $this->findModel($id),
        ]);
    }
    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new LabelForm();
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $label = $this->service->create($form);
                \Yii::$app->session->setFlash('success', 'Successfully created');
                return $this->redirect(['view', 'id' => $label->id]);
            } catch (\DomainException $e) {
                \Yii::$app->errorHandler->logException($e);
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }
    /**
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate(int $id)
    {
        $label = $this->findModel($id);
        $form = new LabelForm($label);
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($label->id, $form);
                \Yii::$app->session->setFlash('success', 'Successfully updated');
                return $this->redirect(['view', 'id' => $label->id]);
            } catch (\DomainException $e) {
                \Yii::$app->errorHandler->logException($e);
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'label' => $label,
        ]);
    }
    /**
     * @param integer $id
     * @return mixed
     */
    public function actionDelete(int $id)
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
     * @param int $id
     * @return Label
     */
    protected function findModel(int $id) : Label
    {
        if (($model = Label::findOne($id)) !== null) {
            return $model;
        }
        throw new \RuntimeException('The requested page does not exist.');
    }
}