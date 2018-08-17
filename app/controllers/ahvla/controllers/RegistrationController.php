<?php

namespace ahvla\controllers;

use Input;
use Config;
use Request;
use Session;
use Response;
use Controller;
use ahvla\form\RegistrationForm;
use ahvla\entity\counties\CountiesRepository;
use Illuminate\View\Factory;
use Illuminate\Routing\Redirector;
use Illuminate\Foundation\Application;
use Illuminate\Mail\Mailer;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application as App;

class RegistrationController extends Controller
{

    /**
     * @var Factory
     */
    private $viewFactory;

    /**
     * @var Redirector
     */
    private $redirect;

    /**
     * @var RegistrationForm
     */
    private $registrationForm;

    /**
     * @var InformationMessagesRepository
     */
    private $informationMessageRepository;

    /**
     * @var Application
     */
    private $app;

    /**
     * The mailer object
     * @var Mailer
     */
    private $mail;

    /**
     * The config object
     * @var Repository
     */
    private $config;

    /**
     * @param Factory $viewFactory
     * @param RegistrationForm $registrationForm
     * @param Redirector $redirect
     * @param AuthenticationManager $authenticationManager
     * @param Mailer $mail
     */
    function __construct(Factory $viewFactory,
        RegistrationForm $registrationForm,
        Redirector $redirect,
        Session $session,
        Application $app,
        Repository $config, 
        Mailer $mail,
        CountiesRepository $countiesRepository
        )
    {
        $this->viewFactory = $viewFactory;
        $this->redirect = $redirect;
        $this->registrationForm = $registrationForm;
        $this->session = $session;
        $this->app = $app;
        $this->mail = $mail;
        $this->config = $config;
        $this->countiesRepository = $countiesRepository;


    }

    public function indexAction()
    {

        $counties = [''=>''];

        foreach ($this->countiesRepository->all() as $row) {
            $counties[$row->counties_name] = $row->counties_name;
        }

        $viewData = [
        'select_counties_elements' => $counties
        ];

        return Response::view('register.register',$viewData)->header('Register-Screen', '/register');

    }

    public function registrationAction() {

        $validator = $this->registrationForm->getValidator();

        if ($validator->fails()) {
            return $this->redirect->back()
            ->withErrors($validator)
            ->withInput();
        }

        $input = Input::all();

        // init mail vars
        $email = $this->config->get('ahvla.registration.email');
        $subject = $this->config->get('ahvla.registration.subject');

        $mailVars = [
        'existing_customer' => isset($input['existing_customer']) && $input['existing_customer'] == 1 ? 'Yes' : 'No',
        'business_name' => $input['business_name'],
        'contact_name' => $input['contact_name'],
        'address_1' => $input['address_1'],
        'address_2' => $input['address_2'],
        'address_3' => $input['address_3'],
        'county' => $input['county'],
        'postcode' => $input['postcode'],
            'email' => $input['email'],
        'telephone' => $input['telephone'],
        ];

        // send mail
        $this->mail->send('emails.registration', $mailVars, function($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });

        return Response::view('register.register-confirm');

    }

}
