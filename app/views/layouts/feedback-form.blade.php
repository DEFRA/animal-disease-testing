{{ Form::open(['name' => 'feedback-form', 'class' => 'form', 'autocomplete' => 'off', 'url' => URL::route('feedback', [], false), 'method' => 'POST'])  }}

<fieldset class="flush--ends">
    <legend class="visuallyhidden">Feedback</legend>

    @if ($errors->count())
        <div class="validation-summary group" role="alert">
            <h2 class="error-heading">There was a problem submitting the form</h2>
            <p>Because of the following problems:</p>

            <ul class="error-list">
                @foreach ($errors->getMessageBag()->getMessages() as $id => $messageArr)
                    <li>
                        @if ($id == 'login_result')
                            {{{ $messageArr[0] }}}
                        @else
                            <a href="#{{{ $id }}}">{{{ $messageArr[0] }}}</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div @if (!$errors->first('feedback')) class="row" @else class="row validation" @endif>
        {{ Form::label('feedback-msg', 'Let us know how we can improve the service:') }}
        @if ($errors->first('feedback'))
            <span class="validation-message">{{{ $errors->first('feedback') }}}</span>
        @endif
        <div>
            {{ Form::textarea('feedback-msg', '', ['id' => 'feedback-msg', 'autocomplete' => 'off']) }}
        </div>
    </div>

    {{ Form::hidden('redirect-to', \URL::previous()) }}

    <div class="row">
        {{ Form::hidden('page-title', '', ['id' => 'page-title']) }}
        <button name="send-feedback" class="button flush--bottom" type="submit">Send feedback</button>
    </div>

</fieldset>

{{ Form::close() }}