var util = {
    init: function(){
        this.jsToggle();
        this.removeNoJS();
        this.navKeyboardAccessible();
        if (navigator.userAgent.indexOf('MSIE 8.0') !== -1) {
        }else{
            if($('.js-date').length > 0) {
                this.datePicker();
            }
        }
        $(".js-cphh").inputmask({mask: "99/999/9999"}); 
    },
    hide: function (id) {
        $('#' + id).addClass('hidden');
        $('#' + id).find('*').attr('disabled', true);
    },

    show: function (id) {
        $('#' + id).removeClass('hidden');
        $('#' + id).find('*').attr('disabled', false);
    },

    jsHide: function (id) {
        $('#' + id).addClass('js-hidden');
        $('#' + id).find('*').attr('disabled', true);
    },

    jsShow: function (id) {
        $('#' + id).removeClass('js-hidden');
        $('#' + id).find('*').attr('disabled', false);
    },

    hideDropDown: function (ref, results) {

        $(document).on("click", "." + ref, function () {

            // deselect existing selections
            $('.' + ref).removeClass("selected");
            $('.' + ref).removeClass("current");

            // color current selection
            var search_mode_client = $('#search_mode_client').val();
            var search_mode_animal = $('#search_mode_animal').val();
            $(this).addClass("selected");
            if (search_mode_client === "clientCPHSearch") {
                $(this).find('.client-edit .editClientButton').css('display','none');
            } else {
                $(this).find('.client-edit .editClientButton').css('display','block');
            }
            if (search_mode_animal === "animalCPHSearch") {
                $(this).find('.client-edit .editAnimalsAddressButton').css('display','none');
            } else {
                $(this).find('.client-edit .editAnimalsAddressButton').css('display','block');
            }

            // select the current radio
            var radio = $(this).find('input[type=radio]');
            radio.prop("checked", true);

            // persist the data
            persistentForm.saveInput(radio);
        });

        // hide radio previous submission selection
        // $('.access-hide').css('display','none');

        // select current selection
        current_selection = $('#' + results).find('input[type=radio]:checked').closest('.' + ref);
        current_selection.addClass("current");

        // hide edit button for cph search results
        var search_mode_client = $('#search_mode_client').val();
        var search_mode_animal = $('#search_mode_animal').val();
        if (search_mode_client === "clientCPHSearch") {
            current_selection_id = current_selection.find('.editClientButton').attr('id');
            $('#'+current_selection_id).css('display','none');
        }
        if (search_mode_animal === "animalCPHSearch") {
            current_selection_id = current_selection.find('.editAnimalsAddressButton').attr('id');
            $('#'+current_selection_id).css('display','none');
        }
    },

    guid: function () {
        var d = new Date().getTime();
        var uuid = 'xxxxxxxxxxxx4xxxyxxxxxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = (d + Math.random() * 16) % 16 | 0;
            d = Math.floor(d / 16);
            return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
        return uuid;
    },

    disableElementFormEnter: function () {

        $(document).keypress(
            function (event) {
                if (event.target.tagName != "TEXTAREA") {
                    if (event.which == '13') {
                        event.preventDefault();
                    }
                }
            }
            );
    },

    disableElementFormEnterSearchBox: function () {

        $(document).on('keyup keypress', 'form input[type="text"]', function (e) {
            if (e.which == 13) {
                e.preventDefault();
                return false;
            }
        });
    },

    bindStepButtonClickEvent: function () {
        $('.steps-bar li a').on('click', function () {

            // Store the link href in a hidden input inside the form
            if (!($('#link-to-step').length)) {
                $('<input>').attr({
                    type: 'hidden',
                    id: 'link-to-step',
                    name: 'link-to-step',
                    value: ($(this).attr('href').charAt(0) == '/') ? $(this).attr('href').substring(1) : $(this).attr('href')
                }).prependTo('form.step-form');
            }

            // Submit the form
            $('form.step-form').submit();

            // Cancel the link
            return false;
        });

    },

    bindTimeUpdateToStepSubmit: function () {
        $('.step-form').submit(function (e) {
            // store the latest utc timestamp
            $('#js_timestamp').val(new Date().getTime());
        });
    },

    bindRadioButtonsSelectedBehaviour: function () {
        $('.block-label').find('input[type="radio"], input[type="checkbox"]').each(function () {
            if ($(this).is(':checked')) {
                $(this).closest('.block-label').addClass('selectedOption');
            }else {
                $(this).closest('.block-label').removeClass('selectedOption');
            }
        });


        //Radios
        $('.block-label').find('input[type="radio"]').click(function () {
            if ($(this).is(':checked')) {
                var radioGroupName = $(this).attr('name');
                $('input[name="'+radioGroupName+'"]').closest('.block-label').removeClass('selectedOption');
                $(this).closest('.block-label').addClass('selectedOption');
            }
        });

        //Checkboxes
        $('.block-label').find('input[type="checkbox"]').click(function () {
            var closestBlockLabel = $(this).closest('.block-label');
            if ($(this).is(':checked')) {
                closestBlockLabel.addClass('selectedOption');
            } else {
                closestBlockLabel.removeClass('selectedOption');
            }
        });


        //Client Selection Radios
        $('.block-label-client').find('input[type="radio"]').each(function () {
            if ($(this).is(':checked')) {
                var blockLabelClient = $(this).closest('.block-label-client');
                blockLabelClient.css( "background-color", "#ffffff");
                blockLabelClient.css( "border", "solid 1px #000000");
            }
        });

        $('.block-label-client').find('input[type="radio"]').click(function () {
            if ($(this).is(':checked')) {

                var parent = $(this).parents().find('.block-label-parent');

                parent.find('.block-label-client').css( "background-color", "");
                parent.find('.block-label-client').css( "border", "");


                var blockLabelClient = $(this).closest('.block-label-client');
                blockLabelClient.css( "background-color", "#ffffff");
                blockLabelClient.css( "border", "solid 1px #000000");
            }
        });
    },

    // Update a dropdown with some options
    updateDropdownOptions: function (parentContainerId, containerId, selectValues) {
        var output = [];
        $.each(selectValues, function(key, value)
        {
            output.push('<option value="'+ key +'">'+ value +'</option>');
        });
        $('#' + containerId).html(output.join('')).val('');
        $('#' + parentContainerId).removeClass('hidden');
    },

    // Reusable details (hide/show) component
    jsToggle: function(){
        $('.js-toggle').click(function(){
            var target = $(this).data('target');
            var targetElement = $('#'+target);
            if (targetElement.hasClass('hidden')) {
                targetElement.removeClass('hidden');
            } else {
                targetElement.addClass('hidden');
            }
            return false;
        });
    },

    removeNoJS: function(){
        $('body').removeClass('no-js');
    },

    datePicker: function(){
        var dateFormat = $('#year').val();
        var dateFormat = $.datepicker.formatDate('dd MM, yy', new Date(dateFormat));

        if($('#year').val().length === 0){
            $('#year').val('');
        }else {
            $('#year').val(dateFormat);
        }

        var input = $('.datepicker');
        this.startDatePicker();
    },

    startDatePicker: function() {
        $('.js-date').pickadate({
            formatSubmit: 'yyyy-mm-dd',
            hiddenPrefix: 'convert__',
            hiddenSuffix: '__convert',
            hiddenName: true,
            closeOnSelect: true,
            closeOnClear: true
        });
    },

    navKeyboardAccessible: function() {
        $('.account-management__menu a').focus(function() {
            $('.account-management__list').addClass('access');
        });

        $('.account-management__item a').last().blur(function() {
            $('.account-management__list').removeClass('access');
        });
    }
};

$(document).ready(function () {
    util.init();
});
