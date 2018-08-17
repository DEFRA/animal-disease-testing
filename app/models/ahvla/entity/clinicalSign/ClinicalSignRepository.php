<?php

namespace ahvla\entity\ClinicalSign;

use App;
use ahvla\entity\AbstractEloquentRepository;

class ClinicalSignRepository extends AbstractEloquentRepository
{
    /*
     * @var Model
     */
    const CLASS_NAME = __CLASS__;
    protected $model;

    /**
     * A list of clinical signs lims codes to be positioned last
     * @var array
     */
    protected $positionLimsCodeLast = ['UNKNOWN', 'OTHER'];

    public function __construct(ClinicalSign $model)
    {
        $this->model = $model;
    }

    /**
     * @return ClinicalSigns[]
     */
    public function getClinicalSigns($params)
    {
        return $this->model->scopeAvian($this->model, $params)->get();
    }

    public function getClinicalSignsWithIdsAsKeys()
    {
        $results = [];
        foreach ($this->all() as $row) {
            $results[$row->lims_code] = $row;
        }
        return $results;
    }

    /**
     * @return array ClinicalSigns objects
     */
    public function getClinicalSignsByLimsCode($limsCodes, $isAvian=null)
    {
        $query = $this->model->whereIn('lims_code', $limsCodes);
        if (!is_null($isAvian)) {
            $query = $query->where('is_avian', $isAvian);
        }
        return $query->get()->all();
    }

    /**
     * Re-sorts the clinical signs by attribute name, pushing certain
     * attributes to the end
     *
     * @param ClinicalSign[] $signs
     * @return ClinicalSign
     */
    private function reSortClinicalSignsByAttribute(array $signs) {
        /** @var ClinicalSign[] $data */
        $data = [];
        $last = [];

        // split the signs into 2 arrays
        /** @var ClinicalSign $sign */
        foreach ($signs as $sign) {
            $limsCode = $sign->getAttribute('lims_code');
            if (in_array($limsCode, $this->positionLimsCodeLast)) {
                $last[$limsCode] = $sign;
            } else {
                $data[$limsCode] = $sign;
            }
        }

        // sort and re-merge the signs
        ksort($data);
        $result = array_values($data);
        foreach ($this->positionLimsCodeLast as $limsCode) {
            foreach ($last as $key => $value) {
                if ($limsCode == $key) {
                    $result[] = $value;
                    break;
                }
            }
        }

        return $result;
    }
}