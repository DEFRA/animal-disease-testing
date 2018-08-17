<?php

namespace ahvla\controllers;

use ahvla\address\DeliveryAddress;
use ahvla\entity\ageCategory\AgeCategoryRepository;
use ahvla\entity\animalBreed\SpeciesAnimalBreedRepository;
use ahvla\entity\product\BasketProduct;
use ahvla\entity\sexGroup\SexGroupRepository;
use ahvla\entity\species\SpeciesRepository;
use ahvla\entity\submission\Submission;
use ahvla\MultipleSubmissionManager;
use Illuminate\Foundation\Application as App;
use ahvla\authentication\AuthenticationManager;
use ahvla\basket\BasketManager;
use Session;
use Input;
use Redirect;
use ahvla\limsapi\LimsApiFactory;
use ahvla\entity\submission\SubmissionRepository;
use Illuminate\Http\Request;
use ahvla\form\submissionSteps\PrintForm;
use ahvla\form\FilterSubmissionsForm;
use Hackzilla\BarcodeBundle\Utility\Barcode;

/*
 * For general submission related actions
 */

class SubmissionController extends StepBaseController
{
    /**
     * @var FilterSubmissionsForm
     */
    private $filterForm;

    /*
     * delivery address of submission
     */
    private $deliveryAddress;

    /*
     * Get products in basket
     */
    private $basketManager = null;

    private $authManager;
    /**
     * @var LimsApiFactory
     */
    private $apiFactory;
    /**
     * @var MultipleSubmissionManager
     */
    private $multipleSubmissionManager;

    public function __construct(
        App $app,
        DeliveryAddress $deliveryAddress,
        LimsApiFactory $limsApiFactory,
        AuthenticationManager $authManager,
        SubmissionRepository $submissionRepository,
        MultipleSubmissionManager $multipleSubmissionManager,
        Request $request,
        FilterSubmissionsForm $filterForm,
        Barcode $barcode
    )
    {
        parent::__construct($app);
        $this->deliveryAddress = $deliveryAddress;
        if($this->fullSubmissionForm){
            $this->basketManager = new BasketManager(
                $app,
                $this->fullSubmissionForm
            );
        }
        $this->limsApiFactory = $limsApiFactory;
        $this->authManager = $authManager;
        $this->submissionRepository = $submissionRepository;
        $this->multipleSubmissionManager = $multipleSubmissionManager;
        $this->request = $request;
        $this->filterForm = $filterForm;
        $this->barcode = $barcode;
        $this->barcode->setGenbarcodeLocation('/usr/local/bin/genbarcode');
    }

    /*
     * Print address label
     */
    public function printAddressLabelAction()
    {
        $user = $this->authenticationManager->getLoggedInUser();

        try {
            $addresses = $this->deliveryAddress->getTestAddresses([
                'draftSubmissionId'=>$this->fullSubmissionForm->draftSubmissionId,
                'pvsId'=>$user->getPracticeLimsCode()
            ]);
        } catch (Exception $e) {
        }

        // single or multiple
        $addressConfig = Input::get('address_config');
        $separateAddressesIdx = Input::get('separate_addresses_idx');
        $labId = Input::get('lab_id');

        $viewData = [
            'addresses' => $addresses,
            'addressConfig' => $addressConfig,
            'submissionId' => $this->fullSubmissionForm->submissionId,
            'separateAddressesIdx' => $separateAddressesIdx,
            'barcode' => $this->barcode->outputHtml($this->fullSubmissionForm->submissionId),
            'labId' => $labId
        ];

        return $this->makeView('submission.steps.partials.print.address-label', $viewData);
    }

