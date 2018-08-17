<?php

namespace ahvla\form\submissionSteps;

use ahvla\basket\Basket;
use ahvla\basket\BasketManager;
use ahvla\entity\product\ProductOption;
use ahvla\form\FullSubmissionForm;
use ahvla\form\validation\ValidationError;
use ahvla\SubmissionUrl;
use App;

class YourBasketForm extends StepSubmissionForm
{
    const CLASS_NAME = __CLASS__;
    const LABEL = 'Your basket';

    public $sample_date_year = null;
    public $animals_at_address = null;
    //public $animals_still_at_address = null;
    public $sop_animal_farm = null;
    public $sop_animal_address1 = null;
    public $sop_animal_address2 = null;
    public $sop_animal_address3 = null;
    public $sop_animal_county = null;
    public $sop_animal_sub_county = null;
    public $sop_animal_postcode = null;
    public $sop_animal_cphh = null;
    public $pairedSeroReset = false;


    function __construct()
    {
        parent::__construct(null, false);
    }

    public function getProductRemovedFromBasket($input)
    {
        foreach ($input as $key => $value) {
            if (preg_match('~removeProductFromBasket(\d*)~', $key, $matches)) {
                return $input['removeProductId' . $matches[1]];
            }
        }
        return null;
    }

    public function getAnimalIdRemoveFromProduct($input)
    {
        foreach ($input as $attribute => $value) {
            if (preg_match('~removeAnimalId_(\w{1,100})_(\d{1,3})~', $attribute, $matches)) {
                return [
                    'product' => $matches[1],
                    'animalId' => $matches[2]
                ];
            }
        }

        return null;
    }

