<fieldset>
    <legend>Client</legend>
    <p>Enter the details of your client - if you have the full CPH this can be used to retrieve the client record.</p>
    <div class="form-group">

        <div id="client-cphh-input" @if($persistence->isIsEditClientMode())style="display: none" @endif>
            @include('submission.steps.partials.client-details.client-cphh-input')
        </div>

        @include('submission.steps.partials.client-details.client-name-input')
        @include('submission.steps.partials.client-details.client-address-input')
        <p>
            <a id="cancel-client" href="#" class="js-searchClientsButton push--top no-js-hide">Cancel</a>
        </p>
    </div>
</fieldset>
<hr />