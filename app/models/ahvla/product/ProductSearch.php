<?php

namespace ahvla\product;

use ahvla\entity\product\Product;
use ahvla\form\FullSubmissionForm;
use ahvla\limsapi\LimsApiFactory;
use ahvla\limsapi\service\GetProductsLimsService;
use ahvla\MultipleSubmissionManager;
use Illuminate\Session\Store;
use Session;
use ahvla\limsapi\LimsPagination;

class ProductSearch
{
    /*
     * Number of records per page
     */
    const PER_PAGE = 5;

    /**
     * @var Store
     */
    private $session;

    /**
     * @var LimsApiFactory
     */
    private $limsApiFactory;

    /**
     * Pagination of records
     */
    public $limsPaginator;

    /**
     * @var FullSubmissionForm
     */
    private $fullSubmissionForm;
    /**
     * @var MultipleSubmissionManager
     */
    private $multipleSubmissionManager;

    /**
     * @param LimsApiFactory $limsApiFactory
     * @param FullSubmissionForm $fullSubmissionForm
     * @param MultipleSubmissionManager $multipleSubmissionManager
     */
    public function __construct(
        LimsApiFactory $limsApiFactory,
        FullSubmissionForm $fullSubmissionForm,
        MultipleSubmissionManager $multipleSubmissionManager
    )
    {
        $this->limsApiFactory = $limsApiFactory;
        $this->fullSubmissionForm = $fullSubmissionForm;
        $this->multipleSubmissionManager = $multipleSubmissionManager;
    }

    /**
     * @param $params   array of filter,species || list of product_ids
     * @param $page
     * @return Product[]
     */
    public function searchProductsAndSaveResults($params, $page=null)
    {
        //if (strlen($filter) < 2) {
        if (!array_key_exists('tests', $params) && strlen($params['filter']) < 2) {
            $this->fullSubmissionForm->latestTestSearchResults = [];
            $this->multipleSubmissionManager->saveSubmission($this->fullSubmissionForm);
            return [];
        }

        /** @var GetProductsLimsService $getProductsService */
        $getProductsService = $this->limsApiFactory->newGetProductsService();
        $products = $getProductsService->execute($params);


        //print 'searchProductsAndSaveResults:<pre>';
        //print_r($products);
        //dd();


        if (!is_null($page)) {
            $this->limsPaginator = new LimsPagination([], self::PER_PAGE, $page);

            $this->limsPaginator->setItems($products);
            $this->limsPaginator->paginate();

            // save so if user refreshes, the correct list of results are shown
            $this->fullSubmissionForm->latestTestSearchResults = $this->limsPaginator->currentItems;
            $this->multipleSubmissionManager->saveSubmission($this->fullSubmissionForm);
            return $this->limsPaginator->currentItems;
        }
        else {
            // No pagination
            $this->fullSubmissionForm->latestTestSearchResults = $products;
            $this->multipleSubmissionManager->saveSubmission($this->fullSubmissionForm);
            return $products;
        }


    }

    /**
     * @param string $id
     * @return Product|null
     */
    public function getSearchedResultProduct($id)
    {
        // check the latest search results for the product
        foreach ($this->fullSubmissionForm->latestTestSearchResults as $product) {
            if ($product->id == $id) {
                return $product;
            }
        }

        // otherwise recall the product by id
        /** @var GetProductsLimsService $getProductsService */
        $getProductsService = $this->limsApiFactory->newGetProductsService();
        $products = $getProductsService->execute(['filter' => $id]);
        foreach ($products as $product) {
            if ($product->id == $id) {
                return $product;
            }
        }

        return null;
    }
}