    /** @inheritdoc */
    public function beforeSave(FullSubmissionForm $fullSubmissionForm)
    {
        $productAnimalSampleIds = [];
        $productPackageAnimalSampleIds = [];
        $productAnimalSamplePoolGroups = [];
        $packageProductFOPs = [];
        $packageProductSOPs = [];

        $input = get_object_vars($this);
        foreach ($input as $attribute => $value) {
            if (preg_match('~sampleid_(\d{1,3})_((?!_)[A-Za-z0-9-]{1,100})(_SOP)?~', $attribute, $matches)) {
                $productCode = $matches[2];
                $animalId = $matches[1];
                $isSOP = isset($matches[3]) ? 'SOP' : 'FIRST';
                $sampleId = $value;
                $productAnimalSampleIds[$productCode][$animalId][$isSOP] = $sampleId;
            } elseif (preg_match('~sampleid_package_([\w\-]{1,100})_(\d{1,3})_((?!_)[A-Za-z0-9-]{1,100})(_SOP)?~', $attribute, $matches)) {
                $productCode = $matches[3];
                $animalId = $matches[2];
                $packageCode = $matches[1];
                $isSOP = isset($matches[4]) ? 'SOP' : 'FIRST';
                $sampleId = $value;

                $productPackageAnimalSampleIds[$packageCode][$productCode][$animalId][$isSOP] = $sampleId;
            } elseif (preg_match('~sampleTypesSelect_([\w\-]+)~', $attribute, $matches)) {
                $fullSubmissionForm->basket->setProductSampleType($matches[1], $value);
                $fullSubmissionForm->basket->setSelectedSampleTypeMaxPool($matches[1], $value);
            } elseif (preg_match('~packageSampleTypesSelect_([\w\-]+)_([\w\-]+)~', $attribute, $matches)) {
                $fullSubmissionForm->basket->setPackageProductSampleType($matches[1], $matches[2], $value);
            } elseif (preg_match('~poolGroupId_(\d{1,3})_([\w\-]{1,100})~', $attribute, $matches)) {
                $productCode = $matches[2];
                $animalId = $matches[1];
                $poolGroup = $value;

                if ($fullSubmissionForm->basket->getProductAnimalSampleMaxPool($productCode)) {
                    $productAnimalSamplePoolGroups[$productCode][$animalId] = $poolGroup;
                } else {
                    $productAnimalSamplePoolGroups[$productCode][$animalId] = null;
                    $this->removePoolGroup($animalId, $productCode);
                }
            } elseif (preg_match('~^isFOP_([\w\-]+)~', $attribute, $matches)) {
                $fullSubmissionForm->basket->setProductIsFOP($matches[1], $value);
                $this->pairedSeroReset = false;
                if ($value === 'false' && !$fullSubmissionForm->isSOP) { // if FOP has been set to false (and submission not a SOP), no paired sero req'd for this product
                    $fullSubmissionForm->basket->setProductIsSOP($matches[1], $value); // set SOP samples to false also
                    $this->pairedSeroReset = true;
                }
            } elseif (preg_match('~^isSOP_([\w\-]+)~', $attribute, $matches)) {
                if (!$this->pairedSeroReset) {
                    $fullSubmissionForm->basket->setProductIsSOP($matches[1], $value);
                }
            } elseif (preg_match('~package_isFOP_([\w\-]+)~', $attribute, $matches)) {
                $fullSubmissionForm->basket->setPackageProductIsFOP($matches[1], $value);
                $packageProductFOPs[$matches[1]] =  $value;
            } elseif (preg_match('~package_isSOP_([\w\-]+)~', $attribute, $matches)) {
                $fullSubmissionForm->basket->setPackageProductIsSOP($matches[1], $value);
                $packageProductSOPs[$matches[1]] =  $value;
            }
        }

        // if any packages contain FOP/SOP tests, set the package to FOP/SOP accordingly
        $this->setPackageFOP($packageProductFOPs, $fullSubmissionForm->basket);
        $this->setPackageSOP($packageProductSOPs, $fullSubmissionForm->basket);

        $fullSubmissionForm->basket = $this->saveProductOptionCheckboxes($input, $fullSubmissionForm->basket);

        foreach ($productAnimalSampleIds as $productCode => $animalSampleIds) {
            foreach ($animalSampleIds as $animalId => $sample) {
                foreach ($sample as $sampleType => $sampleId) {
                    $fullSubmissionForm->basket->setProductAnimalSampleId($productCode, $animalId, $sampleId, $sampleType);
                }
            }
        }

        foreach ($productPackageAnimalSampleIds as $package => $products) {
            foreach ($products as $productCode => $animalSampleIds) {
                foreach ($animalSampleIds as $animalId => $sample) {
                    foreach ($sample as $sampleType => $sampleId) {
                        $fullSubmissionForm->basket->setProductPackageAnimalSampleId($package, $productCode, $animalId, $sampleId, $sampleType);
                    }
                }
            }
        }

        // set any pool groups created by user (if any)
        foreach ($productAnimalSamplePoolGroups as $productCode => $poolGroups) {
            foreach ($poolGroups as $animalId => $poolGroup) {
                $fullSubmissionForm->basket->setProductAnimalSamplePoolGroup($productCode, $animalId, $poolGroup);
            }
        }

        // set default pool groups or nulls depending on if user has selected pooled or non-pooled sample type for product.
        // if pool groups previously set and pooled sample type selected, will do nothing.
        foreach ($productAnimalSampleIds as $productCode => $animalSampleIds) {
            $maxPool = $fullSubmissionForm->basket->getProductAnimalSampleMaxPool($productCode); // check if pooled sample type selected
            $poolGroupCount = $fullSubmissionForm->basket->getProductAnimalSamplePoolGroupNumber($productCode); // check if any pool groups are already set
            if ($maxPool && !$poolGroupCount) {
                $fullSubmissionForm->basket->setDefaultPoolGroups($productCode,$animalSampleIds, $maxPool);
            } elseif (!$maxPool) { // set pool groups to null only if sample type is non-pooled and pool group has a positive count i.e. user has switched from pooled sample type to non-pooled after previously saving pool groups.
                $fullSubmissionForm->basket->setNullPoolGroups($productCode,$animalSampleIds);
            }
        }

        return $fullSubmissionForm;
    }

