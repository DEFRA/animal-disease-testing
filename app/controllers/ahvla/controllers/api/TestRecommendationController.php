<?php

namespace ahvla\controllers\api;

use ahvla\entity\testRecommendation\TestRecommendationRepository;
use ahvla\entity\product\Product;
use ahvla\limsapi\LimsApiFactory;
use ahvla\form\FullSubmissionForm;
use ahvla\MultipleSubmissionManager;
use ahvla\product\ProductSearch;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Controller;
use Illuminate\Support\Facades\Input as Input;
//use Illuminate\View\Factory;
use Illuminate\Support\Facades\View;

class TestRecommendationController extends ApiBaseController
{
    /**
     * @var Request
     */
    private $input;
    /**
     * @var ProductSearch
     */
    private $productSearch;

    function __construct(
        Request $input,
        Application $app,
        TestRecommendationRepository $testRecommendationRepository
    )
    {
        parent::__construct($app);
        $this->input = $input;
        $this->testRecommendationRepository = $testRecommendationRepository;
        $this->productSearch = new ProductSearch(
            $app->make(LimsApiFactory::CLASS_NAME),
            $this->getSetFullSubmissionForm(),
            $app->make(MultipleSubmissionManager::CLASS_NAME)
        );
    }

    public function getAction()
    {
        // Clear test recommendations
        $this->fullSubmissionForm->latestTestSearchResults = [];
        $this->saveFullSubmissionForm($this->fullSubmissionForm);

        // For no js functionality and general case if no page is defined,
        // we start again from page 1 as user may have changed the filter parameters.
        $testsForm = $this->fullSubmissionForm->testsForm;

        $testsForm->setAttribute('current_page', 1);
        $page = Input::get('page');
        if (is_numeric($page)) {
            $testsForm->setAttribute('current_page', $page);
        }

        $species = $this->input->get('species', '');
        $sampleType = $this->input->get('sample_type', '');
        $disease = $this->input->get('disease', '');

        // Get a grouped list of test recommendation data and a list of unique product (test) ids in one hit
        $list = $this->testRecommendationRepository->getGroupedAndListed(
            $species,
            $sampleType,
            $disease
        );
        $groupedList = $list['groupedList'];
        $productIdsList = $list['testIdsList'];

        if (count($productIdsList)) {
            // Make fresh LIMS call to get the product info and key it by product ID
            $productsIdsCsv = implode(',', $productIdsList);

            $products = $this->productSearch->searchProductsAndSaveResults(
                [
                    'filter' => '',
                    'tests' => $productsIdsCsv
                ]
            );

            // Reindex array on productId
            $productsList = [];
            foreach ($products as $product) {
                $productsList[$product->id] = $product;
            }

            $adviceSearchResults = [
                'products' => $productsList,
                'recommendations' => $groupedList
            ];

            $viewData = [
                'adviceSearchResults' => isset($adviceSearchResults) ? $adviceSearchResults : []
            ];

            return View::make('submission.steps.partials.tests.test-recommendations-template', $viewData);
        }
        else {
            return '';
        }
    }
}