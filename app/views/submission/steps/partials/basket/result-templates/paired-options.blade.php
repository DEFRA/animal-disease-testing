@if (isset($packageId))
    <?php $isFOP = 'package_isFOP_'; ?>
    <?php $isSOP = 'package_isSOP_'; ?>
    <?php $isSOPClass = 'isPackageSOPButton'; ?>
    <?php $package = true; ?>
@else
    <?php $isFOP = 'isFOP_'; ?>
    <?php $isSOP = 'isSOP_'; ?>
    <?php $isSOPClass = 'isSOPButton'; ?>
    <?php $package = false; ?>
@endif

<!-- For paired sample types, hide the Paired Sero options if test/package is a SOP submission but is not a FOP i.e. the FOP submission is already done. This essentially equates to simply not displaying the options in the SOP submission basket.-->
<fieldset id="pairedSerologyOptions" class="panel-indent inline push--top {{{(($basketProduct->isPairable() && (($basketProduct->isFOP && $basketProduct->isSOP) || (!$basketProduct->isFOP && !$basketProduct->isSOP) || ($basketProduct->isFOP && !$basketProduct->isSOP))) || ($package)) ? '' : 'hidden' }}}">
    <div class="isFOPSection clear">
        <h5>Paired Serology</h5>
        @if($package)
            <p>The package you have chosen must be used for paired serology testing</p>
        @else
            <p>The sample type you have chosen can be used for paired serology testing. Do you wish to do paired serology testing?</p>
        @endif
        <div class="radioGroup row {{{ ($package) ? 'hidden' : '' }}}">
            <label class="block-label inline pairedSerology">
                @if($package)
                    {{ Form::hidden($isFOP.$basketProduct->id, "true", ['id' => $isFOP.'true_'.$basketProduct->id, 'class' => 'persistentInput isFOPButton']) }}
                @else
                    {{ Form::radio($isFOP.$basketProduct->id, "true", $basketProduct->isFOP, ['id' => $isFOP.'true_'.$basketProduct->id, 'class' => 'persistentInput isFOPButton']) }}
                @endif
                Yes
            </label>
            <label class="block-label inline pairedSerology">
                @if($package)
                    {{ Form::radio($isFOP.$basketProduct->id, "false", false, ['id' => $isFOP.'false_'.$basketProduct->id, 'class' => 'persistentInput isFOPButton']) }}
                @else
                    {{ Form::radio($isFOP.$basketProduct->id, "false", !$basketProduct->isFOP, ['id' => $isFOP.'false_'.$basketProduct->id, 'class' => 'persistentInput isFOPButton']) }}
                @endif
                No
            </label>
            {{Form::submit('Confirm choice',['name'=>'refresh','id'=>'confirm_paired_serology_'.(isset($basketProduct)?$index:''),'class'=>'confirm-button js-hidden push--bottom'])}}
        </div>
    </div>

    <div class="isSOPSection clear {{{ ($basketProduct->isFOP || ($package) ) ? '' : 'hidden' }}}">
        <p>Will the second sample also be sent with this submission?</p>
        <div class="radioGroup row">
            <label class="block-label inline pairedSerology">
                {{ Form::radio($isSOP.$basketProduct->id, "true", $basketProduct->isSOP, ['id' => $isSOP.'true_'.$basketProduct->id, 'class' => 'persistentInput '.$isSOPClass]) }}
                Yes
            </label>
            <label class="block-label inline pairedSerology">
                {{ Form::radio($isSOP.$basketProduct->id, "false", !$basketProduct->isSOP, ['id' => $isSOP.'false_'.$basketProduct->id, 'class' => 'persistentInput '.$isSOPClass]) }}
                No
            </label>
            {{Form::submit('Confirm choice',['name'=>'refresh','id'=>'confirm_paired_serology_fopsop_'.(isset($basketProduct)?$index:''),'class'=>'confirm-button js-hidden push--bottom'])}}
        </div>
    </div>
</fieldset>
