{{ Form::open(array('name'=>'landing','class'=>'','id'=>'filter-form','autocomplete'=>'off'))  }}
<fieldset>
    <legend>Filter submissions by</legend>
    <div class="row">
        <div class="submissions-filter">
            <div class="group">
                <label for="clientId">Client</label>
            </div>
            <div class="group">
                {{ Form::text('clientId', isset($persistence['clientId'])?$persistence['clientId']:'', ['id'=>'clientId','class'=>'search-field','autocomplete' => 'off']); }}
            </div>
        </div>

        <div class="submissions-filter">
            <div class="group">
                <label for="clinician">Clinician</label>
            </div>
            <div class="group">
                {{ Form::text('clinician', isset($persistence['clinician'])?$persistence['clinician']:'', ['id'=>'clinician','class'=>'search-field','autocomplete' => 'off']); }}
            </div>
        </div>

        <div class="submissions-filter">
            <div class="group">
                <label for="status">Status</label>
            </div>
            <div class="group">
                {{Form::select('status', $filterForm->getStatuses(), isset($persistence['status'])?$persistence['status']:'', ['id'=>'status','class'=>'search-field'])}}
            </div>
        </div>

        <div class="submissions-filter">
            <div class="group">
                <label for="date">Submitted date</label>
            </div>
            <div class="group">
                {{Form::select('date', $filterForm->getFilterDates(), (isset($persistence['date']) && !empty($persistence['date']))?$persistence['date']:$filterForm->getDefaultFilterDate(), ['id'=>'date','class'=>'search-field'])}}
            </div>
        </div>

        <div class="submissions-filter last">
            <div class="group">
                {{ Form::submit('Search',['id'=>'filterSubmission','class'=>'button']) }}
            </div>
        </div>

    </div>
</fieldset>

<br />

{{Form::hidden('page', isset($persistence['page'])?$persistence['page']:1,['id'=>'page','class'=>'search-field'])}}

{{Form::close()}}