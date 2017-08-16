<?php
namespace frontend\controllers;

use application\services\FeedbackService;
use Yii;
use yii\base\Module;
use yii\web\Controller;
use application\forms\FeedbackForm;

class FeedbackController extends Controller
{
    private $service;

    public function __construct
    (
        string $id,
        Module $module,
        FeedbackService $service,
        array $config = null
    )
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionIndex()
    {
        $form = new FeedbackForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->sendMail($form);
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
                return $this->goHome();
            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }
            return $this->refresh();
        }
        return $this->render('index', ['model' => $form]);
    }
}
