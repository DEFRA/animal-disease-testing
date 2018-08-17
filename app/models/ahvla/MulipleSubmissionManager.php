<?php

namespace ahvla;

use ahvla\authentication\AuthenticationManager;
use ahvla\basket\Basket;
use ahvla\entity\PvsClient;
use ahvla\entity\submission\Submission;
use ahvla\form\FullSubmissionForm;
use ahvla\form\submissionSteps\AnimalDetailsForm;
use ahvla\form\submissionSteps\ClientDetailsForm;
use ahvla\form\submissionSteps\ClinicalHistoryForm;
use ahvla\form\submissionSteps\DeliveryForm;
use ahvla\form\submissionSteps\ReviewConfirmForm;
use ahvla\form\submissionSteps\TestsForm;
use ahvla\form\submissionSteps\YourBasketForm;
use ahvla\form\submissionSteps\PrintForm;
use ahvla\limsapi\LimsApiFactory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use ahvla\entity\Address;

class MultipleSubmissionManager
{
    const CLASS_NAME = __CLASS__;

    /** @var  LimsApiFactory */
    private $apiFactory;

    /** @var  AuthenticationManager */
    private $authenticationManager;
    /**
     * @var Request
     */
    private $request;

    public $limsSubmission;

    function __construct(LimsApiFactory $apiFactory, AuthenticationManager $authenticationManager, Request $request)
    {
        $this->apiFactory = $apiFactory;
        $this->authenticationManager = $authenticationManager;
        $this->request = $request;
    }

    /**
     * @param string $submissionType
     * @throws Exception
     */
    public function startNewSubmission($submissionType)
    {
        $fullSubmissionForm = new FullSubmissionForm(
            new ClientDetailsForm(),
            new AnimalDetailsForm(),
            new ClinicalHistoryForm(),
            new TestsForm(),
            new DeliveryForm(),
            new YourBasketForm(),
            new ReviewConfirmForm(),
            new PrintForm(),
            new Basket(),
            $this->authenticationManager->getLoggedInUser(),
            null,
            $submissionType
        );

        $draftSubmissionId = $this->callLimsCreateUpdateService($fullSubmissionForm->toSubmissionObject());

        $fullSubmissionForm->setDraftSubmissionId($draftSubmissionId);

        $fullSubmissionForm->setSubmissionComplete(false);

        $this->saveSubmission($fullSubmissionForm);
        return $draftSubmissionId;
    }

    public function getFullCounty($countyLimsCode)
    {
        $countyDB = \DB::table('counties')
            ->where('counties_lims_code', $countyLimsCode)
            ->first();

        if (!empty($countyDB)) {
            return $countyDB->counties_name;
        }
        else {
            return '';
        }

    }

