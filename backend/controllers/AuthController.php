<?php
namespace backend\controllers;

use application\services\AuthService;
use Yii;
use yii\base\Module;
use yii\web\Controller;
use yii\filters\VerbFilter;
use application\forms\LoginForm;

/**
 * Class AuthController
 * @package backend\controllers
 */
class AuthController extends Controller
{
    private $authService;

    /**
     * AuthController constructor.
     * @param string $id
     * @param Module $module
     * @param AuthService $service
     * @param array|null $config
     */
    public function __construct
        (
            string $id,
            Module $module,
            AuthService $service,
            array $config = null
        )
    {
        parent::__construct($id, $module, $config);
        $this->authService = $service;
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
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    /**
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->layout = 'main-login';
        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->authService->auth($form);
                Yii::$app->user->login($user, $form->rememberMe ? Yii::$app->params['rememberTime'] : 0);
                return $this->goBack();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('login', ['model' => $form]);
    }
    /**
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}