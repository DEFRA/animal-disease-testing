var persistentForm = {
    phpFormClassName: undefined,
    saveQueue: [],
    saveQueueTimer: null,

    init: function (phpFormClassName) {
        persistentForm.phpFormClassName = phpFormClassName;

        persistentForm.appendBehaviourToInputs();
        persistentForm.saveQueueTimer = setInterval(persistentForm.processQueue, 100);
    },

    appendBehaviourToInputs: function () {
        $('body').off('change', '.persistentInput');
        $('body').on('change', '.persistentInput', function () {
            persistentForm.saveInput($(this));
        });
    },

    saveInput: function (inputElement) {
        var dbName = inputElement.attr('name');
        if (inputElement.is(':checkbox')) {
            if (inputElement.is(':checked')) {
                var value = 1;
            } else {
                var value = 0;
            }
        }
        else if (inputElement.is(':radio')) {
            if (inputElement.is(':checked')) {
                var value = inputElement.val();
            }
            else {
                var value = '';
            }
        }
        else {
            var value = inputElement.val();
        }
        var obj = {};
        obj[dbName] = value;
        obj['js_timestamp'] = new Date().getTime();
        persistentForm.saveQueue.push(obj);
    },

    processQueue: function () {

        var saveValue = persistentForm.saveQueue.shift();

        if (saveValue != undefined) {

            var name = '';
            var value = '';
            jQuery.each(saveValue, function (iterationKey, iterationValue) { //Workaround so it works in IE
                name = iterationKey;
                value = iterationValue;
                return false; //Gets the first in the queue
            });

            // get the timestamp
            var time = saveValue['js_timestamp'];

            // stop timer
            clearInterval(persistentForm.saveQueueTimer);

            var url = 'api/v1/form-input/'
                + persistentForm.phpFormClassName + '/'
                + name + '?_token='+$("input[name='_token']").val();
            jQuery.post( url, subParams.build({value: value, timestamp: time}), function (data,textStatus,jqXHR) {
                // for session timeout
                if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                    top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                }

            })
                .success(function () {
                    validation.triggerInputUpdated(name);
                })
                .always(function () {
                    persistentForm.saveQueueTimer = setInterval(persistentForm.processQueue, 100);
                }
            )
            ;
        }
    }


}