    /**
     * @param string $submissionType
     * @throws Exception
     */
    public function startNewPairedSubmission($submissionType, $linkedFirstOfPairSubmissionId)
    {
        // create the SOP "shell" (only to generate draft id)
        $fullSubmissionForm = new FullSubmissionForm(
            new ClientDetailsForm(),
            new AnimalDetailsForm(),
            new ClinicalHistoryForm(),
            new TestsForm(),
            new DeliveryForm(),
            new YourBasketForm(),
            new ReviewConfirmForm(),
            new PrintForm(),
            new Basket(),
            $this->authenticationManager->getLoggedInUser(),
            null,
            $submissionType,
            null,
            $linkedFirstOfPairSubmissionId
        );

        // Create the SOP in LIMS
        $draftSubmissionId = $this->callLimsCreateUpdateService($fullSubmissionForm->toSubmissionObject());

        // Get the SOP
        $linkedFirstOfPairSubmission = $this->getSubmissionFromLims($draftSubmissionId);

        $fullSubmissionForm = FullSubmissionForm::newFromSubmissionObject(
            $linkedFirstOfPairSubmission,
            $this->authenticationManager->getLoggedInUser()
        );

        $fullSubmissionForm->basket->setSOPProductSampleTypes(); // for test sample type display only in basket
        $fullSubmissionForm->basket->setSOPProductSamples(); // create new SOP animal sample refs
        $fullSubmissionForm->basket->removeFOPProductSamples(); // remove FOP animal sample refs
        $fullSubmissionForm->basket->setPackageConstituentTestsToSOP(); // set any package constituent tests isSOP properties to true for blades
        $fullSubmissionForm->basket->setAllProductSamplePoolGroupsToDisabled();
        $fullSubmissionForm->clinicalHistoryForm->sample_date_year = '';

        $pvsClient = new PvsClient(
            null,
            $fullSubmissionForm->clientDetailsForm->edited_client_name,
            new Address(
                $fullSubmissionForm->clientDetailsForm->clientFarm,
                $fullSubmissionForm->clientDetailsForm->edited_client_address_line1,
                $fullSubmissionForm->clientDetailsForm->edited_client_address_line2,
                $fullSubmissionForm->clientDetailsForm->edited_client_address_line3,
                $fullSubmissionForm->clientDetailsForm->client_sub_county,
                $fullSubmissionForm->clientDetailsForm->client_county,
                $fullSubmissionForm->clientDetailsForm->client_postcode
            ),
            $fullSubmissionForm->clientDetailsForm->client_postcode,
            $fullSubmissionForm->clientDetailsForm->client_county,
            $fullSubmissionForm->clientDetailsForm->client_sub_county,
            $fullSubmissionForm->clientDetailsForm->edited_client_location,
            $fullSubmissionForm->clientDetailsForm->edited_client_cphh
        );
        $fullSubmissionForm->clientDetailsForm->setChosenClient($pvsClient);

        // FOP address for SOP (required for "No address change" selection in Basket)
        if ($fullSubmissionForm->clientDetailsForm->animals_at_address) { // if animals at client address
            $fullSubmissionForm->clientDetailsForm->fop_animal_farm = $fullSubmissionForm->clientDetailsForm->clientFarm;
            $fullSubmissionForm->clientDetailsForm->fop_animal_address1 = $fullSubmissionForm->clientDetailsForm->edited_client_address_line2;
            $fullSubmissionForm->clientDetailsForm->fop_animal_address2 = $fullSubmissionForm->clientDetailsForm->edited_client_address_line3;
            $fullSubmissionForm->clientDetailsForm->fop_animal_address3 = $fullSubmissionForm->clientDetailsForm->edited_client_address_line4;
            $fullSubmissionForm->clientDetailsForm->fop_animal_postcode = $fullSubmissionForm->clientDetailsForm->client_postcode;
            $fullSubmissionForm->clientDetailsForm->fop_animal_county = $this->getFullCounty($fullSubmissionForm->clientDetailsForm->client_county);
            $fullSubmissionForm->clientDetailsForm->fop_animal_sub_county = $fullSubmissionForm->clientDetailsForm->client_sub_county;
            $fullSubmissionForm->clientDetailsForm->fop_animal_cphh = $fullSubmissionForm->clientDetailsForm->edited_client_cphh;
        } else {
            $fullSubmissionForm->clientDetailsForm->fop_animal_farm = $fullSubmissionForm->clientDetailsForm->animal_farm;
            $fullSubmissionForm->clientDetailsForm->fop_animal_postcode = $fullSubmissionForm->clientDetailsForm->animal_postcode;
            $fullSubmissionForm->clientDetailsForm->fop_animal_county = $this->getFullCounty($fullSubmissionForm->clientDetailsForm->animal_county);
            $fullSubmissionForm->clientDetailsForm->fop_animal_sub_county = $fullSubmissionForm->clientDetailsForm->animal_sub_county;
            $fullSubmissionForm->clientDetailsForm->fop_animal_cphh = $fullSubmissionForm->clientDetailsForm->animal_cphh;
            $fullSubmissionForm->clientDetailsForm->setFOPAnimalAddressesLines($fullSubmissionForm->clientDetailsForm->animal_address); // for validation
        }
        $this->saveSubmission($fullSubmissionForm);
        return $draftSubmissionId;
    }

    /**
     * @param FullSubmissionForm $fullSubmissionForm
     */
    public function saveSubmission(FullSubmissionForm $fullSubmissionForm)
    {
        $this->saveSubmissionInSession($fullSubmissionForm);
        $this->saveSubmissionToDb($fullSubmissionForm);
    }

    public function saveSubmissionToLimsOnly(FullSubmissionForm $fullSubmissionForm)
    {
        $this->callLimsCreateUpdateService($fullSubmissionForm->toSubmissionObject());
    }

    public function confirmDraftSubmissionInLims($draftSubmissionId)
    {
        $fullSubmissionForm = $this->getSubmissionFormFromSession($draftSubmissionId);
        $submission = $fullSubmissionForm->toSubmissionObject();

        // final submit must pass default animal IDs if not set
        $fullSubmissionForm->setDefaultAnimalIds();

        $this->callLimsCreateUpdateService($submission);

        $submissionId = $this->apiFactory
            ->newConfirmDraftSubmissionService()->execute(
                [
                    'draftSubmissionId' => $fullSubmissionForm->draftSubmissionId,
                    'pvsId' => $this->authenticationManager->getLoggedInUser()->getPracticeLimsCode()
                ]
            );

        $this->saveSubmission(
            $fullSubmissionForm->setSubmissionId($submissionId)
        );

        return $submissionId;
    }

