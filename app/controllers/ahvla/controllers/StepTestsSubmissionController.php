<?php

namespace ahvla\controllers;

use ahvla\basket\BasketManager;
use ahvla\entity\species\SpeciesRepository;
use ahvla\form\submissionSteps\TestsForm;
use ahvla\limsapi\LimsApiFactory;
use ahvla\MultipleSubmissionManager;
use ahvla\product\ProductSearch;
use Illuminate\Foundation\Application as App;
use Redirect;
use Illuminate\Support\Facades\Input;
use ahvla\entity\submission\SubmissionRepository;
use ahvla\entity\testRecommendation\TestRecommendationRepository;
use ahvla\laravel\helper\UtilityHelper;

/*
 * Step: Tests of submission
 */

class StepTestsSubmissionController extends StepBaseController
{
    protected $submission;
    /**
     * @var SpeciesRepository
     */
    private $speciesRepository;
    /**
     * @var BasketManager
     */
    private $basketManager;
    /**
     * @var ProductSearch
     */
    private $productSearch;
    /**
     * @var TestRecommendationRepository
     */
    private $testRecommendationRepository;

    public function __construct(App $app,
                                SubmissionRepository $submission,
                                SpeciesRepository $speciesRepository,
                                TestRecommendationRepository $testRecommendationRepository)
    {
        parent::__construct($app, TestsForm::CLASS_NAME);
        $this->beforeFilter('submission_form_complete:'.$this->fullSubmissionForm->submissionComplete);
        $this->submission = $submission;
        $this->speciesRepository = $speciesRepository;
        $this->testRecommendationRepository = $testRecommendationRepository;
        $this->basketManager = $this->getBasketManager();
        $this->productSearch = new ProductSearch(
            $app->make(LimsApiFactory::CLASS_NAME),
            $this->fullSubmissionForm,
            $app->make(MultipleSubmissionManager::CLASS_NAME)
        );
    }

