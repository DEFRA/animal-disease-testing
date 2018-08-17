<?php

namespace ahvla\form;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;

class CrudForm
{
    /**
     * @var Factory
     */
    private $validationFactory;

    private $input;
    private $tableName;
    private $schema;
    private $fields;
    private $inputValues;

    public function __construct(Factory $validationFactory,
                                Request $input)
    {
        $this->validationFactory = $validationFactory;
        $this->input = $input;
    }

    /**
     * @return Validator
     */
    public function getValidator($crudRepo, $model)
    {
        $rules = [];
        $messages = [];
        $validation = '';

        $this->tableName = $model->getTableName($this->input);
        $this->schema = $model->getDatabaseSchemaNoId($this->tableName);
        $this->fields = $model->getFormFieldsFromSchema($this->schema);
        $this->inputValues = $model->getRelevantInputData($this->input, $this->fields);

        // dynamically build the validation rules based on database schema
        foreach ($this->schema as $definitions) {
            foreach ($definitions as $key => $value) {
                switch ($key) {
                    case 'Field':
                        $field = $value; // the field name
                        break;
                    case 'Type':
                        $validation[] = $crudRepo->parseValidationField($value); // varchar/int etc
                        break;
                    case 'Null':
                        // disabled, because blank rows are accepted in current schema
                        // $validation[] = $value === 'NO' ? 'required' : null; // NOT NULL, means the field is required
                        break;

                }
            }

            $validationRules[$field] = array_flatten(array_reverse($validation));
            $validationString[$field] = implode('|', $validationRules[$field]);

            $validation = '';
        }

        foreach ($this->inputValues as $field => $values) {
            foreach ($values as $row => $value) {

                $rules[$field . '.' . $row . '.' . 'updated'] = $validationString[$field];

                // assign the rules
                // eg: $validationRules['lims_code'] = ['required','max:50'];
                foreach ($validationRules as $validationField => $validationRulesArray) {

                    foreach ($validationRulesArray as $validationRule) {

                        // there could be multiple rules
                        // only the first rule will be used
                        // there could be attributes, eg max:255
                        $validationRule = explode(':', $validationRule);
                        $parsedRule = $validationRule[0];
                        $parsedAttribute = count($validationRule) > 1 ? $validationRule[1] : null;

                        // make the error message more readable
                        switch ($parsedRule) {
                            case 'required':
                                $messages[$field . '.' . $row . '.' . 'updated.required'] = 'The field ' . $field . ' (' . $row . ') is required';
                                break;

                            case 'integer':
                                $messages[$field . '.' . $row . '.' . 'updated.integer'] = 'The field ' . $field . ' (' . $row . ') must be no longer than ' . $parsedAttribute . ' characters';
                                break;

                            // not triggered below here
                            case 'boolean':
                                $messages[$field . '.' . $row . '.' . 'updated.boolean'] = 'The field ' . $field . ' (' . $row . ') must be 0 (no) or 1 (yes)';
                                break;

                            case 'max':
                                $messages[$field . '.' . $row . '.' . 'updated.max'] = 'The field ' . $field . ' (' . $row . ') is limited to ' . $parsedAttribute . ' characters';
                                break;

                            case 'type':
                                $messages[$field . '.' . $row . '.' . 'updated.type'] = 'The field ' . $field . ' (' . $row . ') must be ' . $parsedAttribute;
                                break;

                        }

                    }

                }

            }

        }

        return $this->validationFactory->make(
            $this->input->all(),
            $rules,
            $messages
        );
    }

}