    /**
     * @param string $draftSubmissionId
     * @return FullSubmissionForm
     */
    private function getSubmissionFormFromSession($draftSubmissionId)
    {
        $allSubmissions = $this->getAllSubmissionsFromSession();

        foreach ($allSubmissions as $id => $submission) {
            if ($id == $draftSubmissionId) {
                return $submission;
            }
        }

        // lets try getting it from lims, since the caller might be from the print dispatch note e.g.
        try {

            $this->limsSubmission = $this->getSubmissionFromLims($draftSubmissionId);

            $fullSubmissionForm = FullSubmissionForm::newFromSubmissionObject(
                $this->limsSubmission,
                $this->authenticationManager->getLoggedInUser()
            );

            return $fullSubmissionForm;

        } catch (Exception $e) {}

        return null;
    }

    public function getLimsSubmission()
    {
        return $this->limsSubmission;
    }

    /**
     * @return FullSubmissionForm[]
     */
    private function getAllSubmissionsFromSession()
    {
        $allSubmissionsSerialized = Session::get('FullSubmissionForms', null);
        if ($allSubmissionsSerialized) {
            $allSubmissions = unserialize($allSubmissionsSerialized);
            return $allSubmissions;
        }
        return [];
    }

    public function callLimsCreateUpdateService($submission)
    {
        $createUpdateService = $this->apiFactory->newCreateUpdateDraftSubmissionService();
        $limsDraftSubmissionId = $createUpdateService->execute(
            ['submission' => $submission]
        );

        if (!$limsDraftSubmissionId) {
            throw new Exception('Create Update Draft submission did not throw exception but no draft submission id returned');
        }

        return $limsDraftSubmissionId;
    }

    /**
     * @param FullSubmissionForm $fullSubmissionForm
     */
    private function saveSubmissionInSession(FullSubmissionForm $fullSubmissionForm)
    {
        $existingSubmissions = $this->getAllSubmissionsFromSession();
        $existingSubmissions[$fullSubmissionForm->draftSubmissionId] = $fullSubmissionForm;
        Session::set('FullSubmissionForms', serialize($existingSubmissions));
    }

    /**
     * @param $draftSubmissionId
     * @return FullSubmissionForm
     */
    public function getSubmission($draftSubmissionId)
    {
        return $this->getSubmissionFormFromSession($draftSubmissionId);
    }

    public function startExistingSubmission($draftSubmissionId)
    {
        $limsSubmission = $this->getSubmissionFromLims($draftSubmissionId);
        $localDbSubmission = Submission::getByDraftSubmissionId($draftSubmissionId);

        if(!$limsSubmission && !$localDbSubmission){
            throw new Exception("Could not find draft submission $draftSubmissionId in LIMS or Locally");
        }elseif ($limsSubmission && !$localDbSubmission) {
            $submission = $limsSubmission;
        } elseif (!$limsSubmission && $localDbSubmission) {
            $submission = $localDbSubmission;
        } elseif ($limsSubmission->getLastUpdated() > $localDbSubmission->getLastUpdated()) {
            $submission = $limsSubmission;
        } else{
            $submission = $localDbSubmission;
        }

        $fullSubmissionForm = FullSubmissionForm::newFromSubmissionObject(
            $submission,
            $this->authenticationManager->getLoggedInUser()
        );

        $this->saveSubmission($fullSubmissionForm);
        return $fullSubmissionForm->draftSubmissionId;
    }

    /**
     * @return FullSubmissionForm
     */
    public function getCurrentRequestSubmission()
    {
        return $this->getSubmission($this->request->get('draftSubmissionId', null));
    }

    public function deleteSubmissionFromDb(FullSubmissionForm $fullSubmissionForm)
    {
        $submission = $fullSubmissionForm->toSubmissionObject();
        $submission->delete();
    }

    /**
     * @param FullSubmissionForm $fullSubmissionForm
     */
    private function saveSubmissionToDb(FullSubmissionForm $fullSubmissionForm)
    {
        $submission = $fullSubmissionForm->toSubmissionObject();
        $submission->save();
    }

    /**
     * @param $draftSubmissionId
     * @return \ahvla\entity\submission\Submission
     * @throws Exception
     */
    private function getSubmissionFromLims($draftSubmissionId)
    {
        $getSubmissionService = $this->apiFactory->newGetSubmissionLimsService();
        $submission = $getSubmissionService->execute(
            [
                'submissionId' => $draftSubmissionId,
                'pvsId' => $this->authenticationManager->getLoggedInUser()->getPracticeLimsCode()
            ]
        );

        if (!$submission || !$submission instanceof Submission) {
            throw new Exception("Failed to load submission from Lims (id: $draftSubmissionId)");
        }
        return $submission;
    }

}