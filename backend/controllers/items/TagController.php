<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 23.08.2017
 * Time: 04:44
 */

namespace backend\controllers\items;


use application\forms\Items\TagForm;
use application\models\Items\Tag;
use application\services\Items\TagService;
use backend\forms\TagSearch;
use yii\base\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class TagController
 * @package backend\controllers\items
 */
class TagController extends Controller
{
    /**
     * @var TagService
     */
    private $service;

    /**
     * TagController constructor.
     * @param string $id
     * @param Module $module
     * @param TagService $service
     * @param array|null $config
     */
    public function __construct(string $id, Module $module, TagService $service, array $config = null)
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
        $searchModel = new TagSearch();
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
            'tag' => $this->findModel($id),
        ]);
    }
    /**
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new TagForm();
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $tag = $this->service->create($form);
                \Yii::$app->session->setFlash('success', 'Successfully created');
                return $this->redirect(['view', 'id' => $tag->id]);
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
        $tag = $this->findModel($id);
        $form = new TagForm($tag);
        if ($form->load(\Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($tag->id, $form);
                \Yii::$app->session->setFlash('success', 'Successfully updated');
                return $this->redirect(['view', 'id' => $tag->id]);
            } catch (\DomainException $e) {
                \Yii::$app->errorHandler->logException($e);
                \Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'tag' => $tag,
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
     * @return Tag, the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) : Tag
    {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}