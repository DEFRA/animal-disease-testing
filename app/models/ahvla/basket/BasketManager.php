<?php

namespace ahvla\basket;

use ahvla\entity\product\BasketProduct;
use ahvla\form\FullSubmissionForm;
use ahvla\MultipleSubmissionManager;
use Illuminate\Foundation\Application as App;

class BasketManager
{
    const CLASS_NAME = __CLASS__;

    /**
     * @var FullSubmissionForm
     */
    private $fullSubmissionForm;

    /** @var  MultipleSubmissionManager */
    private $submissionSessionHandler;

    public function __construct(App $app, FullSubmissionForm $fullSubmissionForm)
    {
        $this->fullSubmissionForm = $fullSubmissionForm;
        $this->submissionSessionHandler = $app->make(MultipleSubmissionManager::CLASS_NAME);
    }

    /**
     * @return BasketProduct[]
     */
    public function getProducts()
    {
        return $this->fullSubmissionForm->basket->getProducts();
    }

    /**
     * @param BasketProduct $product
     * @return BasketProduct|null
     */
    public function addProduct($product)
    {
        $this->fullSubmissionForm->basket->addProduct($product);
        $this->submissionSessionHandler->saveSubmission($this->fullSubmissionForm);
        return $product;
    }

    /**
     * @param string $productId
     */
    public function removeProduct($productId)
    {
        $this->fullSubmissionForm->basket->removeProduct($productId);
        $this->submissionSessionHandler->saveSubmission($this->fullSubmissionForm);
    }

    /**
     * @param Basket $basket
     */
    public function saveBasket(Basket $basket)
    {
        $this->fullSubmissionForm->basket = $basket;
        $this->submissionSessionHandler->saveSubmission($this->fullSubmissionForm);
    }

    /**
     * @return Basket
     */
    public function getBasket()
    {
        return $this->fullSubmissionForm->basket;
    }

    /**
     * @param string $productId
     * @param array $animalSampleIds
     */
    public function setProductSampleIds($productId, $animalSampleIds)
    {
        foreach ($animalSampleIds as $animalId => $sampleId) {
            $this->fullSubmissionForm->basket->setProductAnimalSampleId($productId, $animalId, $sampleId);
        }
        $this->submissionSessionHandler->saveSubmission($this->fullSubmissionForm);
    }

    /*
     * Set product sample types, e.g. blood, serum ...etc.
     */
    public function setProductSampleType($productId, $animalSampleType)
    {
        $this->fullSubmissionForm->basket->setProductAnimalSampleType($productId, $animalSampleType);
        $this->submissionSessionHandler->saveSubmission($this->fullSubmissionForm);
    }

    public function unsetProductAnimalId($productId, $animalId)
    {
        // Remove animal sample from basket
        $this->fullSubmissionForm->basket->unsetProductAnimalSampleId($productId, $animalId);
        $this->fullSubmissionForm->basket->unsetPackageProductAnimalSampleId($productId, $animalId);

        // Remove sample ids from yourBasketForm
        $this->fullSubmissionForm->yourBasketForm->removeAnimalSampleId($animalId, $productId);

        $this->submissionSessionHandler->saveSubmission($this->fullSubmissionForm);
    }

    public function setPairablePackages() {

        $basket = $this->getBasket();

        foreach ($this->getProducts() as $product) {
            if ($product->testPackType === 'PACKAGE') {
                if ($product->isPackagePairable()) {
                    $basket->setProductIsFOP($product->id, 'true');
                }
            }
        }
    }

}