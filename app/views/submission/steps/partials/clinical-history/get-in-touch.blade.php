<div id="howGetInTouch">
    <fieldset class="inline">
        <legend>How did you get in touch?</legend>
        <label class="block-label" for="get-in-touch-phone">
            {{Form::checkbox('get_in_touch_phone', '1',$persistence->get_in_touch_phone,['class'=>'persistentInput','id'=>'get-in-touch-phone']);}}
            By phone
        </label>

        <label class="block-label" for="get-in-touch-farm-visit">
            {{Form::checkbox('get_in_touch_farm_visit', '1',$persistence->get_in_touch_farm_visit,['class'=>'persistentInput','id'=>'get-in-touch-farm-visit']);}}
            During an APHA farm visit
        </label>
    </fieldset>
    <hr />
</div>