    /*
     * Step: Tests of submission
     */
    public function indexAction()
    {
        $input = Input::all();

        /** @var TestsForm $testsForm */
        $testsForm = $this->controllerStepForm;

        // for no js functionality and general case if no page is defined,
        // we start again from page 1 as user may have changed the filter parameters.
        $testsForm->setAttribute('current_page', 1);
        $page = Input::get('page');
        if (is_numeric($page)) {
            $testsForm->setAttribute('current_page', $page);
        }

        $animalDetailsForm = $this->fullSubmissionForm->animalDetailsForm;

        // Species set in animal details form?
        $species = $animalDetailsForm->getSpecies();

        // Species in recommended tests adviser
        $speciesRecommendedSelection = ($testsForm->species_recommended_selection)
            ? $testsForm->species_recommended_selection
            : $species;

        // Species in tests finder
        $speciesSelection = ($testsForm->species_selection) ? $testsForm->species_selection : $species;

        $sampleType = ($testsForm->sample_type) ? $testsForm->sample_type: '';
        $disease = ($testsForm->disease) ? $testsForm->disease : '';

        // Set a flag to indicate whether the user has already performed a search and which type
        $searchType = null;

        // Rerun previous search for finder/adviser?
        if ($testsForm->test_search_input && $speciesSelection) {
            $searchType = 'finder';
            $searchResults = $this->productSearch->searchProductsAndSaveResults(
                [
                    'filter' => $testsForm->test_search_input,
                    'species' => $speciesSelection
                ],
                $testsForm->current_page
            );

            $totalItems = $this->productSearch->limsPaginator->totalItemsCount;
            $previousPage = $this->productSearch->limsPaginator->previousPage();
            $nextPage = $this->productSearch->limsPaginator->nextPage();
            $totalPages = $this->productSearch->limsPaginator->totalPages();
            $currentPage = $this->productSearch->limsPaginator->page;
        }
        elseif ($speciesRecommendedSelection && $disease) {
            $searchType = 'adviser';

            // Get a grouped list of test recommendation data and a list of unique product (test) ids in one hit
            $list = $this->testRecommendationRepository->getGroupedAndListed(
                $speciesRecommendedSelection,
                $sampleType,
                $disease
            );

            $groupedList = $list['groupedList'];
            $productIdsList = $list['testIdsList'];

            $productsList = [];
            $totalItems = 0;

            if (count($productIdsList)) {
                // Get the count of items
                $totalItems = $this->testRecommendationRepository->getNumTestsFromGroupedResults($groupedList);

                // Make fresh LIMS call to get the product info and key it by product ID
                $productsIdsCsv = implode(',', $productIdsList);

                $products = $this->productSearch->searchProductsAndSaveResults(
                    [
                        'filter' => '',
                        'tests' => $productsIdsCsv
                    ]
                );

                // Reindex array on productId
                foreach ($products as $product) {
                    $productsList[$product->id] = $product;
                }
            }

            $adviceSearchResults = [
                'products' => $productsList,
                'recommendations' => $groupedList
            ];
        }


        // Get species for which we have tests
        $testRecommendedSpeciesList = UtilityHelper::formatDropdownData(
            $this->testRecommendationRepository->getAllSpecies(),
            [''=>'--Choose--']
        );

        // Get sample types (optionally for a species) for which we have tests
        $testRecommendedSampleTypesList = UtilityHelper::formatDropdownData(
            $this->testRecommendationRepository->getAllSampleTypes($speciesRecommendedSelection, $disease),
            [''=>'All']
        );

        $viewData = [
            'persistence' => $testsForm,
            'selectedSpecies' => $speciesSelection,
            'selectedRecommendedSpecies' => $speciesRecommendedSelection,
            'selectedSampleType' => $sampleType,
            'tests' => [],
            'test_recommended_species_list' => $testRecommendedSpeciesList,
            'test_recommended_sample_types_list' => $testRecommendedSampleTypesList,
            'basketProducts' => $this->basketManager->getProducts(),
            'searchType' => $searchType,
            'adviceSearchResults' => isset($adviceSearchResults) ? $adviceSearchResults : [],
            'searchResults' => isset($searchResults) ? $searchResults : [],
            'totalItems' => isset($totalItems) ? $totalItems : -1,
            'previousPage' => isset($previousPage) ? $previousPage : 0,
            'nextPage' => isset($nextPage) ? $nextPage : 0,
            'totalPages' => isset($totalPages) ? $totalPages : 0,
            'currentPage' => isset($currentPage) ? $currentPage : 0,
            'basket' => $this->basketManager->getBasket(),
            'diseaseOrClinicalSignsList' => ($speciesRecommendedSelection)
                ? $this->testRecommendationRepository->getDiseaseBySpecies($speciesRecommendedSelection, '')
                : []
        ];
        return $this->makeView('submission.steps.step-tests', $viewData);
    }

    /*
     * Step: Tests page post
     */
    public function postAction()
    {
        $input = Input::all();

        $this->fullSubmissionForm->testsForm->setFormAttributes($input);

        $this->fullSubmissionForm->testsForm->dataCleanse();

        if ($globalPostAction = parent::globalPostAction($input)) {
            return $globalPostAction;
        }

        /** @var TestsForm $testsForm */
        $testsForm = $this->fullSubmissionForm->testsForm;

        if ($testsForm->isTestSearchSubmission($input)) {
            $this->productSearch->searchProductsAndSaveResults(
                [
                    'filter' => $testsForm->test_search_input,
                    'species' => $testsForm->species_selection
                ],
                $testsForm->current_page
            );
            return Redirect::to($this->subUrl->build('step4'));
        }

        $productAddedToBasket = $testsForm->getProductAddedToBasket($input);
        if ($productAddedToBasket) {
            $product = $this->productSearch
                ->getSearchedResultProduct($productAddedToBasket);

            $this->basketManager->addProduct(
                $this->fullSubmissionForm->animalDetailsForm
                    ->wrapProductWithAnimalIds($product)
            );
            return Redirect::to($this->subUrl->build('step4'));
        }

        if ($validationFailure = parent::validateStep('step4')) {
            return $validationFailure;
        }

        //Save to lims
        $this->multiSubmissionManager->saveSubmissionToLimsOnly(
            $this->fullSubmissionForm
        );

        return Redirect::to($this->subUrl->build('step5'));
    }

    /*
     * Just shows the RHS basket
     */
    public function smallBasketAction()
    {
        $viewData = [
            'basketProducts' => $this->basketManager->getProducts(),
            'basket' => $this->basketManager->getBasket()
        ];
        return $this->makeView('submission.steps.partials.tests.test-basket', $viewData);
    }
}
