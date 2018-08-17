<div class="report-header">

    <div class="hidden">
        <img src="/assets/images/apha-sq.png">
    </div>

    <p class="font-xsmall">

        @if(isset($results) && isset($results['OwningVicName']))
            <strong>{{ $results['OwningVicName'] }}</strong><br />
        @endif

        @if(isset($results) && isset($results['OwningVicAddress']))
            {{ $results['OwningVicAddress'] }}<br />
        @endif

        @if(isset($results) && isset($results['OwningVicGeneralMailboxEmail']))
            Email: <a href="mailto:{{ $results['OwningVicGeneralMailboxEmail'] }}">{{ $results['OwningVicGeneralMailboxEmail'] }}</a><br />
        @endif

        @if(isset($results) && isset($results['OwningVicPhoneFax']))
            {{ $results['OwningVicPhoneFax'] }}<br />
        @endif

    </p>

</div>