<?php

namespace ahvla\basket;

use ahvla\entity\product\Animal;
use ahvla\entity\product\BasketProduct;
use App;
use ahvla\entity\product\AnimalSampleId;

class Basket
{
    const CLASS_NAME = __CLASS__;


    /** @var  BasketProduct[] */
    private $products = [];

    /**
     * @return BasketProduct[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
 * @param BasketProduct $product
 * @return BasketProduct|null
 */
    public function addProduct($product)
    {
        if ($this->inBasket($product)) {
            return null;
        }
        $this->products[] = $product;

        // set default pool groups if required
        if($product->getSelectedSampleTypeIsPooled()) {
            $sampleType = $product->getSelectedSampleType();
            $product->setSelectedSampleType($sampleType);
            $this->setSelectedSampleTypeMaxPool($product->id,$sampleType);
            $this->addProductDefaultPoolGroups($product);
        }

        return $product;
    }

    /**
     * @param BasketProduct $product
     */
    public function addProductDefaultPoolGroups($product)
    {
        $this->setDefaultPoolGroups($product->id,$product->animalIdsSamples, $product->getSelectedSampleTypeMaxPool());
    }

    public function inBasket($searchProduct)
    {
        foreach ($this->products as $product) {
            if ($product->id == $searchProduct->id) {
                return true;
            }
        }
        return false;
    }

