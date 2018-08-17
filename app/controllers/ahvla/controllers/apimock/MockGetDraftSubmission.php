<?php

namespace ahvla\controllers\apimock;


use Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Input;

class MockGetDraftSubmission extends Controller
{
    /**
     * @var Request
     */
    private $request;

    function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Two params are required in the json passed in the request according to the json api spec
     *  submissionId    string  The draft submission id
     *  pvsId                string  The id of the PVS
     */
    public function getSubmission()
    {
        $input = Input::all();
        $submissionId = isset($input['submissionId']) ? $input['submissionId'] : '';
        $pvsId = isset($input['pvsId']) ? $input['pvsId'] : '-1';

        if ($submissionId && $pvsId) {

            // Return a 200 with dummy json content
            $data = $this->getData();
            $status = 200;

            return (new Response($data, $status))->header('Content-Type', 'application/json');

        } else {

            // Return a 400 error
            $status = 400;
            $data = ['description' => 'Missing parameter'];

            return (new Response($data, $status))->header('Content-Type', 'application/json');
        }
    }

    private function getData()
    {
        return [
            'submissionId' => '123',
            'submissionType' => 'sick',
            'pvsId' => '234',
            'status' => 'draft',
            'isDigital' => true,
            'clientCPHH' => '48-100-9997',
            'clientName' => 'SMITH, R',
            'clientFarm' => 'FRIDAY FARM',
            'clientAddress' => 'KNOWSLEY LANE, WIGGINGTON, YORK, YO3 3DF',
            'clinician' => 'john smith',
            'resultsReadyConfirmationEmail' => 'test@test.com',
            'resultsReadyConfirmationPhoneNumber' => '07777 777777',
            'areAnimalsAtFarmAddress' => true,
            'animalCPHH' => '',
            'animalFarm' => '',
            'animalAddress' => '',
            'animalSpecies' => 'Cattle',
            'animalSpeciesId' => 'CATTLE',
            'animalBreed' => 'Highland',
            'animalBreedId' => 'HIGHLAND',
            'animals' => [
                ['id' => '1', 'name' => 'cow1'],
                ['id' => '2', 'name' => 'cow2']
            ],
            'animalSex' => 'male',
            'animalSexId' => 'MALE',
            'animalAge' => 'adult',
            'animalAgeId' => 'ADULT',
            'animalOrganic' => 'transition',
            'animalOrganicId' => 'TRANSITION',
            'animalPurpose' => 'suckler',
            'animalPurposeId' => 'SUCKLER',
            'animalHousing' => 'outdoor',
            'animalHousingId' => 'OUTDOOR',
            'dateSamplesTaken' => '2015-02-01T00:00:00',
            'previousSubmissionId' => '',
            'previousSubmissionContactByPhone' => false,
            'previousSubmissionContactByAphaFarmVisit' => false,
            //'previousSubmissionId'=>'VI-15-000007',
            //'previousSubmissionContactByPhone'=>true,
            //'previousSubmissionContactByAphaFarmVisit'=>false,
            'herdTotal' => '100',
            'herdBreedingTotal' => '100',
            'herdAffectedTotal' => '2',
            'herdAffectedIncDead' => '2',
            'herdDeadTotal' => '0',
            'clinicalSign1' => 'Malaise',
            'clinicalSign1Id' => 'MALAISE',
            'clinicalSign2' => 'Found dead',
            'clinicalSign2Id' => 'FNDDEAD',
            'clinicalSign3' => '',
            'clinicalSign3Id' => '',
            'clinicalSignDuration' => '0 to 3 days',
            'clinicalSignDurationId' => '0_3',
            'clinicalHistory' => 'Some history data',
            'products' => [
                ['code' => '1', 'name' => 'Culture, stained smears and wet preparation (for routine culture)',
                    'price' => 20, 'averageTurnaround' => 2, 'maximumTurnaround' => 4, 'species' => ['Cattle', 'Pig'],
                    'dueDate' => '15/03/2015', 'sampleType' => 'FETAL_BRAIN',
                    'sampleTypes' =>
                        [
                            ['sampleId' => 'BLOOD', 'sampleName' => 'blood', 'testSampleName' => 'Blood'],
                            ['sampleId' => 'FETAL_BRAIN', 'sampleName' => 'fetal brain', 'testSampleName' => 'Fetal brain']
                        ],
                    'productType' => 'Culture', 'options' => [
                    'option1' => 'option 1',
                    'option2' => 'option 2'], 'optionsType' => 'analytes', 'minOptions' => 0,
                    'maxOptions' => 0,
                    'animalSamples' => [
                        ['animalId' => 1, 'animalName' => 'cow1', 'sampleId' => 'cow1_samples']
                    ]
                ],
                ['code' => '2', 'name' => 'PCR on fresh brain',
                    'price' => 35.5, 'averageTurnaround' => 3, 'maximumTurnaround' => 10, 'species' => ['Pig'],
                    'dueDate' => '25/03/2015', 'sampleType' => 'BLOOD',
                    'sampleTypes' =>
                        [
                            ['sampleId' => 'BLOOD', 'sampleName' => '', 'testSampleName' => 'Blood'],
                            ['sampleId' => 'FETAL_BRAIN', 'sampleName' => '', 'testSampleName' => 'Fetal brain']
                        ],
                    'productType' => 'Culture', 'options' => [], 'optionsType' => '', 'minOptions' => 0,
                    'maxOptions' => 0,
                    'animalSamples' => [
                        ['animalId' => 1, 'animalName' => 'cow1', 'sampleId' => 'cow1_samples'],
                    ]
                ]
            ],
            'samplesWillSendToSeparateAddresses' => null,
            'canUseSurveillance' => false,
            'vioHasChanged' => false,
            'vioChangeReason' => false
        ];

    }

}