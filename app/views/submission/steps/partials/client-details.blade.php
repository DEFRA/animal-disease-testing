@if(isset($pvsClient))
    <div class="client-details row">
        <dl>
            <dt>Client</dt>
            <dd>
                <p>
                    @if(!$pvsClient->name == '')
                        {{{$pvsClient->name}}}
                    @else
                        No client name set
                    @endif
                </p>
            </dd>
        </dl>
        <dl>
            <dt>Address</dt>
            <dd>
                <p>
                @if(!$pvsClient->address->line1 == '')
                    {{{$pvsClient->address->line1}}}@if(!$pvsClient->address->line2 == ''),
                    {{{$pvsClient->address->line2}}}@if(!$pvsClient->address->line3 == ''),
                    {{{$pvsClient->address->line3}}}@if(!$pvsClient->address->line4 == ''),
                    {{{$pvsClient->address->line4}}}@if(!$pvsClient->address->line5 == ''),
                    {{{$pvsClient->address->line5}}}
                                @endif
                            @endif
                        @endif
                    @endif
                @else
                    No address set
                @endif
                </p>
            </dd>
        </dl>
        <dl>
            <dt>CPH number</dt>
            <dd>
                <p>
                    @if(!$pvsClient->cphh == '')
                        {{{$pvsClient->cphh}}}
                    @else
                        No CPH number set
                    @endif
                </p>
            </dd>
        </dl>
    </div>
@endif