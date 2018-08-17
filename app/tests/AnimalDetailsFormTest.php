<?php

use ahvla\form\submissionSteps\AnimalDetailsForm;

class AnimalDetailsFormTest extends TestCase
{
    /** @var  \ahvla\form\submissionSteps\AnimalDetailsForm */
    private $animalDetailsForm;

    public function setUp()
    {
        $this->animalDetailsForm = new AnimalDetailsForm();
    }

    public function test_getAnimalIds_WhenNoneIsSet()
    {
        $this->assertSame([], $this->animalDetailsForm->getAnimals());
    }

    public function test_getAnimalIds_WhenSomeFound1digit()
    {
        $this->animalDetailsForm->animal_id0 = 'AnimalId0';
        $this->animalDetailsForm->animal_id1 = 'AnimalId1';
        $this->animalDetailsForm->animal_id2 = 'AnimalId2';

        $this->assertSame(
            [
                0 => 'AnimalId0',
                1 => 'AnimalId1',
                2 => 'AnimalId2',
            ],
            $this->animalDetailsForm->getAnimals()
        );
    }

    public function test_getAnimalIds_WhenSomeFound2digits()
    {
        $this->animalDetailsForm->animal_id0 = 'AnimalId0';
        $this->animalDetailsForm->animal_id1 = 'AnimalId1';
        $this->animalDetailsForm->animal_id12 = 'AnimalId12';
        $this->animalDetailsForm->animal_id20 = 'AnimalId20';

        $this->assertSame(
            [
                0 => 'AnimalId0',
                1 => 'AnimalId1',
                12 => 'AnimalId12',
                20 => 'AnimalId20'
            ],
            $this->animalDetailsForm->getAnimals()
        );
    }

}
