<div class="{{{$searchResultsRefDiv}}} clientSearchResult" @if(!isset($client))style="display: none"@endif>
    <label for="{{{$address.'_radio_'.(isset($client)?$client->uniqId:'').'_'.$client_no}}}" class="block-label-client">

        <div class="client-radio">
            {{Form::radio(
            $address,
            isset($client)?$client->uniqId:'',
            isset($client)?($client->uniqId==$persistence->{$edited_name_id}):'',
            ['id'=>$address.'_radio_'. (isset($client)?$client->uniqId:'') .'_'.$client_no, 'row_id'=>isset($client)?preg_replace("/[^a-zA-Z0-9]+/", "", $client->uniqId):'','class'=>'access-hide JSON_uniqId radio client-address-record persistentInput']
            )}}
        </div>

        <div class="client-block">
            <div><span class="JSON_name">{{{isset($client)?$client->name:''}}}</span></div>
            <div><span class="JSON_addressConcatenated">{{{(isset($client))?$client->addressConcatenated:''}}}</span></div>
            <div><span class="JSON_cphh">{{{isset($client)?$client->cphh:''}}}</span></div>
            <input type="hidden" name="JSON_uniqId" class="JSON_uniqId" {{{isset($client)?'id="'.$client->uniqId.'"':''}}} value="{{{isset($client)?$client->uniqId:''}}}">
            {{--eastings and northings of client location--}}
        </div>

        <div class="client-edit">
            <input id="location{{{isset($client)?preg_replace("/[^a-zA-Z0-9]+/", "", $client->uniqId):'_'.$address}}}"
                   type="hidden" class="JSON_location" value="{{{isset($client)?$client->location:''}}}">

                {{Form::submit('Edit',
                    [
                    'id'=>$editButton.'_'.(isset($client)?$client->uniqId:''),
                    'class'=>'button '.$editButton,
                    'name'=>$editButton.'_'.(isset($client)?base64_encode($client->uniqId):''),
                    'data-uniqId'=>(isset($client)?htmlspecialchars($client->uniqId):'')
                    ]
                )}}

        </div>

    </label>
    <script>
        $(document).ready(function () {
            $('input').click(function () {
                $('input:not(:checked)').parent().removeClass("checked");
                $('input:checked').parent().addClass("checked");
            });
        });
    </script>
</div>