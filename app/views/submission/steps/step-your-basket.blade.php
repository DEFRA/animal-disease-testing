@extends('layouts.master')
@section('title', 'Step 5 - '.$step5Title.' - '.$submissionTypeName.' Animal Diagnostic Submission -')
@section('head')
    <script>
        $(document).ready(function () {
            ahvlaApp.init('your-basket',{{json_encode($fullSubmissionForm)}});
        });
    </script>
    <script type="text/javascript">
        function fix_header() {
            var window_top = $(window).scrollTop();
            var div_top = $('#fix-anchor').offset().top;
            if (window_top > div_top) {
                $('#fix-header').addClass('fixed');
            } else {
                $('#fix-header').removeClass('fixed');
            }
        }

        $(function () {
            $(window).scroll(fix_header);
            fix_header();
        });
    </script>
@stop

@section('content')

    @include('submission.steps', ['isSOP' => $isSop])
    @include('submission.steps.partials.client-details')

    {{ Form::open(array('url'=>$subUrl->build('step-basket-post'),'class'=>'form step-form','autocomplete'=>'off')) }}
    {{ Form::hidden('timestamp', time() * 1000, ['id' => 'js_timestamp']) }}

    @include('submission.validation.validation')
    <?php ($isSop) ? $stepNumber = '1' : $stepNumber = '5'; ?>
    <h2>{{{$stepNumber.'. '.$step5Title }}}</h2>
    <p>View your selected items.</p>
    <hr class="basket-table__divide"/>

    <div id="NoTestsInBasket" @if(count($basketProducts))style="display: none"@endif>No tests in basket</div>

    <div id="basketContainer" @if(!count($basketProducts))style="display:none"@endif>

    <div id="fix-anchor"></div>
        <div id="fix-header" class="basket-table header">
            <div class="item">
                <p>Tests selected</p>
            </div>
            <div class="animal-id-list">
                <div class="sample-id">
                    <p>Add sample ID<br>(optional)</p>
                </div>
                <div class="quantity">
                    <p>Animal ID</p>
                </div>
                <div class="pool">
                    <p>Pool group - for pooled testing only</p>
                </div>
                <div class="price-col">
                    <p>Price</p>
                </div>
                <div class="remove-animal">
                    <p></p>
                </div>
            </div>
        </div>
        @if(!$basketProducts)
            @include('submission.steps.partials.basket.result-templates.basketProduct-template',
            ['persistence'=>$persistence]
            )
        @else
            @foreach($basketProducts as $index=>$basketProduct)
                @if ($basketProduct->testPackType === 'PACKAGE')
                    <div class="basket-table js-basketProduct package" @if(!isset($basketProduct)) style='display: none' @endif>
                        <div class="package__details">
                            <div class="item">
                                <h3 class="package_name">{{{isset($basketProduct->name)?$basketProduct->name:''}}}</h3>
                                <h4>(<span>{{{isset($basketProduct->id)?$basketProduct->id:''}}}</span>)</h4>
                            </div>
                            <div class="animal-id-list animalSamplesList">
                                @foreach($basketProduct->animalIdsSamples as $animalIdsSample)
                                    @if(!$animalIdsSample->isSOP) {{-- Only display one "Remove" link per animal ID --}}
                                        <span class="product_animal_sample_id">
                                            <div class="sample-id">
                                                <p></p>
                                            </div>
                                            <div class="quantity">
                                                <p>
                                                    <span class="JSON_animalId">{{{$animalIdsSample->animal->description}}}</span>
                                                </p>
                                            </div>
                                            <div class="pool">
                                                <p></p>
                                            </div>
                                            <div class="price-col">
                                                <p>£<span class="JSON_price">{{{$basketProduct->price}}}</span></p>
                                            </div>
                                            <div class="remove-animal">
                                                @if(!isset($readOnlyMode) && count($basketProduct->animalIdsSamples) > 1)
                                                    {{ link_to_route('removeAnimal', 'Remove', array('productId' => $basketProduct->id,'animalId' => $animalIdsSample->animal->id,'draftSubmissionId' => $fullSubmissionForm->draftSubmissionId), array('class'=>'removeAnimalId' , 'id'=>'removeAnimalId_'.(isset($basketProduct)?$basketProduct->id:'').'_'.(isset($animalIdsSample)?$animalIdsSample->animal->id:''))) }}
                                                @endif
                                            </div>
                                        </span>
                                    @endif
                                    <div class="clear"></div>
                                @endforeach
                                <div id="validationGlobalDiv" class="groups__validation">
                                    <p></p>
                                </div>
                            </div>
                            <div class="remove push-half--top">
                                {{Form::hidden('removeProductId'.(isset($basketProduct)?$index:''),(isset($basketProduct)?$basketProduct->id:''),['class'=>'JSON_id'])}}
                                @include('submission.steps.partials.basket.result-templates.product-remove-button')
                            </div>
                        </div>
                        @if (!$isSop && $basketProduct->isPackagePairable())
                            @include('submission.steps.partials.basket.result-templates.paired-options', ['packageId' => (isset($basketProduct->id)?$basketProduct->id:null)])
                        @endif
                        <div class="{{{'package__tests package_tests_'.(isset($basketProduct)?$basketProduct->id:'')}}}">
                            @foreach($basketProduct->constituentTests as $constituentTest)
                                @include('submission.steps.partials.basket.result-templates.basketProductPackage-template',
                                ['basketProduct'=>$constituentTest,'persistence'=>$persistence, 'packageId' => (isset($basketProduct->id)?$basketProduct->id:null)]
                                )
                            @endforeach
                        </div>
                    </div>
                @else
                    @include('submission.steps.partials.basket.result-templates.basketProduct-template',
                    ['basketProduct'=>$basketProduct,'persistence'=>$persistence]
                    )
                @endif
            @endforeach

            <!--  Added SOP Date picker and address includes  -->
            @if($isSop)
                <div class="row">
                    @include('submission.steps.partials.clinical-history.sample-date')
                </div>
                <div class="row">
                    <!-- Include animal address partial here -->
                    @include('submission.steps.partials.basket.result-templates.animals-address')
                    {{Form::submit('Confirm choice',['name'=>'confirm','class'=>'confirm-button js-hidden push--bottom'])}}
                    {{Form::hidden('is_sop','1')}}
                    @include('submission.steps.partials.client-details.edit-animals-address', ['isSop'=>true])
                    @include('submission.steps.partials.basket.result-templates.animal-address-template')
                </div>
            @endif

        @endif
    </div>

    <div class="grid-row">
        <div class="column-half">
            <div class="panel-indent">
                <p>Prices are indicative only - discounts for volume testing may be applied.</p>
            </div>
        </div>
        <div class="column-half">
            <p class="text--right">Subtotal&nbsp;(<span class="basketTotalItemsCount">{{{$basket->getTotalItems()}}}</span>&nbsp;items):&nbsp; &pound;<span class="basketTotalPrice">{{{$basket->getTotalWithoutVat()}}}</span></p>
            <p class="text--right">VAT:&nbsp; &pound;<span class="basketTotalPriceVat">{{{$basket->getTotalVat()}}}</span></p>
            <p class="text--right"><strong>Total:&nbsp;&pound;</strong><strong><span class="basketTotalPriceWithVat">{{{$basket->getTotalWithVat()}}}</span></strong></p>
        </div>
    </div>

    <div class="row">
        <div class="removing-animals">
            <h4>You have selected to remove <span class="totalAnimalsRemoved"></span> animals from your tests, click Update basket to confirm.</h4>
            {{ Form::submit('Update basket',['class'=>'removing-animals__button']) }}
            <p class="removing-animals__status">Removing animals...</p>
        </div>
        {{ Form::submit('Continue',['id'=>'Continue','class'=>'button basket-continue']) }}
        @include('submission.steps.partials.timeout-notification')
        <p>
            {{ Form::submit('Back',['name'=>'gotostep4', 'id'=>'back', 'class'=>'link button-as-link']) }}
        </p>
    </div>

    {{ Form::close() }}
    
@stop