<?php
/**
 * Created by PhpStorm.
 * User: odd
 * Date: 13.08.2017
 * Time: 06:11
 */

namespace application\services;

use application\forms\FeedbackForm;
use yii\mail\MailerInterface;

/**
 * Class FeedbackService
 * @package application\services
 */
class FeedbackService
{
    private $adminEmail;
    private $mailer;

    /**
     * FeedbackService constructor.
     * @param string $adminEmail
     * @param MailerInterface $mailer
     */
    public function __construct(string $adminEmail, MailerInterface $mailer)
    {
        $this->adminEmail = $adminEmail;
        $this->mailer = $mailer;
    }

    /**
     * @param FeedbackForm $form
     * @return void
     */
    public function sendMail(FeedbackForm $form) : void
    {
        $send = $this->mailer->compose()
            ->setTo($this->adminEmail)
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();
        if (!$send) throw new \RuntimeException('Sending fault');
    }
}