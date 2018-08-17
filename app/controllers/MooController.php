<?php

use ahvla\entity\submission\Submission;

use GuzzleHttp\Client;
use Illuminate\Mail\Mailer;
use Illuminate\Foundation\Application;

class MooController extends Controller
{
    public function __construct(Application $app, Mailer $mail)
    {
        $this->mail = $mail;
    }

    public function woo()
    {
        $to = 'kaichan1@gmail.com';
        $subject = 'Subject 111';
        $message = 'Message <b>222</b>';

        echo App::make('SendGrid')->Send( [ 'to' => $to, 'subject' => $subject, 'message' => $message ] );
    }

    public function moo()
    {
        $session = Session::all();

        if (array_key_exists("FullSubmissionForms", $session)) {
            if (Request::has('draftSubmissionId')) {

                $draftForm = unserialize($session["FullSubmissionForms"]);


                print 'THIS DRAFT SUBMISSION FORM:<br>';
                print '<pre>';
                print_r($draftForm[Request::get('draftSubmissionId')]);
            }
            else {
                print 'ALL SUBMISSION FORMS:<br>';
                print '<pre>';
                print_r(unserialize($session["FullSubmissionForms"]));
            }
        }
    }

    public function baa()
    {
        $session = Session::all();
        if (array_key_exists("FullSubmissionForms", $session)) {
            if (Request::has('draftSubmissionId')) {

                $form = unserialize($session["FullSubmissionForms"]);
                $draftForm = $form[Request::get('draftSubmissionId')];

                $animalDetailsForm = $draftForm->animalDetailsForm;
                $yourBasketForm = $draftForm->yourBasketForm;
                $basket = $draftForm->basket;


                print 'DATA FOR ANIMALID DEBUGGING:<br>';

                print 'species: ' .$animalDetailsForm->species. '<br>';
                print 'animals_test_qty: ' .$animalDetailsForm->animals_test_qty. '<br>';

                for ($i=0; $i<20; ++$i) {
                    $property = 'animal_id' .$i;
                    if (!empty($animalDetailsForm->$property)) {
                        print $property. ': ' .$animalDetailsForm->$property. '<br>';
                    }
                }

                print '<pre>';
                print_r($yourBasketForm);
                print_r($basket);

            }
            else {
                print 'ALL SUBMISSION FORMS:<br>';
                print '<pre>';
                print_r(unserialize($session["FullSubmissionForms"]));
            }
        }
    }

}