<div id="validationGlobalDiv" @if(isset($validationObject) && !count($validationObject->getErrors()))style="display: none" @endif>
    <h2>Some fields have not been completed correctly, please complete before submitting</h2>
    <p>Click the error message to correct the following</p>

    <ul class="validationList">
    @include('submission.validation.validation-error-link')

    @if(isset($validationObject) && count($validationObject->getErrors()))
        @foreach($validationObject->getErrors() as $validationError)
            @include('submission.validation.validation-error-link',['validationError'=>$validationError, 'validationObject'=>$validationObject])
        @endforeach
        <li class="js-hidden">
            <input class="refresh-button refresh-button2 js-hidden" type="submit" name="refresh" value="Refresh">
        </li>
    @endif
    </ul>
</div>