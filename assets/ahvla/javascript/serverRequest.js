var serverRequest = {
    timers: {},
    cachedResponses: {},
    currentCallURL: '',
    currentCallParameters: {},

    delay: function (requestId, callback, ms) {
        clearTimeout(serverRequest.timers[requestId]);
        serverRequest.timers[requestId] = setTimeout(callback, ms);
    },

    getCacheKey: function (url, parameters) {
        var parametersAsString = '';
        jQuery.each(parameters, function(value, key){
            parametersAsString = parametersAsString +value + key;
        });
        var cacheKey = encodeURIComponent(url + parametersAsString);
        return cacheKey;
    },

    getCachedResults: function (url, parameters) {
        var foundCachedValue = null;
        var searchCacheKey = serverRequest.getCacheKey(url, parameters);
        jQuery.each(serverRequest.cachedResponses, function (key, value) {
            if (key == searchCacheKey) {
                foundCachedValue = value;
                return false;
            }
        });

        return foundCachedValue;
    },

    setCachedResults: function (url, parameters, data) {
        var cacheKey = serverRequest.getCacheKey(url, parameters);
        serverRequest.cachedResponses[cacheKey] = data;
    },

    loadDivWithResults: function (url, parameters, containerId, resultTemplateClass, callBack, useCache) {
       var cachedResults = serverRequest.getCachedResults(url, parameters);

        if (useCache && cachedResults) {
            serverRequest.populateDiv(cachedResults, containerId, resultTemplateClass, callBack);
        } else {
            serverRequest.showMessage(containerId, 'Loading...');

            serverRequest.delay(
                containerId,
                function () {

                    jQuery.get(url, parameters, function (data,textStatus,jqXHR) {

                        // for session timeout
                        if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                            top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                        }

                        if (useCache) {
                            serverRequest.setCachedResults(url, parameters, data);
                        }

                        serverRequest.populateDiv(data, containerId, resultTemplateClass, callBack);
                    });
                },
                500
            );
        }
    },

    // This version only uses the final latest call
    loadDivWithResultsFinal: function (url, parameters, containerId, resultTemplateClass, callBack, useCache) {
       var cachedResults = serverRequest.getCachedResults(url, parameters);

        // we store the last url and parameter search so we get the last one that gets called
        serverRequest.currentCallURL = url;
        serverRequest.currentCallParameters = parameters;

        if (useCache && cachedResults) {
            serverRequest.populateDiv(cachedResults, containerId, resultTemplateClass, callBack);
        } else {
            serverRequest.showMessage(containerId, 'Loading...');
            serverRequest.delay(
                containerId,
                function () {
                    jQuery.get(url, parameters, function (data,textStatus,jqXHR) {

                        // for session timeout
                        if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                            top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                        }

                        if (    ( url == serverRequest.currentCallURL ) &&
                                ( parameters == serverRequest.currentCallParameters ) )
                                {
                            if (useCache) {
                                serverRequest.setCachedResults(url, parameters, data);
                            }
                            serverRequest.populateDiv(data, containerId, resultTemplateClass, callBack);
                        }
                    });
                },
                500
            );
        }
    },

    // This version only uses the final latest call
    loadDivWithRecommendedTests: function (url, parameters, showContainerId, hideContainerId, callBack, useCache) {

        var showContainer = $('#' + showContainerId);
        var hideContainer = $('#' + hideContainerId);

        hideContainer.hide();

        var cachedResults = serverRequest.getCachedResults(url, parameters);

        // we store the last url and parameter search so we get the last one that gets called
        serverRequest.currentCallURL = url;
        serverRequest.currentCallParameters = parameters;

        if (useCache && cachedResults) {
            showContainer.html(cachedResults);
            showContainer.show();
            if (typeof callBack == "function") {
                callBack(cachedResults);
            }
        } else {
            serverRequest.showMessage(showContainerId, 'Loading...');
            serverRequest.delay(
                //containerId,
                showContainerId,
                function () {
                    jQuery.get(url, parameters, function (data,textStatus,jqXHR) {

                        // for session timeout
                        if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                            top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                        }


                        if (    ( url == serverRequest.currentCallURL ) &&
                            ( parameters == serverRequest.currentCallParameters ) )
                        {
                            if (useCache) {
                                serverRequest.setCachedResults(url, parameters, data);
                            }

                            //serverRequest.populateDiv(data, containerId, resultTemplateClass, callBack);
                            showContainer.html(data);
                            if (typeof callBack == "function") {
                                callBack(data);
                                serverRequest.hideMessage(showContainerId);
                                showContainer.show();
                            }

                        }
                    });
                },
                500
            );
        }
    },

    // Loads results from cache or from server. Optionally caches results.
    loadResults: function (url, parameters, parentContainerId, containerId, species, sampleType, disease, callBack, useCache) {

        var cachedResults = serverRequest.getCachedResults(url, parameters);

        // we store the last url and parameter search so we get the last one that gets called
        serverRequest.currentCallURL = url;
        serverRequest.currentCallParameters = parameters;

        if (useCache && cachedResults) {
            if (typeof callBack == "function") {
                callBack(containerId, cachedResults);
                serverRequest.hideMessage(parentContainerId);
            }
        } else {
            serverRequest.showMessage(parentContainerId, 'Loading...');

            serverRequest.delay(
                containerId,
                function () {
                    jQuery.get(url, parameters, function (data,textStatus,jqXHR) {

                        // for session timeout
                        if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                            top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                        }


                        if (( url == serverRequest.currentCallURL ) &&
                            ( parameters == serverRequest.currentCallParameters ))
                        {
                            if (useCache) {
                                serverRequest.setCachedResults(url, parameters, data);
                            }

                            if (typeof callBack == "function") {
                                //callBack(parentContainerId, containerId, data);
                                callBack(parentContainerId, containerId, data, species, sampleType, disease);
                                serverRequest.hideMessage(parentContainerId);
                            }
                        }
                    });
                },
                500
            );
        }
    },

    populateDiv: function (data, containerId, resultTemplateClass, callBack) {
        if (serverRequest.dataCount(data) == 0) {
            serverRequest.showMessage(containerId, 'No results');
            return false;
        }

        var newContainer = $('#' + containerId).clone();
        var referenceElement = newContainer.find('.' + resultTemplateClass).first();

        // clear all previous values if any
        $(referenceElement).find("input[type='radio']").attr('checked', false);
        $(referenceElement).find("input[type='checkbox']").attr('checked', false);
        $(referenceElement).find("input[type='text']").attr('value', '');

        newContainer.find('.' + resultTemplateClass).remove();
        newContainer.show();

        $.each(data, function (rowKey, rowValue) {

            // we may return meta data in key value pair, so assume that row data keys are always numeric
            if (!isNaN(rowKey)) {
                var newResultElement = htmlTemplate.injectRow(rowValue, referenceElement);
                serverRequest.hideElement(newResultElement);
                $(newResultElement).removeAttr('style');
                newContainer.append($(newResultElement));
            }
        });

        serverRequest.finishLoading(containerId, newContainer.html());

        // for product add basket button
        $('.JSON_id_ELE_COUNT').each(function (i) {
            var columnElement = $(this);
            var count = i+1;
            columnElement.attr('id', 'element_'+count);
        });

        util.bindRadioButtonsSelectedBehaviour();

        if (typeof callBack == "function") {
            callBack(data);
        }
    },

    hideElement: function (element) {
        $(element).removeClass('hidden');
        $(element).show();
    },


    // As we return meta data in the data set, we can't just use .length to check for the number of records returned.
    dataCount: function (data) {
        var numRows = 0;
        $.each(data, function (rowKey, rowValue) {

            // we may return meta data in key value pair, so assume that row data keys are always numeric
            if (!isNaN(rowKey)) {
                // contains real data
                numRows++;
            }
        });

        return numRows;
    },

    showMessage: function (callId, message) {
        $('#' + callId).hide();
        if ($('#' + callId + '_message').length) {
            $('#' + callId + '_message').html(message);
        } else {
            $('#' + callId).after('<div id="' + callId + '_message">' + message + '</div>');
        }
    },

    hideMessage: function (callId) {
        $('#' + callId + '_message').remove();
        $('#' + callId).show();
    },

    finishLoading: function (callId, output) {
        $('#' + callId + '_message').remove();
        $('#' + callId).show().html(output);
    }
}