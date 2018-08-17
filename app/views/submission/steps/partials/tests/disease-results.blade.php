<div id="diseaseSelection" @if(!count($diseaseList))style="display:none;" @endif>
    <fieldset class="inline">
        <legend>Select disease or clinical sign for test recommendations</legend>
        <div class="row" id="diseaseSelectionResults">
            @if(!count($diseaseList))
                @include('submission.steps.partials.tests.result-templates.disease-template')
            @endif
            @foreach($diseaseList as $id=>$disease)
                @include('submission.steps.partials.tests.result-templates.disease-template', ['id'=>$id,'disease'=>$disease])
            @endforeach
            <div class="clear"></div>
            <input class="confirm-button js-hidden" type="submit" name="refresh" value="Confirm choice">
        </div>
    </fieldset>
    <hr />
</div>

@if ($persistence->disease)
<div id="sample_type_container">
@else
<div id="sample_type_container" class="hidden">
@endif
    <fieldset class="inline">
        <legend class="visuallyhidden">Filter recommendations by sample type</legend>
        <label for="sample_type" class="legend">Filter recommendations by sample type</label>
        {{Form::select(
            'sample_type',
            $test_recommended_sample_types_list,
            $persistence->sample_type?$persistence->sample_type:$selectedSampleType,
            ['id'=>'sample_type','class'=>'persistentInput']
        )}}
        <input class="search-button js-hidden" type="submit" name="refresh" value="Refresh">
    </fieldset>
    <hr>
</div>