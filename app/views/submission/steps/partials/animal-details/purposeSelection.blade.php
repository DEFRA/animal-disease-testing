@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'purpose']
)

@section('before_validation_box')
<div id="purposeSelection">
    <fieldset class="inline">
        <legend>Purpose</legend>
        @overwrite
        @section('validation_box')
            <div class="radioGroup row" id="purposeSelectionResults">
                
                @if(!count($purposes))
                    @include('submission.steps.partials.animal-details.result-templates.purpose-template')
                @endif
                @foreach($purposes as $purpose)
                    @include('submission.steps.partials.animal-details.result-templates.purpose-template', ['purpose'=>$purpose])
                @endforeach
                @overwrite
            </div>
        @section('after_validation_box')
    </fieldset>
    <hr />
</div>
@overwrite