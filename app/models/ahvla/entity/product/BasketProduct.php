<?php

namespace ahvla\entity\product;

use ahvla\entity\SampleType;
use ahvla\form\submissionSteps\YourBasketForm;
use ahvla\limsapi\LimsApiObject;
use stdClass;


class BasketProduct extends Product implements LimsApiObject
{
    /**
     * @var AnimalSampleId[]
     */
    public $animalIdsSamples = [];

    /** @var string */
    private $selectedSampleType = '';

    /** @var SampleType the selected sample type */
    public $selectedSampleTypeObj;

    /** @var  array */
    public $selectedBasketProductOptions = [];

    /*
     * Delivery address of product
     */
    public $limsProductSummaryDeliveryAddresses = [];

    /*
     * Number of samples as given by LIMS
     */
    public $limsNumberSamples = 0;

    public $selectedSampleTypeMaxPool = 0;

    public $useOptionDefaults = true;

    public $original_id = null;
    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        parent::__construct($product->id, $product->name, $product->rawPrice,
            $product->maxTurnaround, $product->averageTurnaround, $product->rawSpeciesArray, $product->rawSampleTypesArray,
            $product->type, $product->maxOptions, $product->minOptions,
            $product->optionTypeLabel,$product->testPackType,$product->packageCode, $product->dueDate, $product->animalSampleIds, $product->options, $product->constituentTests, $product->isFOP, $product->isSOP, $product->accredited);

        $this->original_id = isset($product->original_id)?$product->original_id:null;

