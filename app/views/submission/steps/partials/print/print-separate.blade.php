<p><strong>Send samples to the following addresses:</strong></p>

<article role="article" class="group" style="display: table-cell; float: none; width: 60%;">
    @foreach($delivery['tests'] as $j=>$test)
        <div>{{{ (isset($test['packageCode']) && !is_null($test['packageCode'])) ? $test['packageCode'].' / ' : '' }}} {{{ $test['testId'] }}} / {{{ $test['animalId'] }}} / {{{ $test['sampleId'] }}} / {{{ $test['sampleType'] }}}  {{{ fopsopStatus($test['testPairedStatus']) }}} <br /></div>
    @endforeach

    <br />

    <div>
        @if(!empty( $delivery['address']['address1'] )) {{{ $delivery['address']['address1'] }}} <br /> @endif
        @if(!empty( $delivery['address']['address2'] ))
            <?php
                $splitAddressArr = splitAddressString($delivery['address']['address2']);
                array_map(function($addressItem){
                    print $addressItem . '<br>';
                }, $splitAddressArr);
            ?>
        @endif
        @if(!empty( $delivery['address']['address3'] )) {{{ $delivery['address']['address3'] }}} <br /> @endif
    </div>

    <br />

    @if(isset($subUrl))

        <div class="delivery-address">

            <?php
                $url = '/print-address-label?draftSubmissionId='.$submissionId.'&address_config=separate&lab_id='.$delivery['address']['labId'];
            ?>

            {{ Form::open(array('url'=>$url,'class'=>'form','target'=>'_blank','target'=>'_blank','autocomplete'=>'off')) }}

                <div>Print Address Label:&nbsp;</div>

                {{ Form::submit('Print Address Label',['name'=>'printaddresslabel','id'=>'printaddresslabel', 'class'=>'button']) }}

            {{ Form::close() }}

        </div>

        <div class="delivery-address">

            <?php
                $url = '/print-dispatch-note?draftSubmissionId='.$submissionId.'&address_config=separate&lab_id='.$delivery['address']['labId'];
            ?>

            {{ Form::open(array('url'=>$url,'class'=>'form','target'=>'_blank','autocomplete'=>'off')) }}

                <div>Print Dispatch Note</div>

                {{ Form::submit('Print Dispatch Note',['name'=>'printdispatchnote','id'=>'printdispatchnote', 'class'=>'button']) }}

            {{ Form::close() }}

        </div>

    @endif

</article>