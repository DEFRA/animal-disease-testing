<div class="validation-error" @if(!$error)style="display: none"@endif>
    {{{$error?$error->getMessage():''}}}
</div>