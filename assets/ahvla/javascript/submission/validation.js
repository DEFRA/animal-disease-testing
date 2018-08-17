var validation = {
    formId: null,
    validationFormFields: [],

    init: function (formId, validationFormFields) {
        validation.formId = formId;
        validation.validationFormFields = validationFormFields;

        validation.hookBehaviourToErrorLinks();

        // Set numbers only on inputs with this class
        $('.js-numeric').numericInput();
    },

    formSetForValidation: function () {
        if (validation.formId) {
            return true;
        }
        return false;
    },

    hookBehaviourToErrorLinks: function () {
        $('.validationErrorLink').on('click', function () {
            var errorFieldName = $(this).attr('data-error-field');

            var gotToElement = $('input[name="' + errorFieldName + '"], select[name="' + errorFieldName + '"], textarea[name="' + errorFieldName + '"]');
            if (!gotToElement.length) {
                gotToElement = $('input[data-validation-name="' + errorFieldName + '"]');
            }

            //var last_element = [];
            last_element = gotToElement[gotToElement.length-1];

            if (last_element) {
                $(last_element).focus();
            }

            return false;
        });
    },

    validateField: function (inputString) {
        if (jQuery.inArray(inputString, validation.validationFormFields) != -1) {
            return true;
        } else {
            if (validation.validationFormFields!=undefined) {
                for (i = 0; i < validation.validationFormFields.length; i++) {
                    try {
                        var fieldNameRegex = new RegExp(validation.validationFormFields[i]);
                        if (fieldNameRegex.test(inputString)) {
                            return true;
                        }
                    } catch (e) {
                    }
                }
            }
        }
        return false;
    },

    triggerInputUpdated: function (inputFieldName) {
        if (validation.validateField(inputFieldName)) {
            validation.callServerFormValidation();
        }
    },

    callServerFormValidation: function () {
        if (submission.confirmationSubmissionAttempted) {
            var url = '/api/v1/form/validate/' + validation.formId;
            jQuery.get(url, subParams.build({}), function (data,textStatus,jqXHR) {

                // for session timeout
                if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                    top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                }

                $('.validationErrorLinkContainer:visible').remove();
                $('#validationGlobalDiv').hide();

                //Show new validation errors
                var errorFieldNames = [];
                jQuery.each(data, function (key, error) {
                    errorFieldNames.push(error.formFieldsName[0]);
                    validation.showValidationError(error);
                });

                //Unset errors that are not anymore
                $('.ValidationBoxField').each(function () {
                    var dataFieldName = $(this).attr('data-field-name');
                    if (dataFieldName && jQuery.inArray(dataFieldName, errorFieldNames) === -1) {
                        $(this).removeClass('validation-error-box');
                        $(this).find('.validation-error').hide();
                    }
                });
            });
        }
    },

    showValidationError: function (error) {
        var fieldName = error.formFieldsName[0];

        if (!$('.validationErrorLink[data-error-field="' + fieldName + '"]').length) {
            var validationBox = $('.ValidationBoxField[data-field-name="' + fieldName + '"]');
            validationBox.addClass('validation-error-box');
            validationBox.find('.validation-error')
                .html(error.message).show();

            var newErrorLink = $('.validationErrorLinkContainer').first().clone();
            newErrorLink.find('.validationErrorLink').attr('data-error-field', fieldName);
            newErrorLink.find('.validationErrorLink').attr('href', error.formBaseUrl);
            newErrorLink.find('.validationErrorLink').html(error.message);
            newErrorLink.show();

            $('#validationGlobalDiv .validationList').append(newErrorLink.prop('outerHTML'));
            $('#validationGlobalDiv').show();

            validation.hookBehaviourToErrorLinks();
        }

    },

    isInvalidAge: function(id){
        return !isNaN($(id).val() / 1) == false  || $(id).val() % 1 != 0 || $(id).val() < 0;
    }, 

    addError: function(id){
        $(id).parent().addClass('form-group-error');
        $(id).siblings('.error-message').show()
    },

    removeError: function(id){
        $(id).parent().removeClass('form-group-error');
        $(id).siblings('.error-message').hide()
    }
}
