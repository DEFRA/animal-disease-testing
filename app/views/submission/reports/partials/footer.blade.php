<div class="report-summary-header">
    <div class="report-footer-notes">
        <small>
            ‡ - Test subcontracted; opinions given and interpretations of the result are outside the scope of UKAS accreditation.<br>
            † - Not UKAS accredited; opinions given and interpretations of the result are outside the scope of UKAS accreditation.<br>
            § - Flexible scope; opinions given and interpretations of the result are outside the scope of UKAS accreditation.
        </small>

        @if ( isset($results['HasAccreditation']) )

            @if ( $results['HasAccreditation'] == true )

                <div class="ukas-image">
                    <img src="/assets/images/img-ukas1769.gif">
                </div>

            @endif

        @endif


    </div>
</div>