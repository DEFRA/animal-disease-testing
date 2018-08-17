<?php
namespace ahvla\controllers;

use ahvla\form\FeedbackForm;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Mail\Mailer;
use Illuminate\Routing\Redirector;

/**
 * Class FeedbackController
 *
 * Handles feedback
 * @package ahvla\controllers
 */
class FeedbackController extends BaseController
{
    /**
     * The form object used to validate the post data
     * @var FeedbackForm
     */
    private $form;

    /**
     * The redirector object
     * @var Redirector
     */
    private $redirect;

    /**
     * The config object
     * @var Repository
     */
    private $config;

    /**
     * The mailer object
     * @var Mailer
     */
    private $mail;

    /**
     * The constructor
     *
     * @param Application $app
     * @param FeedbackForm $form
     * @param Redirector $redirect
     * @param Repository $config
     * @param Mailer $mail
     */
    public function __construct(Application $app, FeedbackForm $form, Redirector $redirect, Repository $config, Mailer $mail)
    {
        parent::__construct($app);
        $this->app = $app;
        $this->form = $form;
        $this->redirect = $redirect;
        $this->config = $config;
        $this->mail = $mail;
        $this->pageTitle = 'Feedback';
    }

    /**
     * Shows the template for the non-JS page
     *
     * @return \Illuminate\View\View
     */
    public function getAction()
    {
        return $this->makeView('login.feedback', ['pageTitle' => $this->pageTitle, 'feedbackForm' => true]);
    }

    /**
     * Handles the response for the non-JS page
     *
     * @return $this|\Illuminate\View\View
     */
    public function postAction()
    {
        // validate the input
        $isAjax = $this->form->isAjax();
        $validator = $this->form->getValidator();
        if ($validator->fails()) {
            if ($isAjax) {
                return \Response::json(['results' => 0, 'error' => 'Validation failed']);
            } else {
                return $this->redirect->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        // init mail vars
        $user = $this->authenticationManager->getLoggedInUser();
        $email = $this->config->get('ahvla.feedback.email');
        $subject = $this->config->get('ahvla.feedback.subject');
        $mailVars = [
            'feedback' => $this->form->getFeedback(),
            'pageTitle' => $this->form->getPageTitle(),
            'fullName' => $user->getFullname(),
            'practiceName' => $user->getPractice()->getName(),
            'username' => $user->getUsername(),
            'limsCode' => $user->getPracticeLimsCode(),
        ];

        // send mail
        $this->mail->send('emails.feedback', $mailVars, function($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
        if ($this->mail->failures() && $isAjax) {
            return \Response::json(['results' => 0, 'error' => 'Sending feedback email failed']);
        }

        if ($isAjax) {
            return \Response::json(['results' => 1]);
        } else {
            return $this->makeView('login.feedback-success', ['pageTitle' => $this->pageTitle, 'redirectTo' => $this->form->getRedirectTo()]);
        }
    }
}