        if (strpos($product->id, '_') !== false) {
            $this->original_id = $this->id;
            $this->id = str_replace("_", "-", $this->id);
        }
    }

    /**
     * @param Product $product
     * @param Animal[] $animals
     * @return BasketProduct
     */
    public static function newBasketProductEmptySampleIds(Product $product, array $animals)
    {
        $basketProduct = new BasketProduct($product);

        $defaultPoolGroup = null;
        $poolGroupDisabled = true;

        foreach ($animals as $animal) {
            $basketProduct->animalIdsSamples[] = new AnimalSampleId($animal, '', $defaultPoolGroup, $poolGroupDisabled);
        }

        return $basketProduct;
    }

    /**
     * @param Product $product
     * @param AnimalSampleId[] $animalIdsSamples
     * @return BasketProduct
     */
    public static function newBasketProductWithSampleIds(Product $product, array $animalIdsSamples)
    {
        $basketProduct = new BasketProduct($product);
        $basketProduct->animalIdsSamples = $animalIdsSamples;
        return $basketProduct;
    }

    public function getPriceForAllAnimals()
    {
        if ($this->getSelectedSampleTypeMaxPool()) {
            return $this->price * $this->getNumberOfPoolGroups();
        }
        return $this->price * count($this->animalIdsSamples);
    }

    public function getCountAnimalIdsSamples()
    {
        if ($this->getSelectedSampleTypeMaxPool()) {
            return $this->getNumberOfPoolGroups();
        }

        $count = count($this->animalIdsSamples);
        return $count;
    }

    public function isMaxPoolExceeded()
    {
        if ($this->getSelectedSampleTypeMaxPool()) {
            $maxPool = $this->selectedSampleTypeMaxPool;

            // check if a pool group exceeds selected sample type max pool number
            foreach ($this->animalIdsSamples as $animalIdsSample) {

                $poolGroup = $animalIdsSample->poolGroup;
                $poolGroups = $this->getPoolGroups();

                $poolGroupCounter = array_count_values($this->poolGroupsNullRemove($poolGroups));

                if ($poolGroupCounter) {
                    if ($poolGroupCounter[$poolGroup] > $maxPool) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function poolGroupsNullRemove($poolGroups) {

        $poolGroupsArray = array_filter($poolGroups, function ($poolGroups) {
            return ($poolGroups !== null && $poolGroups !== "");
        });

        return $poolGroupsArray;
    }

    public function poolGroupsContainNonInteger()
    {
        if ($this->getSelectedSampleTypeMaxPool()) {
            if ($this->poolGroupsContainNonIntegerIndex() || $this->poolGroupsContainNonIntegerIndex() === 0) {
                return true;
            }
        }
        return false;
    }

    public function poolGroupsContainNonIntegerIndex()
    {
        $poolGroups = $this->getPoolGroups();

        foreach ($poolGroups as $index => $poolGroup) {
            if (!is_numeric($poolGroup)) {
                return $index;
            }
        }
        return false;
    }


    public function getNumberOfPoolGroups()
    {
        return count(array_unique($this->poolGroupsNullRemove($this->getPoolGroups())));
    }

    public function getPoolGroups()
    {
        $poolGroups = array();

        // collect pool groups
        foreach($this->animalIdsSamples as $animalIdsSample) {
            $poolGroups[] = $animalIdsSample->poolGroup;
        }

        return $poolGroups;
    }

    public function setSamplePoolGroupsToDisabled()
    {
        foreach($this->animalIdsSamples as $animalIdsSample) {
            $animalIdsSample->poolGroupDisabled = 'disabled';
        }
    }

    public function getPooledTotal()
    {
        $total = 0;
        $total = $this->price * $this->getNumberOfPoolGroups();

        return number_format($total, 2, '.', '');
    }

    /**
     * @return string
     */
    public function getSelectedSampleType()
    {

        if (count($this->rawSampleTypesArray) === 1) {
            /** @var SampleType $sampleType */
            $sampleType = reset($this->rawSampleTypesArray);
            return $sampleType->sampleId;
        }


        return $this->selectedSampleType;
    }

    /**
     * @return SampleType
     */
    public function getSelectedSampleTypeObj()
    {
        if (count($this->rawSampleTypesArray) === 1) {
            /** @var SampleType $sampleType */
            $sampleType = reset($this->rawSampleTypesArray);
            return $sampleType;
        }

        return $this->selectedSampleTypeObj;
    }

    /**
     * @return string
     */
    public function getSelectedSampleTypeLabel()
    {
        $selectedSampleType = $this->getSelectedSampleType();

        foreach($this->rawSampleTypesArray as $sampleType){
            if($sampleType->sampleId == $selectedSampleType){
                return $sampleType->testSampleName;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getSelectedSampleTypeIsPooled()
    {
        $selectedSampleType = $this->getSelectedSampleType();

        foreach($this->rawSampleTypesArray as $sampleType){
            if($sampleType->sampleId == $selectedSampleType){
                return $sampleType->isPooled;
            }
        }

        return null;
    }

    /**
     * @param string $sampleType
     */
    public function setSelectedSampleType($selectedSampleType)
    {
        $this->selectedSampleType = $selectedSampleType;

        foreach($this->rawSampleTypesArray as $sampleType){
            if ($sampleType->sampleId === $selectedSampleType)
            {
                $this->selectedSampleTypeObj = $sampleType;
            }
        }

        return $this->selectedSampleType;
    }

    /**
     * Setter for isFOP
     *
     * @param $isFOP
     * @return bool
     */
    public function setIsFOP($isFOP)
    {
        $this->isFOP = $isFOP == 'true' ? true : false;

        return $this->isFOP;
    }

    public function removeFOP()
    {
        $this->isFOP = $isFOP == 'true' ? true : false;

        return $this->isFOP;
    }

    /**
     * Setter for isSOP
     *
     * Will generate or delete the SOP samples.
     *
     * @param $isSOP
     * @return AnimalSampleId[]|array
     */
    public function setIsSOP($isSOP)
    {
        $this->isSOP = $isSOP == 'true' ? true : false;

        switch ($isSOP) {
            case 'true':
                return $this->generateSOPSamples();

            case 'false':
                return $this->deleteSOPSamples();
        }
    }

    /**
     * Creates the SOP samples for each sample in this product
     *
     * @return AnimalSampleId[]
     */
    public function generateSOPSamples()
    {
        if (! $this->hasSOPSamples()) {
            foreach ($this->animalIdsSamples as $animalIdsSample) {
                $this->animalIdsSamples[] = new AnimalSampleId($animalIdsSample->animal, '', null, null, true);
            }
        }

        return $this->animalIdsSamples;
    }

    /**
     * Deletes all SOP samples for this product.
     *
     * @return AnimalSampleId[]|array
     */
    public function deleteSOPSamples()
    {
        $this->animalIdsSamples = array_filter($this->animalIdsSamples, function ($sample) {
            return ! $sample->isSOP;
        });

        return $this->animalIdsSamples;
    }

    /**
     * Deletes all FOP samples for this product.
     *
     * @return AnimalSampleId[]|array
     */
    public function deleteFOPSamples()
    {
        $this->animalIdsSamples = array_filter($this->animalIdsSamples, function ($sample) {
            return $sample->isSOP;
        });

        return $this->animalIdsSamples;
    }

    /**
     * Checks whether the product
     *
     * @return int
     */
    public function hasSOPSamples()
    {
        $sopSamples = [];
        $sopSamples = array_filter($this->animalIdsSamples, function ($sample) {
            return $sample->isSOP;
        });

        return count($sopSamples);
    }

    /**
     * @param int $maxPool
     */
    public function setSelectedSampleTypeMaxPool($maxPool)
    {
        $this->selectedSampleTypeMaxPool = $maxPool;
    }

    public function getSelectedSampleTypeMaxPool()
    {
        return $this->selectedSampleTypeMaxPool;
    }

    public function getLimsApiObject()
    {
        $limsObject = new stdClass();

        // single values

        // convert back any underscores previously removed in test id for LIMS submission.
        if (!is_null($this->original_id)) {
            $limsObject->productId = $this->original_id;
        } else {
            $limsObject->productId = $this->id;
        }

        $limsObject->productType = $this->testPackType;
        $limsObject->isFOP = $this->isFOP;
        $limsObject->isSOP = $this->isSOP;


        if ($this->testPackType === 'PACKAGE') { //packages
            foreach ($this->constituentTests as $constituentTest) {

                // convert back any underscores previously removed in constituent test id for LIMS submission.
                if (!is_null($constituentTest->original_id)) {
                    $constituentTest->id = $constituentTest->original_id;
                }

                foreach ($constituentTest->selectedBasketProductOptions as $ctProductOptionId) {
                    $ctProductOption = $constituentTest->getProductOptionById($ctProductOptionId);
                    $ctOption = $ctProductOption->getLimsApiObject();

                    $limsObject->options[] = array(
                        'packageProductId' => $constituentTest->getPackageProductId(),
                        'option' => $ctOption
                    );
                }

                foreach ($constituentTest->animalIdsSamples as $ctAnimalIdsSample) {
                    $animalSample = $ctAnimalIdsSample->getLimsApiObject();
                    $animalSample->packageProductId = $constituentTest->getPackageProductId();
                    $limsObject->animalSamples[] = $animalSample;
                }

                $limsObject->sampleTypes[] = array(
                    'packageProductId' => $constituentTest->getPackageProductId(),
                    'sampleType' => $constituentTest->getSelectedSampleType()
                );

            }
        } else { //tests

            $limsObject->animalSamples = [];
            foreach ($this->animalIdsSamples as $animalSample) {
                $limsObject->animalSamples[] = $animalSample->getLimsApiObject();
            }

            $limsObject->sampleTypes[] = array(
                'packageProductId' => "",
                'sampleType' => $this->removeIsPooledFromSampleTypes($this->getSelectedSampleType())
            );

            foreach ($this->selectedBasketProductOptions as $productOptionId) {
                $productOption = $this->getProductOptionById($productOptionId);
                $option = $productOption->getLimsApiObject();
                $limsObject->options[] = array(
                    'packageProductId' => "",
                    'option' =>  $option
                );
            }
        }

        return $limsObject;
    }

    /**
     *
     *  Necessary sampleTypeId change due to LIMS not differentiating between pooled/non-pooled sampleTypeIds, however, PVS requires unique identifiers.
     *
     */
    public function removeIsPooledFromSampleTypes($sampleType)
    {
        if (strpos($sampleType, '_ISPOOLED') !== false) {
            $sampleType = str_replace('_ISPOOLED', '', $sampleType);
        }
        return $sampleType;
    }

    public function getSampleTypesSelectOptions()
    {
        $selectOptions = [];
        foreach($this->rawSampleTypesArray as $sampleType){
            $selectOptions[$sampleType->sampleId] = $sampleType->testSampleName;
        }
        return $selectOptions;
    }

    /**
     * @param ProductOption $searchOption
     * @return bool
     */
    public function isOptionSelected(ProductOption $searchOption)
    {
        if (empty($this->selectedBasketProductOptions) && $this->useOptionDefaults) {
            if ($searchOption->isDefault) {
                return true;
            }
        }

        if (in_array($searchOption->id, $this->selectedBasketProductOptions)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $optionId
     * @param int $selected
     */
    public function toggleProductOptionSelected($searchOptionId, $selected)
    {
        if (($key = array_search($searchOptionId, $this->selectedBasketProductOptions)) !== false) {
            unset($this->selectedBasketProductOptions[$key]);
        }

        if ($selected) {
            $this->selectedBasketProductOptions[] = $searchOptionId;
        }

        $this->useOptionDefaults = false; // flag to state user has now amended options so ignore initial defaults
    }

    public function getSelectedOptionsCount()
    {
        return count($this->selectedBasketProductOptions);
    }

    /*
     * This is provided by getSubmissions at the moment, we basically back fill this on landing page, but it may
     * come as getProducts later.
     */
    public function setLimsProductSummaryDeliveryAddresses($addressList)
    {
        $this->limsProductSummaryDeliveryAddresses = $addressList;
    }

    public function setLimsNumberSamples($numSamples)
    {
        $this->limsNumberSamples = $numSamples;
    }

    /**
     * Determines whether the product is eligible for paired serology.
     * This is accomplished by checking the selected sample type &
     * ensuring that it is indeed enabled for paired serology.
     *
     * @return bool
     */
    public function isPairable()
    {
        if ($this->getSelectedSampleType() == '' || ! isset($this->selectedSampleTypeObj) || ! property_exists($this->selectedSampleTypeObj, 'isPairable'))
        {
            return false;
        }

        return $this->selectedSampleTypeObj->isPairable;
    }

    public function isPackagePairable()
    {
        $sampleTypes = $this->rawSampleTypesArray;

        foreach ($sampleTypes as $sampleType) {
            if (!$sampleType->isPairable) {
                return false;
            }
        }

        return true;
    }
}