<fieldset id="speciesSelection" class="inline">
    <legend>Have you contacted us about the same case?</legend>
    <div class="row">
        <label class="block-label" data-target="example-ni-number" for="contact-recent-1">
            {{Form::radio('clinical_history_same_case','0',isset($persistence)?($persistence->clinical_history_same_case=='0'):'',['id'=>'contact-recent-1','class'=>'persistentInput']);}}
            No
        </label>

        <label class="block-label" for="contact-recent-2">
            {{Form::radio('clinical_history_same_case','1',isset($persistence)?($persistence->clinical_history_same_case=='1'):'',['id'=>'contact-recent-2','class'=>'persistentInput']);}}
            Yes
        </label>
    </div>

    <div class="row panel-indent flush--top" id="submissionSearch">

        <label for="previous_submission_ref">Type the previous submission reference (optional)</label>

        <div>
            <input autocomplete="off" class="persistentInput" placeholder="start typing" type="text" id="previous_submission_ref" name="previous_submission_ref" value="{{{isset($persistence)?$persistence->previous_submission_ref:''}}}">
            <input class="search-button js-hidden" type="submit" name="refresh" value="Search">
        </div>

        <div class="" id="submissionSearchResults">

            @if(!count($submission_list))
                @include('submission.steps.partials.clinical-history.result-templates.submission-template')
            @endif

            @foreach($submission_list as $submission)
                @include('submission.steps.partials.clinical-history.result-templates.submission-template', ['submission'=>$submission])
            @endforeach

        </div>

    </div>
</fieldset>
<hr />