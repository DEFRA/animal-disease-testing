@extends('layouts.blank')
@section('title', 'Print Dispatch Note -')
@section('content')

<table style="width: 595px;" id="dispatchNote">
    <tbody>
        <tr>
            <td style="width: 100%;">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="width: 100%">
                                PACKING SLIP FOR SAMPLE DISPATCH<br>
                                Submission ID:

                                @if(isset($submissionId))
                                    {{{ $submissionId }}}
                                @endif

                                <br>
                            </td>

                        </tr>
                        <tr>
                            <td style="width: 100%">
                                <?php
                                echo $barcode;
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 100%">
                <table style="border-spacing:0px;width: 100%;">
                    <tbody><tr>
                        <td style="
                            border-top: 1px solid #000000;
                            border-left:  1px solid #000000;
                            width: 50%;
                            ">Dispatch to</td>
                        <td style="
                            border-top: 1px solid #000000;
                            border-left:  1px solid #000000;
                            border-right: 1px solid #000000;
                            width: 50%;
                            ">Submitting PVS</td>
                    </tr>
                    <tr>
                        <td style="
                            border-top: 1px solid #000000;
                            border-left:  1px solid #000000;
                            border-bottom:  1px solid #000000;
                            vertical-align: top;
                            ">

                            @if($addressConfig=='single')

                                @if(isset($addresses->deliveryAddresses['singleAddress']))

                                    @if(!empty( $addresses->deliveryAddresses['singleAddress']['address']['address1'] )) {{{ $addresses->deliveryAddresses['singleAddress']['address']['address1'] }}} <br /> @endif
                                    @if(!empty( $addresses->deliveryAddresses['singleAddress']['address']['address2'] )) {{{ $addresses->deliveryAddresses['singleAddress']['address']['address2'] }}} <br /> @endif
                                    @if(!empty( $addresses->deliveryAddresses['singleAddress']['address']['address3'] )) {{{ $addresses->deliveryAddresses['singleAddress']['address']['address3'] }}} <br /> @endif
                                    @if(!empty( $addresses->deliveryAddresses['singleAddress']['address']['labEmail'] )) {{{ $addresses->deliveryAddresses['singleAddress']['address']['labEmail'] }}} <br /> @endif

                                @endif

                            @else

                                @if(isset($addresses->deliveryAddresses['separateAddresses']))

                                    @foreach($addresses->deliveryAddresses['separateAddresses'] as $separateAddress)
                                        @if ($separateAddress['address']['labId'] === $labId)
                                            @if(isset($separateAddress['address']['address1']))
                                                {{{ $separateAddress['address']['address1'] }}} <br />
                                            @endif

                                            @if(isset($separateAddress['address']['address2']))
                                                {{{ $separateAddress['address']['address2'] }}} <br />
                                            @endif

                                            @if(isset($separateAddress['address']['address3']))
                                                {{{ $separateAddress['address']['address3'] }}} <br />
                                            @endif
                                        @endif
                                    @endforeach

                                @endif

                            @endif

                        </td>
                        <td style="
                            border-top: 1px solid #000000;
                            border-left:  1px solid #000000;
                            border-right:  1px solid #000000;
                            border-bottom:  1px solid #000000;
                            vertical-align: top;
                            ">

                            <?php

                                echo $user->getPracticeLimsCode().'<br />';
                                echo implode('<br />',$pvs->pvsAddress());

                            ?>

                        </td>
                    </tr>
                </tbody></table>
            </td>
        </tr>
        <tr>
            <td style="width: 100%;">
                <table style="border-spacing:0px;width: 100%;border: 1px solid #000000">
                    <tbody>
                    <tr><td>Date submitted:{{{ date('d/M/Y') }}}</td></tr>
                    <tr>
                        <td>
                            @if($reviewConfirmForm->getClinicianName())Contact for submission: {{{$reviewConfirmForm->getClinicianName()}}}@endif
                            @if($reviewConfirmForm->email_notification_email)email: {{{$reviewConfirmForm->email_notification_email}}}@endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 100%;">
                <table style="border-spacing:0px;width: 100%;border: 1px solid #000000">
                    <tbody>
                    <tr><td>Owner: {{{$clientDetailsForm->edited_client_name}}}</td></tr>
                    <tr><td>
                        {{{$clientDetailsForm->clientFarm}}}
                    </td></tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width: 100%;">
                <table style="border-spacing:0px;width: 100%;border: 1px solid #000000">
                    <tbody>

                        <tr>
                            <td>

                                <b>Animal Details:</b><br />
                                <b>Species:</b> {{{$species}}}<br />
                                <b>Breed:</b> {{{$breed}}}<br />
                                <b>Age:</b> {{{$ageCategory}}}<br />
                                <b>Sex:</b> {{{$sexGroup}}}<br />
                                <b>Written Clinical History:</b><br />
                                @if(isset($ClinicalHistory->written_clinical_history))
                                    {{{ $ClinicalHistory->written_clinical_history }}}
                                @endif
                                <br />

                            </td>
                        </tr>

                    </tbody></table>
            </td>
        </tr>
        <?php
        $saddresses = $addresses->deliveryAddresses['separateAddresses'];

        if (isset( $addressConfig ) && ( $addressConfig === 'separate' )) {
            foreach($saddresses as $address) {
                if ($address['address']['labId'] === $labId) {
                    $tests = $address['tests'];
                }
            }
        } elseif ( isset( $addressConfig ) && ( $addressConfig === 'single' ) ) {
            $saddresses = $addresses->deliveryAddresses['singleAddress'];

            $tests = $saddresses['tests'];
        }
        $containsPooledTests = $addresses->containsPooledTests($tests);
        ?>
        <tr>
            <td style="width: 100%; ">
                <table style="padding:0px;border-spacing:0px;width: 100%;border: 1px solid #000000">
                    <tbody><tr><td colspan="{{{(isset($containsPooledTests) && $containsPooledTests)?'6':'5'}}}" style="border-bottom: 1px solid #000000">Samples Enclosed</td></tr>
                    <tr>
                        <td style="border: 1px solid #000;">Sample type</td>
                        <td style="border: 1px solid #000;">Test ID</td>
                        <td style="border: 1px solid #000;">Test desc.</td>
                        <td style="border: 1px solid #000;">Sample Id</td>
                        <td style="border: 1px solid #000;">Animal Ref.</td>
                        @if (isset($containsPooledTests) && $containsPooledTests)
                            <td style="border: 1px solid #000;">Pool group</td>
                        @endif
                    </tr>
                    <?php displayProducts($tests, $containsPooledTests); ?>
                    </tbody></table>
            </td>
        </tr>
    </tbody>
</table>

@stop