    /*
     * Print dispatch note
     */
    /**
     * @return \Illuminate\View\View
     */
    public function printDispatchNoteAction()
    {
        /** @var SpeciesRepository $speciesRepo */
        $speciesRepo = $this->app->make(SpeciesRepository::CLASS_NAME);
        /** @var SpeciesAnimalBreedRepository $speciesRepo */
        $breedsRepo = $this->app->make(SpeciesAnimalBreedRepository::CLASS_NAME);
        /** @var SexGroupRepository $speciesRepo */
        $sexGroupRepo = $this->app->make(SexGroupRepository::CLASS_NAME);
        /** @var AgeCategoryRepository $speciesRepo */
        $ageCategoryRepo = $this->app->make(AgeCategoryRepository::CLASS_NAME);

        // for pvsId
        $user = $this->authenticationManager->getLoggedInUser();

        try {
            $addresses = $this->deliveryAddress->getTestAddresses([
                'draftSubmissionId'=>$this->fullSubmissionForm->draftSubmissionId,
                'pvsId'=>$user->getPracticeLimsCode()
            ]);
        } catch (Exception $e) {
        }

        $animalDetailsForm = $this->fullSubmissionForm->animalDetailsForm;
        $ClinicalHistory = $this->fullSubmissionForm->clinicalHistoryForm;
        $BasketProducts = $this->fullSubmissionForm->basket->getProducts();

        // single or multiple
        $addressConfig = Input::get('address_config');
        $separateAddressesIdx = Input::get('separate_addresses_idx');
        $labId = Input::get('lab_id');

        // if any packages, collect associated info & sort
        $addresses->addPackageInfo($addressConfig, $BasketProducts);

        $clientDetailsForm = $this->fullSubmissionForm->clientDetailsForm;

        $clientDetailsForm->beforeSave($this->fullSubmissionForm);

        // get submission
        $user = $this->authenticationManager->getLoggedInUser();

        $input['submissionId'] = $this->fullSubmissionForm->draftSubmissionId;
        $input['pvsId'] = $user->getPracticeLimsCode();

        // Get submissions
        $submission = $this->submissionRepository->getSingleSubmission($input);

        // Get PVS details
        $pvs = $this->submissionRepository->getPVS($input);

        $this->barcode->setHeight(80);
        $this->barcode->setScale(1.5);

        $species = $animalDetailsForm->getSpecies();

        $viewData = [
            'addresses' => $addresses,
            'species'=>$speciesRepo->getLabelByLimsCode($species),
            'breed'=>$submission->animalBreed->getDescription(),
            'sexGroup'=>$sexGroupRepo->getLabelByLimsCode($animalDetailsForm->sexGroup),
            'ageCategory'=>$ageCategoryRepo->getLabelBySpeciesLimsCode($animalDetailsForm->age_category, $species),
            'ClinicalHistory' => $ClinicalHistory,
            'reviewConfirmForm' => $this->fullSubmissionForm->reviewAndConfirmForm,
            'BasketProducts' => $BasketProducts,
            'submissionId' => $this->fullSubmissionForm->submissionId,
            'addressConfig' => $addressConfig,
            'clientDetailsForm' => $clientDetailsForm,
            'separateAddressesIdx' => $separateAddressesIdx,
            'pvsClient' => $this->fullSubmissionForm->clientDetailsForm->getChosenClient(),
            'submission' => $submission,
            'user' => $user,
            'pvs' => $pvs,
            'barcode' => $this->barcode->outputHtml($this->fullSubmissionForm->submissionId),
            'labId' => $labId
        ];



        return $this->makeView('submission.steps.partials.print.dispatch-note', $viewData);
    }

    public function viewPastSubmissionAction()
    {
        $submission_id = $this->request->get('submissionId', '');

        $limsApiClientViewSubmission = $this->limsApiFactory->newViewSubmissionLimsService();
        $user = $this->authManager->getLoggedInUser();

        $params = ['pvsId' => $user->getPracticeLimsCode(),
            'submissionId' => $submission_id,
            'isDraft' => false];

        /** @var Submission $submissionObjectViewSubmission */
        $submissionObjectViewSubmission = $limsApiClientViewSubmission->execute($params);

        // get delivery addresses
        try {
            $addresses = $submissionObjectViewSubmission->sampleAddresses;
        } catch (Exception $e) {
        }

        $viewData = [
            // Caption and value pairs, structured by report section
            'submission_data' =>
            [
                'Submission' => [
                    'Draft submission id' => $submissionObjectViewSubmission->draftSubmissionId,
                    'Reference number' => $submission_id,
                    'Your reference' => $submissionObjectViewSubmission->sendersReference,
                    'Date submitted' => date( 'd/M/Y', mktime( 0, 0, 0, date_parse($submissionObjectViewSubmission->limsSubmittedDate)['month'],
                                                                        date_parse($submissionObjectViewSubmission->limsSubmittedDate)['day'],
                                                                        date_parse($submissionObjectViewSubmission->limsSubmittedDate)['year'] ) ),
                    'Status' => $submissionObjectViewSubmission->status,
                    'Digital / Paper' => $submissionObjectViewSubmission->submissionMethod ? 'Digital' : 'Paper',
                    'Submission type' => empty($submissionObjectViewSubmission->submissionReason) ? '---':$submissionObjectViewSubmission->submissionReason,
                    'Vet' => $submissionObjectViewSubmission->clinician,
                    'Email notification' => empty($submissionObjectViewSubmission->resultsReadyConfirmationEmail) ? '---':$submissionObjectViewSubmission->resultsReadyConfirmationEmail,
                    'Text notification' => empty($submissionObjectViewSubmission->resultsReadyConfirmationPhoneNumber) ? '---':$submissionObjectViewSubmission->resultsReadyConfirmationPhoneNumber,
                ],
               'Client details' => [
                   'Client / Owner' => $submissionObjectViewSubmission->clientName,
                   'CPH (or Postcode)' => $submissionObjectViewSubmission->clientPostcodeCPHH,
                   'Animals at client' => $submissionObjectViewSubmission->areAnimalsAtFarmAddress?'Yes':'No',
                   'Animal location' => $submissionObjectViewSubmission->animalPostcodeCPHH,
                ],
               'Animal details' => [
                   'Species / Number' => $submissionObjectViewSubmission->getAnimalSpecies(),
                   'Breed' => $submissionObjectViewSubmission->getAnimalBreed(),
                   'Sex' => $submissionObjectViewSubmission->getAnimalSex(),
                   'Age' => htmlentities($submissionObjectViewSubmission->getAnimalAge()),
                   'Organic' => $submissionObjectViewSubmission->getAnimalOrganic(),
                   'Purpose' => $submissionObjectViewSubmission->getAnimalPurpose(),
                   'Housing' => $submissionObjectViewSubmission->getAnimalHousing()
               ],
               'Clinical history' => [
                   'Date of Sampling' => $submissionObjectViewSubmission->dateSamplesTaken ? $submissionObjectViewSubmission->dateSamplesTaken->format('d/M/Y') : '',
                   'Previous submission' => $submissionObjectViewSubmission->previousSubmissionId,
                   'Number in herd' => $submissionObjectViewSubmission->herdTotal,
                   'Number breeding' => $submissionObjectViewSubmission->herdBreedingTotal,
                   'Number in affected group' => $submissionObjectViewSubmission->herdAffectedTotal,
                   'Number affected including dead' => $submissionObjectViewSubmission->herdAffectedIncDead,
                   'Number dead' => $submissionObjectViewSubmission->herdDeadTotal,
                   'Duration of clinical signs' => $submissionObjectViewSubmission->getClinicalSignDuration(),
                   'Clinical signs' => $submissionObjectViewSubmission->getClinicalSignsCSV(),
                   'Written clinical history' => $submissionObjectViewSubmission->clinicalHistory,
               ]
            ],
            'charges' => $submissionObjectViewSubmission->testCharges,
            'samples' => $submissionObjectViewSubmission->tests,
            'test_status' => $submissionObjectViewSubmission->testStatus,
            'samples_will_send_to_separate_addresses' => $submissionObjectViewSubmission->samplesWillSendToSeparateAddresses,
            'sample_addresses' => $addresses,
            'total_submission_cost' => $submissionObjectViewSubmission->totalSubmissionCost,
            'send_samples_package' => $this->fullSubmissionForm->deliveryAddressesForm->send_samples_package
        ];

        if ($submissionObjectViewSubmission->ageDetail) {
            $viewData['submission_data']['Animal details']['Age detail'] = $submissionObjectViewSubmission->ageDetail. ' '.ucfirst(strtolower($submissionObjectViewSubmission->ageIndicator));
            $viewData['submission_data']['Animal details']['Age is estimate'] = $submissionObjectViewSubmission->ageIsEstimate?'Yes':'No';
        }

       return $this->makeView('submission.index.overview', $viewData);
    }

