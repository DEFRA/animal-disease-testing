<div class="submission-item">
    <div class="header">
        <div class="header-left">
            <h3 class="flush--top">
                @if (!empty($submission->submissionId))
                    {{{ $submission->submissionId }}}
                @elseif(!empty($submission->draftSubmissionId))
                    Draft&nbsp;Id:&nbsp;{{{ $submission->draftSubmissionId }}}
                @endif
                @if (!empty($submission->sendersReference))
                    &nbsp;&nbsp;{{{ '('.$submission->sendersReference.')' }}}
                @endif
            </h3>

        </div>
        <div class="header-right">
            <div class="status">
                @if ($submission->status=='Draft')
                    <div class="submission-status-initial">Draft</div>
                @elseif($submission->status=='Cancelled')
                    <div class="submission-status-cancel">Cancelled</div>
                @elseif($submission->status=='Submitted')
                    <div class="submission-status-initial">Submitted</div>
                @elseif($submission->status=='Received')
                    <div class="submission-status-progress">Received</div>
                    {{-- changed status date  --}}
                @elseif($submission->status=='In Progress')
                    <div class="submission-status-progress">In progress</div>
                    @if (!empty($submission->limsResultsDueDate))
                        <div class="submission-status-initial">All results due</br>{{{ date( 'd/M/Y', mktime( 0, 0, 0,  date_parse($submission->limsResultsDueDate)['month'],
                                                                date_parse($submission->limsResultsDueDate)['day'],
                                                                date_parse($submission->limsResultsDueDate)['year'] ) ) }}}</div>
                    @endif
                @elseif($submission->status=='All Tests Complete')
                    @if($submission->isFOP === true && !$submission->limsResultsAvailable && empty($submission->previousSubmissionId))
                        <!-- Left intentionally blank - must just be FOP tests i.e. no regular tests in submission because "FOP ResultsAvailable = true" can only occur in the SOP -->
                    @else
                        <div class="submission-status-progress">All tests complete</div>
                    @endif
                @endif

                {{--FOPs/SOPs--}}
                @if ($submission->isFOP === true && $submission->isSOP === false && empty($submission->previousSubmissionId))
                    <div class="submission-status-initial">Awaiting second of pair</div>
                    {{-- SOP submitted --}}
                @elseif($submission->isFOP === false && $submission->isSOP === true && !empty($submission->previousSubmissionId))
                    <div class="submission-status-initial">First of pair on {{{ $submission->previousSubmissionId }}}</div>
                    {{-- ** FOP submitted (but not awaiting SOP becuase it has been submitted also) ** --}}
                @elseif ($submission->isFOP === true && $submission->isSOP === false && !empty($submission->previousSubmissionId))
                    <div class="submission-status-initial">Second of pair on {{{ $submission->previousSubmissionId }}}</div>
                @endif

                {{-- Samples Missing/Overdue --}}
                @if ($submission->samplesOverdue)
                    <div class="submission-status-overdue">Samples overdue</div>
                @elseif ($submission->samplesMissing)
                    <div class="submission-status-overdue">Samples Missing</div>
                @endif

                <div class="view-results text--right">
                    @if ($submission->limsResultsAvailable)

                        <?php
                            $submissionId = $submission->draftSubmissionId;

                            if (!empty($submission->submissionId)) {
                                $submissionId = $submission->submissionId;
                            }
                        ?>

                        <a href="/reports/report?submissionId={{{ $submissionId }}}">View results</a>
                        <a href="/reports/pdf?draftSubmissionId={{{ $submissionId }}}">View PDF</a>
                        @if (Session::has('pdf_failure_'.$submissionId))
                            <div class="validation-error" id="{{{'pdf_failure_message_'.$submissionId}}}">
                                {{{Session::get('pdf_failure_'.$submissionId)}}}
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>
    <table class="subheader">
        <thead>
            <tr>
                <th>Samples to</th>
                <th>Client</th>
                <th>Client farm</th>
                <th>Species</th>
                <th>Clinician</th>
                <th>Order submitted</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>
                    <?php

                        $addresses = [];

                        if(is_array($submission->tests)) {
                            foreach($submission->tests as $testIdx=>$test) {
                                if(is_array($test->limsProductSummaryDeliveryAddresses)) {
                                    foreach($test->limsProductSummaryDeliveryAddresses as $address) {
                                        if (isset($address['deliveryAddress'])) {
                                            $currentAddress = $address['deliveryAddress']['address1'];
                                            if ( !in_array($currentAddress, $addresses) ) {
                                                $addresses[] = $currentAddress;
                                            }
                                        }
                                    }
                                }
                            }

                            if (sizeof( $addresses ) >= 1) {
                                foreach($addresses as $currentAddress) {
                                    echo $currentAddress.'<br>';
                                }
                            }
                            else {
                                echo '---';
                            }

                        }

                    ?>
                </td>
                <td>@if (empty($submission->clientName)) --- @else{{{ $submission->clientName }}}@endif</td>
                <td>@if (empty($submission->clientFarm)) --- @else{{{ $submission->clientFarm }}}@endif</td>
                <td>@if (empty($submission->animalSpecies)) --- @else{{{ $submission->animalSpecies }}}@endif</td>
                <td>@if (empty($submission->clinician)) --- @else{{{ $submission->clinician }}}@endif</td>
                <td>
                    @if (empty($submission->limsSubmittedDate)) ---
                    @else
                        {{{ date( 'd/M/Y', mktime( 0, 0, 0,  date_parse($submission->limsSubmittedDate)['month'],
                                                            date_parse($submission->limsSubmittedDate)['day'],
                                                            date_parse($submission->limsSubmittedDate)['year'] ) ) }}}
                    @endif
                </td>
            </tr>
        </tbody>

    </table>


    <div class="item-body">
    @if(count($submission->tests)==0)
        <p>Submission contains no tests.</p>
    @else
        <table class="tests-table">
            <thead>
                <tr>
                    <th scope="col">Test name</th>
                    <th scope="col">Test type</th>
                    <th scope="col">Sample type</th>
                    <th scope="col">Qty</th>
                </tr>
            </thead>
            <tbody>
            @if(is_array($submission->tests))
                @foreach($submission->tests as $testIdx=>$test)
                <tr>
                    <td>
                        {{{ $test->name }}}
                        ({{{ $test->id }}})
                    </td>
                    <td>
                        {{{ $test->type }}}
                    </td>
                    <td>
                        {{{ $test->getSelectedSampleType() }}}
                    </td>
                    <td class="text--center">
                        @if ($test->limsNumberSamples > 0)
                        {{{ $test->limsNumberSamples }}}
                        @endif
                    </td>
                </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    @endif
    </div>
    <div class="item-footer text--right">
        @if (!empty($submission->submissionId))
            <a href="/submission?submissionId={{{ $submission->submissionId }}}">View submission {{{ $submission->submissionId }}}</a>
            @if ($submission->isFOP === true && $submission->isSOP === false)
                <br />
                @if (empty($submission->previousSubmissionId))
                    <a href="/start-paired-submission/{{{ $submission->submissionId }}}">Complete Paired Submission</a>
                @endif
            @endif
        @elseif(!empty($submission->draftSubmissionId))
            <a href="/start/{{{ $submission->draftSubmissionId }}}">Continue draft {{{ $submission->draftSubmissionId }}}</a><br/>
            @if ($submission->limsIsCancelable==true)
                <a class="js-dialog" submission-id="@if (!empty($submission->draftSubmissionId)){{{ $submission->draftSubmissionId }}}@elseif(!empty($submission->submissionId)){{{ $submission->submissionId }}}@endif" popup="cancel-submission" href="/cancel-submission-static/?draftSubmissionId=@if (!empty($submission->draftSubmissionId)){{{ $submission->draftSubmissionId }}}@elseif(!empty($submission->submissionId)){{{ $submission->submissionId }}}@endif">Cancel draft {{{ $submission->draftSubmissionId }}}</a>
            @endif
        @endif
    </div>
</div>