    public function validate(\Illuminate\Validation\Factory $laravelValidatorFactory)
    {

        $basketManager = new BasketManager(
            App::make('Illuminate\Foundation\Application'),
            $this->getFullSubmissionForm()
        );

        $errors = [];

        if ($this->sop) {
            if ($this->animals_at_address === null) {
                $errors[] = new ValidationError(
                    'Please select Yes or No to indicate if the animal address has changed since submitting the First of Pair sample',
                    ['animals_at_address'],
                    $this
                );
            }

            if (!$this->animals_at_address) {
                $laravelValidator = $laravelValidatorFactory->make(
                    [
                        'sop_animal_address1' => $this->sop_animal_address1
                    ],
                    [
                        'sop_animal_address1' => 'required',
                    ],
                    [
                        'sop_animal_address1.required' => 'Please specify at least the first line of the animals&#39;s address',
                        'sop_animal_postcode.required' => 'Specify an animal address CPH or Postcode'
                    ]
                );

                // one of these fields are required
                $inputData = [
                    'postcode' => $this->sop_animal_postcode,
                    'cphh' => $this->sop_animal_cphh
                ];

                $laravelValidator->sometimes('sop_animal_postcode', 'required', function () use ($inputData)
                {
                    // return true if Postcode and Cphh are both blank - i.e.  if true, add animal_postcode.required to rules
                    return $inputData['postcode'] === '' && $inputData['cphh'] === '';
                });

                $errors = array_merge($errors, $this->wrapLaravelValidator($laravelValidator, ['sop_animal_address1','sop_animal_postcode']));
            }

            $laravelValidator = $laravelValidatorFactory->make(
                [
                    'date_samples_taken' => $this->sample_date_year
                ],
                [
                    'date_samples_taken' => 'required|date_format:Y-m-d|before:tomorrow|after:01/01/2000'
                ],
                [
                    'date_samples_taken.required' => 'Please specify the date when the Second of Pair samples were taken'
                ]
            );

            $errors = array_merge($errors, $this->wrapLaravelValidator($laravelValidator, ['date_samples_taken']));
        }

        foreach ($basketManager->getProducts() as $basketItem) {
            if ($basketItem->testPackType === 'PACKAGE') {
                foreach ($basketItem->constituentTests as $test) {
                    if (!$test->getSelectedSampleType()) {
                        $errors[] = new ValidationError(
                            'Package product ' . $test->id . ' is in basket but no sample type is selected',
                            [$this->getPackageSampleTypeSelectBoxName($basketItem->id, $test->id)],
                            $this
                        );
                    }
                }
            } else {
                if (!$basketItem->getSelectedSampleType()) {
                    $errors[] = new ValidationError(
                        'Product ' . $basketItem->id . ' is in basket but no sample type is selected',
                        [$this->getSampleTypeSelectBoxName($basketItem->id)],
                        $this
                    );
                }
            }
            // Pooling validation
            // check first all pool groups have integer values
            if ($basketItem->poolGroupsContainNonInteger()) {
                $errors[] = new ValidationError(
                    'Product ' . $basketItem->id . ' has non numeric pool group names',
                    [$this->getPoolGroupBoxName($basketItem->poolGroupsContainNonIntegerIndex(),$basketItem->id)],
                    $this
                );
            } else { // futher pool group validation
                if ($basketItem->isMaxPoolExceeded()) {
                    $errors[] = new ValidationError(
                        'Product ' . $basketItem->id . ' has exceeded maximum pool group allocation',
                        [$this->getSampleTypeSelectBoxName($basketItem->id)],
                        $this
                    );
                }
            }
            if ($basketItem->getOptions()) { //The product has extra options - there may be min max options
                if (
                    ($basketItem->minOptions && $basketItem->getSelectedOptionsCount() < (int)$basketItem->minOptions)
                    || ($basketItem->maxOptions && $basketItem->getSelectedOptionsCount() > (int)$basketItem->maxOptions)
                ) {
                    /** @var ProductOption $optionExample */
                    $optionExample = current($basketItem->getOptions());
                    $min = $basketItem->minOptions ? 'min ' . $basketItem->minOptions : '';
                    $max = $basketItem->maxOptions ? ' max ' . $basketItem->maxOptions : '';
                    $errors[] = new ValidationError(
                        'Product ' . $basketItem->id . ' has invalid count of selected options (' . $min . $max . ')',
                        ['productOption_' . $basketItem->id . '_' . $optionExample->getOptionId()],
                        $this
                    );
                }
            }
        }

        return $errors;
    }

    public function getRouteUrl()
    {
        /** @var SubmissionUrl $subUrl */
        $subUrl = App::make(SubmissionUrl::CLASS_NAME);
        return $subUrl->build('step5');
    }

    private function getSampleTypeSelectBoxName($id)
    {
        return 'sampleTypesSelect_' . $id;
    }

    private function getPackageSampleTypeSelectBoxName($packageId, $testId)
    {
        return 'packageSampleTypesSelect_' . $packageId.'_'.$testId;
    }

    private function getPoolGroupBoxName($id, $productCode)
    {
        return 'poolGroupId_' .$id.'_'.$productCode;
    }

