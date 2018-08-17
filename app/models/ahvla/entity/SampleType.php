<?php

namespace ahvla\entity;


class SampleType
{
    public $sampleId;
    public $sampleName;
    public $testSampleName;

    /** @var boolean is eligible for paired serology */
    public $isPairable;

    /** @var boolean is the sample type selected */
    public $isSelected;

    function __construct($sampleId, $sampleName, $testSampleName, $isPooled, $maxPool, $isPairable, $isSelected = false)
    {
        $this->sampleId = $sampleId;
        $this->sampleName = $sampleName;
        $this->testSampleName = $testSampleName;
        $this->isPooled = $isPooled;
        $this->maxPool = $maxPool;
        $this->isPairable = $isPairable;
        $this->isSelected = $isSelected;
    }

    public static function convertLimsJsonSampleTypes($limsSampleTypes){
        $sampleTypes = [];
        foreach($limsSampleTypes as $limsSampleType){
            if ($limsSampleType['isPooled']) {
                $limsSampleType['sampleId'] = self::addIsPooledToSampleType($limsSampleType['sampleId']);
            }
            $sampleTypes[] =
                new SampleType(
                    $limsSampleType['sampleId'],
                    $limsSampleType['sampleName'],
                    $limsSampleType['testSampleName'],
                    $limsSampleType['isPooled'],
                    $limsSampleType['maxPool'],
                    $limsSampleType['isPairable'],
                    isset($limsSampleType['selected']) ? $limsSampleType['selected'] : false
                );
        }

        return $sampleTypes;
    }

    /*
     * Necessary sampleTypeId change due to LIMS not differentiating between pooled/non-pooled sampleTypeIds, however, PVS requires unique identifiers.
     *
     */
    public static function addIsPooledToSampleType($sampleTypeId){
        return $sampleTypeId.'_ISPOOLED';
    }

}