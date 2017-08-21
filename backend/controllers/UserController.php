<?php

namespace backend\controllers;

use application\forms\Users\{UserCreateForm, UserEditForm};
use application\services\UserService;
use Yii;
use application\models\User;
use backend\forms\UserForm;
use yii\base\Module;
use yii\web\{Controller, NotFoundHttpException};
use yii\filters\VerbFilter;

/**
 * Class UserController
 * @package backend\controllers
 */
class UserController extends Controller
{
    private $userService;

    public function __construct
        (
            string $id,
            Module $module,
            UserService $userService,
            array $config = null
        )
    {
        parent::__construct($id, $module, $config);
        $this->userService = $userService;
    }

    /**
     * @inheritdoc
     */
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserForm();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new UserCreateForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->userService->create($form);
                Yii::$app->session->setFlash('success', 'Successfully created');
                return $this->redirect(['view', 'id' => $user->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', ['model' => $form]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate(int $id)
    {
        $user = $this->findModel($id);
        $form = new UserEditForm($user);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->userService->edit($user->id, $form);
                Yii::$app->session->setFlash('success', 'Successfully updated');
                return $this->redirect(['view', 'id' => $user->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'user' => $user,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Successfully deleted');
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
