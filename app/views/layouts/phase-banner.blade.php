<div class="phase-banner-beta">
    <p>
        <strong class="phase-tag">BETA</strong>
        <span>
            @if(isset($loggedUser) && !isset($feedbackForm))
                This is a new service – your {{ link_to_route('feedback-form', 'feedback', [], ['class' => 'js-toggle-feedback']) }} will help us to improve it.
            @else
                This is a new service.
            @endif
        </span>
    </p>
    @if(isset($loggedUser) && !isset($feedbackForm))
        <div class="feedback" id="feedback-panel">
        @include('layouts.feedback-form', ['isAjax' => true])
        </div>
    @endif
</div>