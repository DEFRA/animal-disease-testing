@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'age_category']
)

@section('before_validation_box')
<div id="ageCategorySelection">
    <fieldset class="inline">
        <legend>Age Category</legend>
        @overwrite
        @section('validation_box')
            <div class="form-group" id="ageCategoryResults">

                @if(!count($ageCategories))
                    @include('submission.steps.partials.animal-details.result-templates.age-category-template')
                @endif
                @foreach($ageCategories as $ageCat)
                    @include('submission.steps.partials.animal-details.result-templates.age-category-template', ['age_cat'=>$ageCat])
                @endforeach
                @overwrite
            </div>
        @section('after_validation_box')
    </div>
            <div id="ageDetailSelection">
                <legend>Age detail (optional)</legend>
                <div class="column-half"> 
                         <div class="form-group">
                            <label for="age-detail">Age</label>
                            <p class="error-message">Please enter age as a number only e.g. 1,2,3</p>
                                {{Form::text(
                                        'age_detail',
                                        $persistence->age_detail,
                                        ['id'=>'age-detail','class'=>'persistentInput form-control','autocomplete' => 'off']
                                )}}
                         </div>
                         <div class="form-group">           
                            <label for="age-indicator">Age Indicator</label>
                            <p class="error-message">Please select an Age Indicator</p>
                            {{Form::select('age_indicator', $ageIndicators, isset($persistence->age_indicator)?$persistence->age_indicator:'', ['id'=>'age-indicator','class'=>'search-field persistentInput'])}}
                            <label for="age-is-estimate" class="form-checkbox">                  
                                    {{Form::checkbox('age_is_estimate',1,$persistence->age_is_estimate?true:false,['class'=>'js-numeric persistentInput','id'=>'age-is-estimate'])}}
                                    Is this an estimate?
                                </label>
                         </div>
                 </div>
            </div>
    </fieldset>
    <hr />
</div>
@overwrite

