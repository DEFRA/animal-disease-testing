<div id="organicEnvSelection">
  <fieldset class="inline">
      <legend>Is the farm accredited organic? (optional)</legend>
      <?php
        $radioGroupData = [
          'name'=> 'organic_environment',
          'radios' => $organicEnvironment,
          'checked'=>$persistence->organic_environment
        ];
      ?>
      @include('submission.inputs.radiogroup', $radioGroupData)
  </fieldset>
  <hr />
</div>