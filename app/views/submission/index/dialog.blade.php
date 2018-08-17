<div id="cancel-submission" aria-labelledby="dialog-title">
	<div class="dialog-inner">
		<h3 id="dialog-title" class="flush--top">Cancel submission</h3>

		<p>
		    All submission details will be lost.<br />
		    Are you sure you want to cancel?
		<p>

		{{Form::open(['url' => 'cancel-submission', 'class' => 'idp-option', 'autocomplete' => 'off', 'method' => 'post'])}}
			<button title="Cancel Submission" name="cancel-submission" value="cancel-submission" class="button" type="submit">Yes, cancel this submission</button>
			<input name="submission-id" value="" type="hidden">
		{{Form::close()}}

		<a href="javascript:void(0);" class="js-dialog-close">No, continue with submission</a>

	</div>
</div>