@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'animal_cphh']
)

@section('before_validation_box')
    <div id="animals-cphh" display="style: {{{ ($persistence->animals_address_type === 1) ? 'block' : 'none' }}}">
@overwrite

@section('validation_box')
    <div class="row validation-group">
        <label for="animal_cphh">Animal CPH eg 48/234/2348:</label>
        {{Form::text(
            'animal_cphh',
            $persistence->animal_cphh,
            ['id'=>'animal_cphh', 'class'=>'persistentInput form-control js-cphh','autocomplete' => 'off']
        )}}}
    </div>
@overwrite

@section('after_validation_box')
    </div>
@overwrite