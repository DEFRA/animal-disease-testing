<div class="radioGroup row">
	@foreach($radios as $radioValue=>$radioLabel) 


    <label class="block-label">
        {{Form::radio($name,$radioValue,$radioValue==$checked?true:false, ['id'=>'value_'.$radioValue, 'class'=>'persistentInput'])}}{{$radioLabel}}
    </label>
	@endforeach

	@if(isset($refresh_button) && $refresh_button == true)
		<input class="confirm-button js-hidden push--bottom" type="submit" name="refresh" value="Confirm choice">
	@endif
</div>