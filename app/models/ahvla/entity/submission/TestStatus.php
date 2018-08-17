<?php
namespace ahvla\entity\submission;
use ahvla\limsapi\LimsApiObject;
use ahvla\entity\submission\Sample;
use stdClass;
class TestStatus implements LimsApiObject
{
    public $code;
    public $description;
    public $quantity;
    public $resultsDueDate;
    public $status;
    public $samples;
    function __construct($code, $description, $quantity, $resultsDueDate, $status, $samples = [])
    {
        $this->code = $code;
        $this->description = $description;
        $this->quantity = $quantity;
        $this->resultsDueDate = $resultsDueDate;
        $this->status = $status;
        $this->samples = $this->setSamples($samples);
    }
    public function getLimsApiObject()
    {
        $limsObject = new stdClass();
        $limsObject->code = $this->code;
        $limsObject->description = $this->description;
        $limsObject->quantity = $this->quantity;
        $limsObject->resultsDueDate = $this->resultsDueDate;
        $limsObject->status = $this->status;
        $limsObject->samples = $this->samples;
        return $limsObject;
    }
    public function setSamples($samples)
    {
        $samples_array = [];
        foreach ($samples as $sample) {
            $samples_array[] = new Sample($sample['animalId'], $sample['sampleId'], $sample['sampleType'], $sample['poolGroup']);
        }
        return $samples_array;
    }
}