    private function saveProductOptionCheckboxes($input, $basket)
    {
        foreach ($basket->getProducts() as $product) {
            if ($product->testPackType === 'PACKAGE') {
                $this->savePackageOptions($input, $product, $basket);
            } else {
                $this->saveOptions($input, $product, $basket);
            }
        }

        return $basket;
    }

    public function savePackageOptions($input, $product, $basket)
    {
        foreach ($product->constituentTests as $constituentTest) {
            foreach ($constituentTest->getOptions() as $productOption) {
                $prodOptKey = 'productOption_' . $constituentTest->id . '_' . $productOption->getOptionId();
                if (isset($input[$prodOptKey]) && $input[$prodOptKey]) {
                    $basket->setPackageOptionValue($product->id, $constituentTest->id, $productOption->getOptionId(), 1);
                } else {
                    $basket->setPackageOptionValue($product->id, $constituentTest->id, $productOption->getOptionId(), 0);
                }
            }
        }
    }

    public function saveOptions($input, $product, $basket)
    {
        foreach ($product->getOptions() as $productOption) {
            $prodOptKey = 'productOption_' . $product->id . '_' . $productOption->getOptionId();
            if (isset($input[$prodOptKey]) && $input[$prodOptKey]) {
                $basket->setProductOptionValue($product->id, $productOption->getOptionId(), 1);
            } else {
                $basket->setProductOptionValue($product->id, $productOption->getOptionId(), 0);
            }
        }
    }

    public function getCheckboxesInputName()
    {
        return ['productOption_'];
    }

    /**
     * Remove sample id from yourBasketForm
     * @param $animalId
     * @param $productId
     */
    public function removeAnimalSampleId($animalId, $productId)
    {
        $sampleId = 'sampleid_' .$animalId. '_' .$productId;
        if (property_exists($this, $sampleId)) {
            unset($this->{$sampleId});
        }

        $this->removePackageAnimalSampleId($animalId, $productId);
        $this->removePoolGroup($animalId, $productId);
    }
    
    public function removePackageAnimalSampleId($animalId, $productId)
    {
        $sampleId = '~sampleid_package_' .$productId. '_' .$animalId.'_([\w\-]{1,100})~';
        foreach ($this as $name => $value) {
            if (preg_match($sampleId, $name)) {
                unset($this->{$name});
            }
        }
    }

    public function removePoolGroup($animalId, $productId)
    {
        $poolGroup = 'poolGroupId_' .$animalId. '_' .$productId;
        if (property_exists($this, $poolGroup)) {
            unset($this->{$poolGroup});
        }
    }

    /**
     * Build the list of sample ids in this form from scratch, using the contents of the Basket
     * @param Basket $basket
     */
    public function refreshSampleIdsFromBasket(Basket $basket)
    {
        $products = $basket->getProducts();

        // remove all sample ids then rebuild
        $this->removeAllSampleIds();
        $this->removeAllSamplePoolGroups();

        if (count($products)) {
            foreach ($products as $i => $product) {
                foreach ($product->animalIdsSamples as $j => $animalIdSample) {
                    $property = 'sampleid_' .$animalIdSample->animal->id. '_' .$product->id;
                    $sampleId = $animalIdSample->sampleId;
                    $this->$property = $sampleId;
                }
            }
        }
    }

    /**
     * Remove all sample ids from the form
     */
    public function removeAllSampleIds()
    {
        $this->removeElements('sampleid_');
    }

    /**
     * Remove all sample pool groups from the form
     */
    public function removeAllSamplePoolGroups()
    {
        $this->removeElements('poolGroupId_');
    }

    /**
     * Remove elements from the form
     */
    public function removeElements($element)
    {
        $properties = array_keys(get_object_vars($this));
        $self = $this;
        array_map(function($property) use($self, $element) {

            if (strpos($property, $element) === 0) {
                unset($self->$property);
            }

        }, $properties);
    }

    public function setPackageFOP($packageProductFOPs, $basket)
    {
        foreach($packageProductFOPs as $package => $FOP) {
            if ($FOP === "true") {
                $basket->setProductIsFOP($package, "true");
            } else {
                $basket->setProductIsFOP($package, "false");
            }
        }
    }

    public function setPackageSOP($packageProductSOPs, $basket)
    {
        foreach($packageProductSOPs as $package => $SOP) {
            if ($SOP === "true") {
                $basket->setProductIsSOP($package, "true");
            } else {
                $basket->setProductIsSOP($package, "false");
            }
        }
    }

}
