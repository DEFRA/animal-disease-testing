<?php
namespace ahvla\entity\crud;

use ahvla\entity\AbstractEloquentRepository;
use Input;

class CrudRepository extends AbstractEloquentRepository
{
    /*
     * @var Model
     */
    const CLASS_NAME = __CLASS__;
    protected $model;

    public function __construct(Crud $model) {
        $this->model = $model;
    }

    /**
     * @description Return the schema string eg: varchar(50) as an array ['varchar',50]
     * @param $value
     * @return array|mixed
     * @throws \Exception
     */
    public function parseValidationField($value) {

        $string = explode('(', $value);
        $type = $this->convertDbSchemaTypeToValidationRule($string[0]);
        $max = str_replace(')', '', $string[1]);

        if (!$type === "integer") {
            if (strlen($max) > 0) {
                return strlen($type) > 0 ?
                    [$type, 'max:' . $max] :
                    ['max:' . $max];
            }
        }

        return $type;

    }

    /**
     * @param $type
     * @return mixed
     * @throws \Exception
     */
    public function convertDbSchemaTypeToValidationRule($type) {

        try {
            foreach ($this->varsMap() as $db => $rule) {
                if ($type === $db) {
                    return $rule;
                }
            }
            throw new \Exception('There is no mapping for the database schema value to the framework validation rule in varsMap().');
        }
        catch (Exception $e) {

            // todo tbc ?? Exception handling example on another branch

        }

    }

    /**
     * @description map database definitions to framework validation rules
     * @return array
     */
    private function varsMap() {

        // note: when updating this array, update the $messages
        return [
            'int' => 'integer',
            'smallint' => 'integer',
            'tinyint' => 'boolean',
            'varchar' => '', // n/a
        ];

    }

    /**
     * @return array
     */
    public function getRelevantInputData() {
        return Input::except('tableId', '_token', '_method');
    }

    /**
     * @param $data
     * @return string
     */
    public function presentTableRow($data) {
        $dataPresenter = '<table>';
        foreach ($data as $key => $val) {
            $dataPresenter .= '<tr><col width="50%"></col><td>'.$key.'</td><td>'.$val.'</td></>';
        }
        $dataPresenter .= '</table>';

        return $dataPresenter;
    }
}