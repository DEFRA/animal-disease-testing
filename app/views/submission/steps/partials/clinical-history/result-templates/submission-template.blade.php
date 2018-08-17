<div class="submissionSearchResultRefDiv submissionSearchResult" @if(!isset($submission))style="display: none"@endif>

    <?php

        $prevSubmissionId = '';

        if (!empty($submission->submissionId)) {
            $prevSubmissionId = $submission->submissionId;
        }
        elseif (!empty($submission->draftSubmissionId)) {
            $prevSubmissionId = $submission->draftSubmissionId;
        }

    ?>

    <label for="{{{$prevSubmissionId}}}">
        {{Form::radio(
            'previous_submission_selection',
            $prevSubmissionId,
            ($prevSubmissionId==$persistence->previous_submission_selection) ? true:false,
            ['class'=>'access-hide previous-submission-record persistentInput JSON_masterSubmissionId','id' => $prevSubmissionId]
        )}}}
        <span class="JSON_masterSubmissionId">
            @if(!empty($submission->submissionId))
                {{{$submission->submissionId}}}
            @elseif(!empty($submission->draftSubmissionId))
                {{{$submission->draftSubmissionId}}}
            @endif
        </span>
    </label>
</div>
