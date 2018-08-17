<?php

use ahvla\basket\BasketManager;
use ahvla\entity\product\AnimalSampleId;
use ahvla\form\submissionSteps\TestsForm;

class TestsFormTest extends TestCase
{
    /** @var  TestsForm */
    private $testsForm;

    /** @var  BasketManager */
    private $basketManager;

    public function setUp()
    {
        $this->basketManager = $this->getMockBuilder(BasketManager::CLASS_NAME)
            ->disableOriginalConstructor()
            ->getMock();

        $this->testsForm = new TestsForm($this->basketManager);
    }

    public function test_beforeSave_NoSampleAnimalIdsSet()
    {
        $this->testsForm->sampleid0TC020002 = 'Invalid';

        $this->basketManager
            ->expects($this->exactly(0))
            ->method('setProductSampleIds');

        $this->testsForm->beforeSave();

    }


    public function test_beforeSave()
    {
        $this->testsForm->sampleid_0_TC020002 = 'Blood123';
        $this->testsForm->sampleid_12_TC020002 = 'Saliva456';
        $this->testsForm->sampleid_5_9dTCdssA212 = 'Blood666';
        $this->testsForm->sampleid_6_9dTCdssA212 = 'Faeses777';

        $this->basketManager
            ->expects($this->exactly(2))
            ->method('setProductSampleIds');

        $this->basketManager
            ->expects($this->at(0))
            ->method('setProductSampleIds')
            ->with('TC020002',
                [
                    new AnimalSampleId('0', 'Blood123'),
                    new AnimalSampleId('12', 'Saliva456')
                ]
            );

        $this->basketManager
            ->expects($this->at(1))
            ->method('setProductSampleIds')
            ->with('9dTCdssA212', [
                new AnimalSampleId('5', 'Blood666'),
                new AnimalSampleId('6', 'Faeses777')
            ]);


        $this->testsForm->beforeSave();

    }
}