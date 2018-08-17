
<?php
$animal_test_qty = 1;
if (isset($persistence->animals_test_qty) && is_numeric($persistence->animals_test_qty)) {
    $animal_test_qty = $persistence->animals_test_qty;
}

$options = [];
for ($i = 1; $i < 51; $i++) {
    $selectOptions[$i] = $i;
}

?>

<div id="animalIdsInput">
    <fieldset>
        <legend class="visuallyhidden">Animal quantities</legend>
        <label for="animals_test_qty" class="legend">Number of animals</label>
        <div class="row">
            {{Form::select('animals_test_qty', $selectOptions, $animal_test_qty, ['id' => 'animals_test_qty','class'=>'persistentInput'])}}
            <input class="confirm-button js-hidden push--left" type="submit" name="refresh" value="Confirm choice">
        </div>
        <div class="row">
            <p>Enter IDs, eg ear tag number (optional).</p>
            <div id="animal_ids_box">
            @if($animal_test_qty===0)
            <?php
                $animal_test_qty = 1;
            ?>
            @endif
            @for($i=0;$i<$animal_test_qty;$i++)
            @include('submission.steps.partials.animal-details.result-templates.animal-id-template',['animalIdIndex'=>$i])
            @endfor
            </div>
        </div>
    </fieldset>
    <hr />
</div>

