<?php

namespace ahvla\entity\clinicalSign;


class ClinicalSignDuration {

    private $lims_code;
    private $description;

    function __construct($lims_code, $description)
    {
        $this->lims_code = $lims_code;
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getLimsCode()
    {
        return $this->lims_code;
    }

    /**
     * @param boolean $fallbackToLimsCode
     * @return mixed
     */
    public function getDescription($fallbackToLimsCode = false)
    {
        if ($fallbackToLimsCode && !$this->description && $this->lims_code) {
            $this->description = self::getOptions()[$this->lims_code];
        }

        return $this->description;
    }

    /**
     * @return array
     */
    public static function getOptions()
    {
        return [
            '3D' => '0 - 3 days',
            'LT2WKS' => '4 days - 2 weeks',
            'GT2WKS' => '> 2 weeks',
            'UNKNOWN' => 'Unknown',
        ];
    }


}