    public function getProductById($searchProductId)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id === $searchProductId) {
                return $product;
            }
        }

        return false;
    }

    public function getProductFromPackage($packageId, $searchProductId)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id === $packageId) {
                foreach ($this->products[$i]->constituentTests as $ii => $constituentTest) {
                    if ($constituentTest->id === $searchProductId) {
                        return $constituentTest;
                    }
                }
            }
        }

        return false;
    }

    public function inBasketById($searchProductId)
    {
        foreach ($this->products as $i => $product) {
            if ($product->testPackType === 'PACKAGE') {
                foreach ($this->products[$i]->constituentTests as $ii => $constituentTest) {
                    if ($constituentTest->id === $searchProductId) {
                        return $constituentTest;
                    }
                }
            } else {
                if ($product->id === $searchProductId) {
                    return $product;
                }
            }
        }

        return false;
    }

    public function getPackageIdFromTestId($searchProductId)
    {
        foreach ($this->products as $i => $product) {
            if ($product->testPackType === 'PACKAGE') {
                foreach ($this->products[$i]->constituentTests as $ii => $constituentTest) {
                    if ($constituentTest->id === $searchProductId) {
                        return $product->id;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param string $searchProductId
     */
    public function removeProduct($searchProductId)
    {
        foreach ($this->products as $key => $product) {
            if ($product->id == $searchProductId) {
                unset($this->products[$key]);
            }
        }

        // reset array as that causes an error in the submission if the numeric keys aren't sequential in json
        // basically json encode will treat it as an associative array rather than a sequential array
        $this->products = array_values($this->products);
    }

    public function setProductAnimalSampleId($searchProductId, $animalId, $sampleId, $sampleType = 'FIRST')
    {
        foreach ($this->products as $i => $product) {
            if ($product->id === $searchProductId) {
                foreach ($product->animalIdsSamples as $j => $animalIdSample) {
                    if ($animalIdSample->animal->id == $animalId) {
                        if (($sampleType == 'SOP' && $animalIdSample->isSOP) || ($sampleType == 'FIRST' && ! $animalIdSample->isSOP))
                        {
                            $animalIdSample->sampleId = substr( $sampleId, 0, 1000 );
                            $this->products[$i]->animalIdsSamples[$j] = $animalIdSample;
                        }
                    }
                }
            }
        }
    }

    public function setProductPackageAnimalSampleId($package, $searchProductId, $animalId, $sampleId, $sampleType = 'FIRST')
    {
        foreach ($this->products as $i => $product) {
            if ($product->testPackType === 'PACKAGE') {
                if ($product->id === $package) {
                    foreach ($this->products[$i]->constituentTests as $ii => $constituentTest) {
                        if ($constituentTest->id === $searchProductId) {
                            foreach ($constituentTest->animalIdsSamples as $j => $animalIdSample) {
                                if ((int)$animalIdSample->animal->id === $animalId) {
                                    if (($sampleType === 'SOP' && $animalIdSample->isSOP) || ($sampleType === 'FIRST' && ! $animalIdSample->isSOP)) {
                                        $animalIdSample->sampleId = substr($sampleId, 0, 1000);
                                        $this->products[$i]->constituentTests[$ii]->animalIdsSamples[$j] = $animalIdSample;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

    }

    public function setProductAnimalSamplePoolGroup($searchProductId, $animalId, $poolGroup, $disabled = false)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id == $searchProductId) {
                foreach ($this->products[$i]->animalIdsSamples as $j => $animalIdSample) {
                    if ($animalIdSample->animal->id == $animalId) {
                        $animalIdSample->poolGroup = $poolGroup;
                        $animalIdSample->poolGroupDisabled = $disabled;
                        $this->products[$i]->animalIdsSamples[$j] = $animalIdSample;
                    }
                }
            }
        }
    }

    // not yet used
    public function getProductAnimalSamplePoolGroup($searchProductId, $animalId)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id == $searchProductId) {
                foreach ($this->products[$i]->animalIdsSamples as $j => $animalIdSample) {
                    if ($animalIdSample->animal->id == $animalId) {
                        return $animalIdSample->poolGroup;
                    }
                }
            }
        }
    }

    public function getProductAnimalSampleMaxPool($searchProductId)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id == $searchProductId) {
                return $product->selectedSampleTypeMaxPool;
            }
        }
    }

    public function getProductAnimalSamplePoolGroupNumber($searchProductId)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id == $searchProductId) {
                return $product->getNumberOfPoolGroups();
            }
        }
    }

    /*
     * Set a sample type against the product
     */
    public function setProductAnimalSampleType($searchProductId, $animalSampleType)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id == $searchProductId) {
                $this->products[$i]->type = $animalSampleType;
                break;
            }
        }
    }

    public function setDefaultPoolGroups($productCode,$animalSampleIds, $maxPool) {
        $counter = 1;
        $defaultPoolGroup = 1;
        foreach ($animalSampleIds as $animalId => $poolGroup) {
            if ($counter > $maxPool) {
                $defaultPoolGroup++;
                $counter = 1;
            }
            $this->setProductAnimalSamplePoolGroup($productCode, $animalId, $defaultPoolGroup, false);
            $counter++;
        }
    }



    public function setNullPoolGroups($productCode,$animalSampleIds) {
        foreach ($animalSampleIds as $animalId => $poolGroup) {
            $this->setProductAnimalSamplePoolGroup($productCode, $animalId, null, 'disabled');
        }
    }

    /*
     * We sync up what's just been posted and what's in the current basket
     */
    public function updateProductAnimalSampleId($animalIds)
    {
        // Each product should have the correct amount of animal id entries, no more, no less.
        foreach ($this->products as $i => $product) {
             if ($product->isFOP && $product->isSOP) { // FOPSOP
                 if ($product->testPackType === 'PACKAGE') {
                     foreach ($product->constituentTests as $ii => $constituentTest) {
                         $this->updateFopSopProductAnimalIds($animalIds, $constituentTest);
                         $this->removeFopSopAnimalIDsNotInUse($animalIds, $constituentTest);
                     }
                 }
                 $this->updateFopSopProductAnimalIds($animalIds, $product); //FOPSOP
                 $this->removeFopSopAnimalIDsNotInUse($animalIds, $product);
            } else { // REGULAR
                 if ($product->testPackType === 'PACKAGE') {
                     foreach ($product->constituentTests as $ii => $constituentTest) {
                         $this->updateProductAnimalIds($animalIds, $constituentTest);
                         $this->removeAnimalIDsNotInUse($animalIds, $constituentTest);
                     }
                 }
                $this->updateProductAnimalIds($animalIds, $product);
                $this->removeAnimalIDsNotInUse($animalIds, $product);
                $this->updatePoolGroupDefaults($product);
            }
        }
    }

    public function removeFopSopAnimalIDsNotInUse($animalIds, $product)
    {
        $animalIdsSamples = $product->animalIdsSamples;

        foreach ($animalIdsSamples as $k => $animalIdSample) {

            if (!array_key_exists($animalIdSample->animal->id, $animalIds)) {
                unset($product->animalIdsSamples[$k]);
            }
        }

        ksort($product->animalIdsSamples);
    }

    // prune from basket if the keys don't exist
    public function removeAnimalIDsNotInUse($animalIds, $product)
    {
        $animalIdsSamples = $product->animalIdsSamples;

        foreach ($animalIdsSamples as $k => $animalIdSample) {

            if (!array_key_exists($k, $animalIds)) {
                unset($product->animalIdsSamples[$k]);
            }
        }

        ksort($product->animalIdsSamples);
    }

    public function updateFopSopProductAnimalIds($animalIds, $product)
    {
        foreach ($animalIds as $id => $description) {
            if ($this->animalIdExistsInProduct($id, $product)) {
                foreach ($product->animalIdsSamples as $key => $animalIdsSample) {
                    if ($animalIdsSample->animal->id === $id) {
                        $product->animalIdsSamples[$key]->animal->description = $description;
                    }
                }
            } else {
                $animal = new Animal($id, $description);
                $maxAnimalId = max(array_keys($animalIds));
                $animalSample = new AnimalSampleId($animal, '', null, ($product->getSelectedSampleTypeIsPooled()) ? false : true);
                // insert new animal sample in correct places within array (i.e. append to end of both non-sop and sop elements respectively)
                array_splice( $product->animalIdsSamples, $maxAnimalId, 0, [$animalSample] );
                $product->animalIdsSamples[] = new AnimalSampleId($animal, '', null, ($product->getSelectedSampleTypeIsPooled()) ? false : true, true);
            }
        }
    }

    public function updateProductAnimalIds($animalIds, $product)
    {
        foreach ($animalIds as $id => $description) {
            if (isset($product->animalIdsSamples[$id])) {
                $product->animalIdsSamples[$id]->animal->description = $description;
            } else {
                // for some reason it doesn't exist, we need to add the sample id to the product so we're sync'ed up
                $animal = new Animal($id, $description);
                $product->animalIdsSamples[$id] = new AnimalSampleId($animal, '', null, ($product->getSelectedSampleTypeIsPooled()) ? false : true);
            }
        }
    }

    public function updatePoolGroupDefaults($product)
    {
        if ($product->getSelectedSampleTypeIsPooled()) {
            foreach ($product->animalIdsSamples as $key => $sample) {
                if (empty($sample->poolGroup)) {
                    $product->animalIdsSamples[$key]->poolGroup = $this->getNextAvailablePoolGroupDefault($product);
                }
            }
        }
    }

    public function getNextAvailablePoolGroupDefault($product) {
        for ($i = 1; $i < count($product->animalIdsSamples); $i++) {
            if (count(array_keys($product->getPoolGroups(), $i)) < $product->getSelectedSampleTypeMaxPool()) {
                return $i;
            }
        }
    }

    // Used for SOP generation when animal qty changed.
    public function animalIdExistsInProduct($animalId, $product)
    {
        $animalIds = $this->getAnimalIdsFromProduct($product);
        return in_array($animalId,$animalIds);
    }

    public function getAnimalIdsFromProduct($product)
    {
        $animalIDs = [];
        foreach ($product->animalIdsSamples as $animalSampleId) {
            $animalIDs[] = $animalSampleId->animal->id;
        }
        return $animalIDs;
    }

    public function unsetProductAnimalSampleId($productId, $animalId)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id == $productId) {
                foreach ($this->products[$i]->animalIdsSamples as $j => $animalIdSample) {
                    if ($animalIdSample->animal->id == $animalId) {
                        unset($this->products[$i]->animalIdsSamples[$j]);
                    }
                }
            }
        }
    }

    public function unsetPackageProductAnimalSampleId($productId, $animalId)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id == $productId) {
                if ($product->testPackType === 'PACKAGE') {
                    foreach ($this->products[$i]->constituentTests as $ii => $constituentTest) {
                        foreach ($constituentTest->animalIdsSamples as $j => $animalIdSample) {
                            if ($animalIdSample->animal->id == $animalId) {
                                unset($this->products[$i]->constituentTests[$ii]->animalIdsSamples[$j]);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Remove a list of Animal(s) from the Basket
     * @param $animals
     */
    public function removeAnimalsFromBasket($animals)
    {
        foreach ($this->products as $i => $product) {
            foreach ($this->products[$i]->animalIdsSamples as $j => $animalIdSample) {
                if (in_array($animalIdSample->animal, $animals)) {
                    unset($this->products[$i]->animalIdsSamples[$j]);
                }
            }
            $this->products[$i]->animalIdsSamples = array_values($this->products[$i]->animalIdsSamples);
        }
    }

    /**
     * Add an animal to a product in the basket, or replace an existing one
     * @param $productId
     * @param Animal $animal
     * @param null $replaceIndex
     */
    public function addAnimalToProduct($productId, Animal $animal, $replaceIndex=null)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id == $productId) {
                if (!is_null($replaceIndex)) {
                    $this->products[$i]->animalIdsSamples[$replaceIndex] = new AnimalSampleId($animal, '');
                }
                else {
                    $this->products[$i]->animalIdsSamples[] = new AnimalSampleId($animal, '');
                }
                return;
            }
        }
    }

    public function getTotalItems()
    {
        $totalItems = 0;
        foreach ($this->getProducts() as $basketProduct) {
            $totalItems = $totalItems + $basketProduct->getCountAnimalIdsSamples();
        }
        return $totalItems;
    }

    public function numberProducts()
    {
        return count($this->getProducts());
    }

    public function getTotalVat()
    {
        return number_format(round($this->getTotal() * 0.2, 2), 2);
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->getProducts() as $product) {
                $total = bcadd($total, $product->getPriceForAllAnimals(), 2);
        }

        return number_format($total, 2, '.', '');
    }

    public function getTotalWithVat()
    {
        return number_format($this->getTotal() + $this->getTotalVat(), 2, '.', '');
    }

    public function getTotalWithoutVat()
    {
        return $this->getTotal();
    }

    /**
     * @param BasketProduct[] $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    public function setPackageProductSampleType($packageId, $productId, $sampleType)
    {
        foreach ($this->products as $i => $product) {
            if ($product->testPackType === 'PACKAGE') {
                if ($product->id === $packageId) {
                    foreach ($product->constituentTests as $ii => $test) {
                        if ($test->id === $productId) {
                            $this->products[$i]->constituentTests[$ii]->setSelectedSampleType($sampleType);
                        }
                    }
                }
            }
        }
    }

    public function setProductSampleType($productId, $sampleType)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id === $productId) {
                $this->products[$i]->setSelectedSampleType($sampleType);
            }
        }
    }

    public function setSOPProductSampleTypes()
    {
        foreach ($this->products as $i => $product) {
            if ($product->testPackType === 'PACKAGE') {
                foreach ($product->constituentTests as $ii => $test) {
                    foreach ($test->rawSampleTypesArray as $sampleType) {
                        if ($sampleType->isSelected) {
                            $this->products[$i]->constituentTests[$ii]->setSelectedSampleType($sampleType->sampleId);
                        }
                    }
                }
            } else {
                foreach ($product->rawSampleTypesArray as $sampleType) {
                    if ($sampleType->isSelected) {
                        $product->setSelectedSampleType($sampleType->sampleId);
                    }
                }
            }
        }
    }

    public function setSelectedSampleTypeMaxPool($productId, $sampleType)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id == $productId) {
                foreach ($product->rawSampleTypesArray as $sampleTypesAttribute) {
                    if ($sampleTypesAttribute->sampleId == $sampleType) {
                        $this->products[$i]->setSelectedSampleTypeMaxPool($sampleTypesAttribute->maxPool);
                    }
                }
            }
        }

    }

    public function setPackageProductIsFOP($packageId,$isFOP)
    {
        foreach ($this->products as $i => $product) {
            if ($product->testPackType === 'PACKAGE') {
                if ($product->id === $packageId) {
                    foreach ($product->constituentTests as $ii => $test) {
                        $this->products[$i]->constituentTests[$ii]->setIsFOP($isFOP);
                    }
                }
            }
        }
    }

    public function setProductIsFOP($productId, $isFOP)
    {
        foreach ($this->products as $i => $product) {
                if ($product->id === $productId) {
                    $this->products[$i]->setIsFOP($isFOP);
                }
            }
    }

    public function setPackageProductIsSOP($packageId,$isSOP)
    {
        foreach ($this->products as $i => $product) {
            if ($product->testPackType === 'PACKAGE') {
                if ($product->id === $packageId) {
                    foreach ($product->constituentTests as $ii => $test) {
                        $this->products[$i]->constituentTests[$ii]->setIsSOP($isSOP);
                    }
                }
            }
        }
    }

    public function setProductIsSOP($productId, $isSOP)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id === $productId) {
                $this->products[$i]->setIsSOP($isSOP);
            }
        }
    }

    public function removeProductFOP($productId)
    {
        foreach ($this->products as $i => $product) {
            if ($product->type === 'Package') {
                foreach ($product->constituentTests as $ii => $test) {
                    if ($test->id === $productId) {
                        $this->products[$i]->constituentTests[$ii]->deleteFOPSamples();
                    }
                }
            } elseif ($product->id === $productId) {
                $this->products[$i]->deleteFOPSamples();
            }
        }
    }

    public function setPackageConstituentTestsToSOP() {
        foreach ($this->products as $i => $product) {
            if ($product->testPackType === 'PACKAGE') {
                if ($product->isSOP && !$product->isFOP) {
                    foreach ($product->constituentTests as $ii => $test) {
                        $this->products[$i]->constituentTests[$ii]->isSOP = true;
                    }
                }
            }
        }
    }

    public function setSOPProductSamples() {
        foreach ($this->products as $i => $product) {
            if ($product->testPackType === 'PACKAGE') {
                foreach ($product->constituentTests as $ii => $test) {
                    $this->products[$i]->constituentTests[$ii]->generateSOPSamples();
                }
            } else {
                    $this->products[$i]->generateSOPSamples();
            }
        }
    }

    public function removeFOPProductSamples() {
        foreach ($this->products as $i => $product) {
            if ($product->testPackType === 'PACKAGE') {
                foreach ($product->constituentTests as $ii => $test) {
                    $this->products[$i]->constituentTests[$ii]->deleteFOPSamples();
                }
            } else {
                $this->products[$i]->deleteFOPSamples();
            }
        }
    }

    public function setAllProductSamplePoolGroupsToDisabled() {
        foreach ($this->products as $i => $product) {
            $this->products[$i]->setSamplePoolGroupsToDisabled();
        }
    }

    public function setProductOptionValue($searchProductId, $optionId, $value)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id == $searchProductId) {
                $this->products[$i]->toggleProductOptionSelected($optionId, $value);
            }
        }
    }

    public function setPackageOptionValue($searchProductId, $constituentTestId, $optionId, $value)
    {
        foreach ($this->products as $i => $product) {
            if ($product->id == $searchProductId) {
                foreach ($product->constituentTests as $ii => $constituentTest) {
                    if ($constituentTest->id == $constituentTestId) {
                        $this->products[$i]->constituentTests[$ii]->toggleProductOptionSelected($optionId, $value);
                    }
                }
            }
        }
    }
}