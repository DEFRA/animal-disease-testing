var clientEditor = {

    init: function () {
        $('.js-addClient').click(function () {

            var addressType = this.id;

            if (addressType === 'add-new-animal-address') {
                var url = 'api/v1/pvs-animals-address/new';
            } else {
                var url = 'api/v1/pvs-client/new';
            }

            jQuery.post(url+ '?_token='+$("input[name='_token']").val(),subParams.build({}), function (data,textStatus,jqXHR) {

                // for session timeout
                if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                    top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                }

                if (data.result) {
                    if (addressType === 'add-new-animal-address') {
                        $('#animalSearchResults').hide();
                        $('.js-searchAnimalsButton').val('Cancel');
                        $('input[name="animal_farm"]').val('');
                        $('input[name="animal_address1"]').val('');
                        $('input[name="animal_address2"]').val('');
                        $('input[name="animal_address3"]').val('');
                        $('input[name="animal_address4"]').val('');
                        $('input[name="animal_address5"]').val('');
                        $('input[name="animal_county"]').removeAttr('selected').find('option:first').attr('selected', 'selected');
                        $('input[name="animal_postcode"]').val('');
                        $('input[name="animal_cphh"]').val('');

                        $('#animalAddressSearchModeBox').hide();
                        $('#animalSearchResults').detach().appendTo('#animal-cphh-input');
                        $('#editAnimalAddressModeBox').show();
                    } else {
                        $('#clientSearchResults').hide();
                        $('.js-searchClientsButton').val('Cancel');
                        $('input[name="edited_client_name"]').val('');
                        $('input[name="edited_client_address_line1"]').val('');
                        $('input[name="edited_client_address_line2"]').val('');
                        $('input[name="edited_client_address_line3"]').val('');
                        $('input[name="edited_client_address_line4"]').val('');
                        $('input[name="edited_client_address_line5"]').val('');
                        $('input[name="edited_client_address_line6"]').removeAttr('selected').find('option:first').attr('selected', 'selected');
                        $('input[name="edited_client_address_line7"]').val('');
                        $('input[name="edited_client_cphh"]').val('');

                        $('#client-cphh-input').show();
                        $('#clientSearchModeBox').hide();
                        $('#clientSearchResults').detach().appendTo('#client-cphh-input');
                        $('#editClientModeBox').show();
                    }

                    validation.callServerFormValidation();
                }
            });

            return false;
        });

        $('.js-searchClientsButton, .js-searchAnimalsButton').click(function () {

            var cancelType = this.id;
            if (cancelType === 'cancel-client') {
                $('#search_mode_client').val('');
            }
            if (cancelType === 'cancel-animals') {
                $('#search_mode_animal').val('');
            }
            clientEditor.unsetClient(
                function (data) {
                    if (cancelType === 'cancel-animals') {
                        $('#animalAddressSearchModeBox').show();
                        $('#editAnimalAddressModeBox').hide();
                        $('#animalSearchResults').detach().appendTo('#animalSearchResultsContainer');
                        $('#animalsSearchBox').trigger('keyup')
                    } else {
                        $('#clientSearchModeBox').show();
                        $('#editClientModeBox').hide();
                        $('#clientSearchResults').detach().appendTo('#clientSearchResultsContainer');
                        $('#clientSearchBox').trigger('keyup');
                    }
                    validation.callServerFormValidation();
                }, cancelType);

            return false;
        });

        clientEditor.hookEditClientButtonsBehave();
        clientEditor.hookEditAnimalsAddressButtonsBehave();

    },

    unsetClient: function (callback,cancelType) {

        url = 'api/v1/pvs-client/unset';

        if (cancelType === 'cancel-animals') {
            url = 'api/v1/pvs-animals-address/unset';
        }

        jQuery.post(url+ '?_token='+$("input[name='_token']").val(),subParams.build({}), function (data,textStatus,jqXHR) {

            // for session timeout
            if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
            }

            if (typeof callback == "function") {
                callback(data);
            }
        });
    },

    hookEditClientButtonsBehave: function () {
        $('.editClientButton').unbind('click').bind('click', function () {
            var clientRowDiv = $(this).closest('.clientSearchResult');
            var cphh = clientRowDiv.find('.JSON_uniqId').val();

            jQuery.post('api/v1/pvs-client/set'+ '?_token='+$("input[name='_token']").val(), subParams.build({cphh: cphh}), function (data,textStatus,jqXHR) {

                // for session timeout
                if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                    top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                }

                if (data.result && data.client) {
                    $('.js-searchClientsButton').val('Cancel');
                    $('input[name="edited_client_name"]').val(data.client.name);
                    $('input[name="edited_client_address_line1"]').val(data.client.address.line1);
                    $('input[name="edited_client_address_line2"]').val(data.client.address.line2);
                    $('input[name="edited_client_address_line3"]').val(data.client.address.line3);
                    $('input[name="edited_client_address_line4"]').val(data.client.address.line4);
                    $('input[name="edited_client_address_line5"]').val(data.client.address.line5);
                    $('select[name="edited_client_address_line6"]').val(data.client.address.line6);
                    $('input[name="edited_client_address_line7"]').val(data.client.address.line7);
                    $('input[name="edited_client_cphh"]').val(data.client.cphh);

                    var clientSelectorDiv = $('#' + data.client.uniqId ).closest('.clientSearchResultRefDiv');
                    clientSelectorDiv.click();

                    $('#client-cphh-input').hide();

                    validation.callServerFormValidation();

                    setTimeout(function(){
                        $('#clientSearchModeBox').hide();
                        $('#editClientModeBox').show();
                    }, 250);

                }
            });

            return false;
        });
    },

    hookEditAnimalsAddressButtonsBehave: function () {
        $('.editAnimalsAddressButton').unbind('click').bind('click', function () {
            var clientRowDiv = $(this).closest('.clientSearchResult');
            var cphh = clientRowDiv.find('.JSON_uniqId').val();

            jQuery.post('api/v1/pvs-animals-address/set'+ '?_token='+$("input[name='_token']").val(), subParams.build({cphh: cphh}), function (data,textStatus,jqXHR) {

                // for session timeout
                if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                    top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                }

                if (data.result && data.client) {
                    //$('.js-searchClientsButton').val('Cancel');
                    $('input[name="animal_farm"]').val(data.client.address.line1);
                    $('input[name="animal_address1"]').val(data.client.address.line2);
                    $('input[name="animal_address2"]').val(data.client.address.line3);
                    $('input[name="animal_address3"]').val(data.client.address.line4);
                    //$('input[name="animal_address4"]').val(data.client.address.line5);
                    $('select[name="animal_county"]').val(data.client.address.line6);
                    $('input[name="animal_postcode"]').val(data.client.address.line7);
                    $('input[name="animal_cphh"]').val(data.client.cphh);

                    var clientSelectorDiv = $('#' + data.client.uniqId ).closest('.animalsSearchResultRefDiv ');
                    clientSelectorDiv.click();

                    $('#animal-cphh-input').hide();

                    validation.callServerFormValidation();

                    setTimeout(function(){
                        $('#animalAddressSearchModeBox').hide();
                        $('#editAnimalAddressModeBox').show();
                    }, 250);

                }
            });

            return false;
        });
    }
}