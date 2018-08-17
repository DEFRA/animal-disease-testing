@extends('layouts.blank')
@section('title', 'Print Address Label -')
@section('content')

<table>
    <tr>
        <td>
            <div>
                @if(isset($submissionId))
                    {{{ $submissionId }}}
                @endif
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <?php
            echo $barcode;
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <p>For research and diagnostic purposes</p>

            @if($addressConfig=='single')

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

                @endif

            @else

                @if(isset($addresses->deliveryAddresses['separateAddresses']))

                    @foreach($addresses->deliveryAddresses['separateAddresses'] as $separateAddress)
                        @if ($separateAddress['address']['labId'] === $labId)

                            @if(isset($separateAddress['address']['address1']))
                                {{{ $separateAddress['address']['address1'] }}} <br />
                            @endif
                            @if(isset($separateAddress['address']['address2']))
                                <?php
                                $splitAddressArr = splitAddressString($separateAddress['address']['address2']);
                                array_map(function($addressItem){
                                    print $addressItem . '<br>';
                                }, $splitAddressArr);
                                ?>
                            @endif

                        @endif
                    @endforeach

                @endif
            @endif
            
        </td>
    </tr>
</table>

@stop





