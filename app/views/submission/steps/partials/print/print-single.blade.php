<label><strong>You have chosen to send all samples to your local centre</strong></label>

<article role="article" class="group" style="display: table-cell; float: none; width: 60%;">

    @if(isset($addresses->deliveryAddresses['singleAddress']))

        @if(!empty( $addresses->deliveryAddresses['singleAddress']['address']['address1'] )) {{{ $addresses->deliveryAddresses['singleAddress']['address']['address1'] }}} <br /> @endif
        @if(!empty( $addresses->deliveryAddresses['singleAddress']['address']['address2'] ))
            <?php
                $splitAddressArr = splitAddressString($addresses->deliveryAddresses['singleAddress']['address']['address2']);
                array_map(function($addressItem){
                    print $addressItem . '<br>';
                }, $splitAddressArr);
            ?>
        @endif
        @if(!empty( $addresses->deliveryAddresses['singleAddress']['address']['address3'] )) {{{ $addresses->deliveryAddresses['singleAddress']['address']['address3'] }}} <br /> @endif
        @if(!empty( $addresses->deliveryAddresses['singleAddress']['address']['labEmail'] )) {{{ $addresses->deliveryAddresses['singleAddress']['address']['labEmail'] }}} <br /> @endif

    @endif

    <br />

    @if(isset($subUrl))

        <div class="delivery-address">

            <?php
                $url = '/print-address-label?draftSubmissionId='.$submissionId.'&address_config=single';
            ?>

            {{ Form::open(array('url'=>$url,'class'=>'form','target'=>'_blank','autocomplete'=>'off')) }}

                <div>Print address label:</div>

                {{ Form::hidden('address_config', 'single') }}

                {{ Form::submit('Print address label',['name'=>'printaddresslabel','class'=>'button']) }}

                </fieldset>

            {{ Form::close() }}

        </div>

        <div class="delivery-address">

            <?php
                $url = '/print-dispatch-note?draftSubmissionId='.$submissionId.'&address_config=single';
            ?>

            {{ Form::open(array('url'=>$url,'class'=>'form','target'=>'_blank','autocomplete'=>'off')) }}

                <div>Print dispatch note:</div>

                {{ Form::hidden('address_config', 'single') }}

                {{ Form::submit('Print dispatch note',['name'=>'printdispatchnote','class'=>'button']) }}

            {{ Form::close() }}

        </div>

    @endif

</article>