    /*
     * Cancel a submission. Can be draft or a submission that's already been completed
     */
    public function cancelSubmissionAction()
    {
        $input = Input::all();

        $submissionId = isset($input['submission-id'])?$input['submission-id']:'';

        if ($submissionId) {
            $user = $this->authManager->getLoggedInUser();

            $input['submissionId'] = $submissionId;
            $input['pvsId'] = $user->getPracticeLimsCode();

            // Get submissions
            $params = [
                'pvsId' => $user->getPracticeLimsCode(),
                'submissionDraftId' => $submissionId,
                'clinician' => 'Joe Blogs'
            ];

            $limsApiClient = $this->limsApiFactory->newCancelSubmission();
            $cancelResponse = $limsApiClient->execute($params);

        }

        // for the minute, we redirect to the landing page, but this could be controlled from caller when the need arises.
        $url = route('landing').'#start';

        return Redirect::to($url);
    }

    /*
     * Cancel a submission dialog box when JS is enabled
     */
    public function cancelSubmissionDialogAction()
    {
        return $this->makeView('submission.index.dialog', []);
    }

    /*
     * Cancel a submission dialog box when JS is disabled
     */
    public function cancelSubmissionStaticAction()
    {
        $input = Input::all();

        $id = isset($input['draftSubmissionId'])?$input['draftSubmissionId']:'';

        return $this->makeView('submission.index.static', ['id'=>$id]);
    }

    /**
     * @param BasketProduct[] $products
     * @return array
     */
    private function animalSamples($products)
    {
        return [];
    }

    /**
     * @param BasketProduct[] $products
     * @return array
     */
    private function animalTests($products)
    {
        /*
         * [
                   'Porcine viral mega diarrhoea (Erms) antigen Serum - cattle > 30 days old (heparinised blood needed)' => 'Results due 21/10/2014'
                   ]
         */
        return [];
    }

    /*
     * Cancel a submission. Can be draft or a submission that's already been completed
     */
    public function filterSubmissionsAction()
    {
        $input = Input::all();

        $user = $this->authenticationManager->getLoggedInUser();

        $input['clinician'] = substr( Input::get('clinician', ''), 0, 1000 );
        $input['clientId'] = substr( Input::get('clientId', ''), 0, 1000 );

        $input['filter'] = Input::get('filter', null);
        $input['pvsid'] = $user->getPracticeLimsCode();
        $input['status'] = Input::get('status', null);
        $input['date'] = Input::get('date', null);

        // Save filters
        $this->filterForm->saveFiltersInSession($input);

        // Get submissions
        $submissionList = $this->submissionRepository->getSubmissions($input);

        $viewData = [
            'submissionList' => $submissionList,
            'limsPaginator' => $this->submissionRepository->limsPaginator
        ];

        return $this->makeView('login.partials.submissions', $viewData);
    }

}
