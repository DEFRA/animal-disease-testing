@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'date_samples_taken']
)
@section('before_validation_box')
@overwrite
@section('validation_box')
    <fieldset class="validate inline-fields" data-validation-name="dateOfBirthDate" data-validation-type="fieldset" data-validation-rules="allNonEmpty" data-validation-children="day month year">
        <legend>Date the sample was taken</legend>
        <label for="sample_date_year" class="">
            <span id="calendar-date-entry-text" class="no-js-hide">Select a date using our calendar:</span><span id="manual-date-entry-text" class="js-hidden">Please enter a date:</span>
        </label>
        {{ Form::text('sample_date_year', $persistence->sample_date_year, ['id'=>'year', 'class'=>'form-control persistentInput js-date', 'autocomplete' => 'off']); }}
        <p id="sample-date-example" class="js-hidden">for example {{{ date('Y') }}}-{{{ date('m') }}}-{{{ date('d') }}} (yyyy-mm-dd)</p>
        <p class="no-js-hide">
            <a href="#" id="enter-date-manually-link">Enter date manually</a>
            <a href="#" id="use-date-picker-link" style="display: none">Use date picker</a>
        </p>
    </fieldset>
    <hr />
@overwrite

@section('after_validation_box')
@overwrite