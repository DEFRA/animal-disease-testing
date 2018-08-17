<li class="validationErrorLinkContainer" @if(!isset($validationError))style="display: none" @endif>
    <a class="validationErrorLink"
       data-error-field="{{isset($validationError)?current($validationError->getFormFieldsName()):''}}"
       href="{{isset($validationError)?$validationError->getUrlToErrorField():''}}{{isset($validationError)?'#'.current($validationError->getFormFieldsName()):''}}">
        {{isset($validationError)&&$validationObject->isFullSubmissionValidation()?$validationError->getSourceFormLabel().'&nbsp;-&nbsp;':''}}{{isset($validationError)?$validationError->getMessage():''}}
    </a>
</li>