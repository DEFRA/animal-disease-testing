@section('before_validation_box')
@show
<a name="{{{$validationFieldName}}}"></a>
<div data-field-name="{{{$validationFieldName}}}"
     class="ValidationBoxField {{{$validationObject->hasError($validationFieldName)?'validation-error-box':''}}}">
    @include('submission.validation.validation-error', ['error'=>$validationObject->getError($validationFieldName)])
    @section('validation_box')
    @show
</div>
@section('after_validation_box')
@show