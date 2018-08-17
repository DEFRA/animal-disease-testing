<fieldset>
	<legend class="visuallyhidden">Clinical history</legend>
	<label for="written_clinical_history" class="legend">Written clinical history (optional):</label>

	{{ Form::textarea('written_clinical_history', $persistence->written_clinical_history, ['id'=>'written_clinical_history', 'class'=>'persistentInput','autocomplete' => 'off']); }}

	<div id="written_clinical_history_count"><span class="js-hide">Maximum 1000 characters</span></div>

</fieldset>
<hr />