<?php

namespace ahvla\entity\crud;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Crud extends Eloquent {

    protected $table = 'admin_lookups';

    /**
     * @return array
     */
    public function getAdminLookupTables() {
        return Crud::all() ?:[];

    }

    /**
     * @param $tableId
     * @return array
     */
    public function getAdminLookupTable($tableId) {
        return Crud::find($tableId) ?:[];
    }

    /**
     * @param $input
     * @return mixed
     */
    public function getTableName($input) {
        return $input->get('table');
    }

    /**
     * @param $tableName
     * @return array
     */
    public function getDatabaseSchema($tableName) {
        return \Schema::getColumnListing($tableName) ?:[];
    }

    /**
     * @description Get database schema and ignore id field which should always be first
     * @return array
     */
    public function getDatabaseSchemaNoId($tableName) {

        return array_except(
            \DB::select(\DB::raw('SHOW COLUMNS FROM ' . $tableName)), 0
        );
    }

    /**
     * @param $tableId
     * @return mixed
     */
    public function getTableNameFromTableId($tableId) {
        $table = $this->getAdminLookupTable($tableId);
        return $table->table_name;
    }

    /**
     * @param $tableId
     * @param $fieldId
     * @return mixed|static
     */
    public function getExistingRowFromDatabase($tableId, $fieldId) {
        $tableName = $this->getTableNameFromTableId($tableId);
        return \DB::table($tableName)
            ->where('id', $fieldId)
            ->first();
    }


    /**
     * @param $tableName
     * @return array|static[]
     */
    public function getExistingRowsFromDatabase($tableName) {
        return \DB::table($tableName)
            ->orderBy('id')
            ->paginate(50);
    }

    /**
     * @param $tableName
     * @param $fieldId
     * @return int
     */
    public function deleteRow($tableName, $fieldId) {
        return \DB::table( $tableName )
            ->where('id',$fieldId)
            ->delete();
    }

    /**
     * @return array
     */
    public function getFormFieldsFromSchema($schema) {

        foreach ($schema as $definition) {
            $fields[] = $definition->Field;
        }

        return $fields;

    }

    /**
     * @return mixed
     */
    public function getRelevantInputData($input, $fields) {

        foreach ($fields as $field) {
            $inputData[$field] = $input->get($field);
        }

        return $this->compactFormData($inputData);

    }

    /**
     * @description Compact the input data and remove superfluous values
     * @param $inputData
     * @return mixed
     */
    private function compactFormData($inputData) {

        foreach ($inputData as $field => $values) {
            foreach ($values as $id => $value) {
                $inputValues[$field][$id] = $value['updated'];
            }

        }

        return $inputValues;
    }

}