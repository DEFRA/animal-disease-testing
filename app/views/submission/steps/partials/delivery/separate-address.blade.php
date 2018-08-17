@extends('submission.inputs.input-layout',
    ['validationObject'=>$validationObject,'validationFieldName'=>'send_samples_package']
)
<p>
Your tests will be carried out at one laboratory.
</p>

<p>
You can choose to send all your samples to the testing laboratory, or to your local laboratory.
</p>


@section('before_validation_box')
@overwrite
@section('validation_box')

    <fieldset id="sendSamplesSelection">

        <legend class="visuallyhidden">Delivery selection</legend>

        <label class="block-label" data-target="example-ni-number" for="send_samples_package_seperate">
            {{Form::radio('send_samples_package','separate',isset($persistence)?($persistence->send_samples_package=='separate'):'',['id'=>'send_samples_package_seperate','class'=>'persistentInput']);}}
            Send samples directly to the testing laboratory to minimise delay
        </label>
        <div class="clear"></div>
        <div>
            @if(isset($addresses['separateAddresses']))

                @foreach($addresses['separateAddresses'] as $i=>$delivery)
                    <div class="delivery-address">
                        <p>
                            {{{ $delivery['address']['address1'] }}}<br />
                            {{{ $delivery['address']['address2'] }}}<br />
                            {{{ $delivery['address']['address3'] }}}<br />
                            <a href="mailto:{{{ $delivery['address']['labEmail'] }}}">{{{ $delivery['address']['labEmail'] }}}</a>
                        </p>
                        <a href="#" class="js-toggle" data-target="samples-details-{{{$i}}}">Show samples</a>
                        <div id="samples-details-{{{$i}}}" class="hidden panel-indent">
                            <table>
                                <thead>
                                    <th>Test ID</th>
                                    <th>Animal ID</th>
                                    <th>Sample ID</th>
                                    <th>Sample Type</th>
                                </thead>
                                <tbody>
                                @foreach($delivery['tests'] as $j=>$test)
                                    <tr>
                                        <td>{{{ (!is_null($test['packageCode']) ? $test['packageCode']. ' / ' : '').$test['testId'] }}}</td>
                                        <td>{{{ $test['animalId'] }}}</td>
                                        <td>{{{ $test['sampleId'] }}}</td>
                                        <td>{{{ $test['sampleType'] }}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

            @endif
        </div>
        <label class="block-label" for="send_samples_package_together">
            {{Form::radio('send_samples_package','together',isset($persistence)?($persistence->send_samples_package=='together'):'',['id'=>'send_samples_package_together','class'=>'persistentInput']);}}
            Send all samples to single location (turnaround delayed)
        </label>
        <div class="clear"></div>
        <div class="delivery-address">
            @if(isset($addresses['singleAddress']))

                {{{ $addresses['singleAddress']['address']['address1'] }}}<br />
                {{{ $addresses['singleAddress']['address']['address2'] }}}<br />
                {{{ $addresses['singleAddress']['address']['address3'] }}}<br />
                <a href="mailto:{{{ $addresses['singleAddress']['address']['labEmail'] }}}">{{{ $addresses['singleAddress']['address']['labEmail'] }}}</a>

            @endif
        </div>

    </fieldset>

@overwrite

@section('after_validation_box')
@overwrite
