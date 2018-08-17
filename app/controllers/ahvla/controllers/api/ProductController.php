<?php

namespace ahvla\controllers\api;

use ahvla\entity\product\Product;
use ahvla\limsapi\LimsApiFactory;
use ahvla\MultipleSubmissionManager;
use ahvla\product\ProductSearch;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Controller;

class ProductController extends ApiBaseController
{
    /**
     * @var Request
     */
    private $input;
    /**
     * @var ProductSearch
     */
    private $productSearch;


    function __construct(Request $input, Application $app)
    {
        parent::__construct($app);
        $this->input = $input;

        $this->productSearch = new ProductSearch(
            $app->make(LimsApiFactory::CLASS_NAME),
            $this->getSetFullSubmissionForm(),
            $app->make(MultipleSubmissionManager::CLASS_NAME)
        );
    }

    public function getAction()
    {
        $filter = substr( $this->input->get('filter'), 0, 1000 );
        $species = $this->input->get('species', '');
        $page = $this->input->get('page', '1');

        //$products = $this->productSearch
        //    ->searchProductsAndSaveResults($filter, $species, $page);

        $products = $this->productSearch->searchProductsAndSaveResults(
            [
                'filter' => $filter,
                'species' => $species
            ],
            $page
        );

        foreach ($products as $product) {
            $product->addProductToBasketId = $product->id;
        }

        // add meta data for pagination
        $products['totalItems'] = $this->productSearch->limsPaginator->totalItemsCount;
        $products['previousPage'] = $this->productSearch->limsPaginator->previousPage();
        $products['nextPage'] = $this->productSearch->limsPaginator->nextPage();
        $products['totalPages'] = $this->productSearch->limsPaginator->totalPages();
        $products['currentPage'] = $this->productSearch->limsPaginator->page;

        return $products;
    }


}