<p>
Your tests will be carried out at one laboratory.
</p>

{{Form::hidden('send_samples_package', 'together')}}

<fieldset id="sendSamplesSelection" class="inline">
    <div class="delivery-address">
    	<h2>Send samples to</h2>
        @if(isset($addresses['singleAddress']))

            {{{ $addresses['singleAddress']['address']['address1'] }}}<br />
            {{{ $addresses['singleAddress']['address']['address2'] }}}<br />
            {{{ $addresses['singleAddress']['address']['address3'] }}}<br />
            <a href="mailto:{{{ $addresses['singleAddress']['address']['labEmail'] }}}">{{{ $addresses['singleAddress']['address']['labEmail'] }}}</a>

        @endif
    </div>
</fieldset>