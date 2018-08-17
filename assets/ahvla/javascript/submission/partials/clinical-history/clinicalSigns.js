var clinicalSigns = {

    init: function () {
    	clinicalSigns.toggleSelection();
        clinicalSigns.enterSelection();
    },

    removeSelectedOptionsFromOptions: function (clinicalInputs, options) {
        clinicalInputs.each(function() {
            if($(this).val() !== '') {
                var temp = options.indexOf($(this).val());
                if (temp !== -1) {
                    options.splice(temp, 1);
                }
            }
        });
    },

    // for keyboard
    getSelectedOptions: function (clinicalInputs) {
        var selected_options = [];
        clinicalInputs.each(function() {
            if($(this).val() !== '') {
                selected_options.push($(this).val());
            }
        });
        return selected_options;
    },

    setRemainingOptions: function (selected_options, options) {
        options.length = 0;
        var options_list = ['1', '2', '3'];
        $.each(options_list, function(key, value) {
            if(jQuery.inArray(value, selected_options) === -1) { // if value not in selected_options
                options.push(value);
            }
        });
    },

    toggleSelection: function(){
        var options = ['1', '2', '3'],
            clinicalSign = $('.js-sign-select'),
            clinicalInputs = $('.clinical-signs__input'),
            inputCount = $('.clinical-signs__input').length;

        clinicalSigns.removeSelectedOptionsFromOptions(clinicalInputs, options); // initialise or page refresh

        clinicalSign.on('click', function(e) {
            e.preventDefault();
            var clinicalInput = $(this).closest('.column-third').find('.clinical-signs__input');
            clinicalSigns.storeSelectedSignClick(clinicalInputs, options, clinicalInput);
        });

        $('.clinical-signs__input').on('change', function(e) {
            e.preventDefault();
            var clinicalInput = $(this).closest('.column-third').find('.clinical-signs__input');
            clinicalSigns.storeSelectedSignKeyboard(clinicalInputs, options, clinicalInput);
        });
    },
    
    storeSelectedSignClick: function(clinicalInputs, options, clinicalInput) {
        options.sort(function(a, b) { return a-b; });
        // record selection
        if(clinicalInput.val() === '' && options.length !== 0) {
            clinicalInput.val(options.shift()); // remove first option
            clinicalInput.removeAttr('disabled');
        // All options used up
        } else if(options.length === 0 && clinicalInput.val() === '') {
            clinicalInputs.filter(function() { return $(this).val() === ""; }).attr('disabled', 'disabled');
        // remove option and store removed option
        } else {
            options.unshift(clinicalInput.val());
            clinicalInput.val('');
            clinicalInputs.removeAttr('disabled');
        }
    },

    storeSelectedSignKeyboard: function(clinicalInputs, options, clinicalInput) {
        options.sort(function(a, b) { return a-b; });
        // record selection
        if(options.length !== 0) { // if options still available
            // remove the selected option + reset the options available to use
            clinicalSigns.removeSelectedOptionsFromOptions(clinicalInputs, options);
            selected_options = clinicalSigns.getSelectedOptions(clinicalInputs);
            clinicalSigns.setRemainingOptions(selected_options, options);
            clinicalInput.removeAttr('disabled');
            // if options now used up, disable all unselected sign fields
            if(options.length === 0) {
                clinicalInputs.filter(function () {return $(this).val() === "";}).attr('disabled', 'disabled');
            }
        // All options used up (or option is being removed from max used options)
        } else if(options.length === 0 && clinicalInput.val() === '') {
            selected_options = clinicalSigns.getSelectedOptions(clinicalInputs);
            if (selected_options.length === 3) { // options used up
                clinicalInputs.filter(function () {return $(this).val() === "";}).attr('disabled', 'disabled'); //disable all unselected sign fields
            } else { // removing an option from max used options i.e. removing 1 when 1,2,3 are selected
                clinicalSigns.setRemainingOptions(selected_options, options);
                clinicalInputs.filter(function () {return $(this).val() === "";}).removeAttr('disabled'); // enable all unselected sign fields
            }
        }
    },

    displayErrors: function(errors) {

        if (errors.length === 0) {
            $('.clinical-signs__validation').hide();
        } else {
            $.each(errors, function(key, value) {
                $('<p>' + value + '</p>').appendTo('.clinical-signs__validation');
            });
            $('.clinical-signs__validation').show();
        }
    },

    enterSelection: function(){
        var clinicalInput = $('.clinical-signs__input');

        clinicalInput.keyup(function () {

            var errors = [];

            $('.clinical-signs__validation').hide();
            $('.clinical-signs__validation').empty();

            var signValue = $(this).val();
            if(! (signValue === '' || signValue === '1' || signValue === '2' || signValue === '3') ){
                errors.push('Only values 1, 2 or 3 allowed.');
                $(this).val('');
                clinicalSigns.displayErrors(errors);
                return;
            }

            // find duplicates
            var optionss = [];
            clinicalInput.each(function() {
                if($(this).val() !== '') {
                    optionss.push($(this).val());
                }
            });

            var duplicates = !optionss.every(function(v,i) {
                return optionss.indexOf(v) == i;
            });

            if(duplicates){
                errors.push('Values entered must be unique');
                $(this).val('');
                clinicalSigns.displayErrors(errors);
                return;
            }
        });
    }
}