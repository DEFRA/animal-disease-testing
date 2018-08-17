@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'animal_address1']
)

@section('before_validation_box')
    <div id="animals-address-type" @if($persistence->animals_at_address === '1' || $persistence->animals_at_address === null)style="display:none"@endif>
@overwrite

@section('validation_box')

    {{-- <p>Fill in new animal address details.</p> --}}
    <div class="form-group">
        @include('submission.steps.partials.client-details.animal-address-input')
    </div>
    <hr />
@overwrite

@section('after_validation_box')
    </div>
@overwrite