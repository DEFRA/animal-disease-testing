<?php

namespace ahvla\controllers\api;

use ahvla\basket\BasketManager;
use ahvla\limsapi\LimsApiFactory;
use ahvla\MultipleSubmissionManager;
use ahvla\product\ProductSearch;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Response;
//use ahvla\SubmissionUrl;
use Redirect;

class BasketProductController extends ApiBaseController
{
    /**
     * @var BasketManager
     */
    private $basketManager;
    /**
     * @var ProductSearch
     */
    private $productSearch;

    private $multiSubmissionManager;

    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->basketManager = $this->getBasketManager();

        $this->productSearch = new ProductSearch(
            $app->make(LimsApiFactory::CLASS_NAME),
            $this->getSetFullSubmissionForm(),
            $app->make(MultipleSubmissionManager::CLASS_NAME)
        );

        $this->multiSubmissionManager = $app->make(MultipleSubmissionManager::CLASS_NAME);

        //$this->subUrl = $app->make(SubmissionUrl::CLASS_NAME);
    }

    public function getBasketProduct($productId)
    {
        $basketProduct = $this->basketManager->getBasket()->getProductById($productId);

        if ($basketProduct) {
            return ['result' => 1, 'product' => $basketProduct ? $basketProduct : ''];
        }

        return ['result' => 0, 'product' => null];
    }
    
    public function postAction($productId)
    {
        $product = $this->productSearch->getSearchedResultProduct($productId);

        $newBasketProduct = $this->basketManager->addProduct(
            $this->getSetFullSubmissionForm()->animalDetailsForm
                ->wrapProductWithAnimalIds($product)
        );

        if (!$newBasketProduct) {
            return [
                'result' => 0,
                'product' => null
            ];
        }

        return [
            'result' => 1,
            'product' => $newBasketProduct ? $newBasketProduct : ''
        ];
    }

    public function removeProductFromBasket($productId)
    {
        $this->basketManager->removeProduct($productId);

        // Refresh the sample ids in YourBasketForm
        $fullSubmissionForm = $this->getSetFullSubmissionForm();
        $fullSubmissionForm->yourBasketForm->refreshSampleIdsFromBasket($this->basketManager->getBasket());

        $this->saveFullSubmissionForm($fullSubmissionForm);

        //Save to lims
        $this->multiSubmissionManager->saveSubmissionToLimsOnly(
            $this->getSetFullSubmissionForm()
        );
    }

    public function deleteActionNoJS($productId, $step)
    {
        $this->removeProductFromBasket($productId);

        return Redirect::to($this->subUrl->build($step, $this->draftSubmissionId));
    }



    public function deleteAction($productId)
    {

        $this->removeProductFromBasket($productId);

        if ($this->basketManager->getBasket()
            ->getProductById($productId)
        ) {
            return ['result' => 0];
        }

        return ['result' => 1];
    }

    public function deleteAnimalAction($productId, $animalId)
    {
        $this->basketManager->unsetProductAnimalId($productId, $animalId);

        return ['result' => 1];

    }

    public function deleteAnimalActionNoJS($productId, $animalId)
    {
        $this->basketManager->unsetProductAnimalId($productId, $animalId);

        return Redirect::to($this->subUrl->build('step5', $this->draftSubmissionId));

    }

    public function packageSampleAction($packageId, $productId, $sampleId)
    {
        $basket = $this->basketManager->getBasket();
        $package = $basket->getProductById($packageId);
        $product = $basket->getProductFromPackage($packageId, $productId);

        // Override isPairable flag if test part of a paired package.
        // Reason is some pairable packages contain test level sample types that are non-paired.
        // Business rules for packages state packages are pairable/non-pairable at package level
        // not test level and thus isPairable flag needs to be overwritten in some cases.
        $packagePairable = false;
        if ($package->isPackagePairable()) {
            $packagePairable = true;
        }

        if ($product) {
            $sampleTypes = $product->rawSampleTypesArray;
            foreach ($sampleTypes as $sampleType) {
                if($sampleType->sampleId == $sampleId)
                {
                    if ($packagePairable) {
                        $sampleType->isPairable = true; // Override isPairable
                    }
                    return ['sample' => $sampleType];
                }
            }
        }

        return ['sample' => null];
    }

    public function sampleAction($productId, $sampleId)
    {
        if ($this->basketManager->getBasket()->inBasketById($productId)) {
            $sampleTypes = $this->basketManager->getBasket()->inBasketById($productId)->rawSampleTypesArray;
            foreach ($sampleTypes as $sampleType) {
                if($sampleType->sampleId == $sampleId)
                {
                    return ['sample' => $sampleType];
                }
            }
        }
        return ['sample' => null];
    }
}