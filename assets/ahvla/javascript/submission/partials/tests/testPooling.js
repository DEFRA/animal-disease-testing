var testPooling = {

    init: function () {
        testPooling.hookChangePoolGroups();
        testPooling.hookUpdatePoolGroups();
    },

    hookChangePoolGroups: function () {
        $('[data-id="js-singleTest"]').find('select').on('change', function () {
            var count           = 0, // Must be 0 
                testItem        = $(this).closest('[data-id="js-singleTest"]'),
                sampleId        = $(this).val(),
                productId       = testItem.find('.JSON_id').val(),
                poolInputs      = testItem.find('.poolId'),
                maxPoolI        = testItem.find('.maxPoolI'),
                animalPrice     = testItem.find('.price-col p'),
                pooledPrice     = testItem.find('.price-col'),
                updating        = testItem.find('.updating-basket'),
                groupVals       = [],
                groups          = testItem.find('.groups'),
                groupRow        = testItem.find('.groups__row--groups'),
                groupTotal      = testItem.find('groups__totalprice'),
                url             = 'api/v1/basket-product/' + productId + '?_token='+$("input[name='_token']").val();

            updating.show();

            jQuery.post(url, subParams.build({}), function (data,textStatus,jqXHR) {
                var testPrice   = data.product.price;
                if (sampleId) {
                    var sample      = getData(sampleId),
                        isPooled    = sample.isPooled,
                        maxPool     = sample.maxPool;
                }
                // for session timeout
                if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                    top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                }

                updating.hide();

                function getData(sampleId) {

                    var sampleTypes = data.product.rawSampleTypesArray;
                    
                    for (var i=0; i < sampleTypes.length; i++){
                        if (sampleTypes[i].sampleId == sampleId) {
                        return sampleTypes[i];
                        }
                    }
                    return false;
                }

                if(isPooled === true) {
                    poolInputs.prop("disabled", false).addClass('poolActive');
                    poolInputs.removeClass('non-pooled-sample');

                    pooledPrice.addClass('pooled');
                    
                    groups.find('.groups__row').remove();

                    poolInputs.each(function( index ) {
                        if (index % maxPool === 0) {
                            count = count+1;
                        }
                        $(this).val(count);
                    });
                    
                    animalPrice.each(function( index ) {
                      $(this).html('');
                    });
                    
                    poolInputs.each(function(index, item) {
                        groupVals.push($(item).val());
                    });

                    groupVals.sort();

                    var uniqueGroups = $.unique(groupVals).length;

                    for(i=0; i<uniqueGroups; i++){
                        var gCount = i+1;
                        var html = '<div class="groups__row"><div class="groups__group"><p>Pool group <span>' + gCount + '</span></p></div><div class="groups__price"><p>£<span class="JSON_price">' + testPrice + '</span></p></div></div>';
                        groupRow.append(html);
                    }

                    var totalPrice = (testPrice) * (uniqueGroups);
                        
                    groups.find('.groups__totalprice').html('<p>£' + totalPrice.toFixed(2) + '</p>');
                    
                    groups.show();

                    maxPoolI.html('(Max pool: ' + maxPool + ')').show();

                    basket.updateBasketState();
                } else {
                    poolInputs.prop("disabled", true).val('');
                    poolInputs.addClass('non-pooled-sample');

                    pooledPrice.removeClass('pooled');

                    maxPoolI.html('').hide();
                    
                    if(animalPrice.html() === ''){
                        animalPrice.each(function( index ) {
                            $(this).append('£<span class="JSON_price">' + testPrice + '</span>');
                        });
                    }

                    groups.hide();
                    
                    groups.find('.groups__row').remove();

                    basket.updateBasketState();
                }
            });

            return false;
        });
    },

    hookUpdatePoolGroups: function () {
        $('[data-id="js-singleTest"]').find('.poolId').keyup(function () {
            var testItem        = $(this).closest('[data-id="js-singleTest"]'),
                updating        = testItem.find('.updating-basket'),
                productId       = testItem.find('.JSON_id').val(),
                poolInputs      = testItem.find('.poolId'),
                groups          = testItem.find('.groups'),
                groupRow        = testItem.find('.groups__row--groups'),
                groupTotal      = testItem.find('.groups__totalprice'),
                groupValidation = testItem.find('.groups__validation'),
                url             = 'api/v1/basket-product/' + productId + '?_token='+$("input[name='_token']").val();

            if (testItem.find('.JSON_sampleType .persistentInput').length > 0){
                var sampleId = testItem.find('select').val();
            }else {
                var sampleId = testItem.find('.JSON_sampleType p').attr('id');
            }

            if(!isNaN($(this).val() / 1) == false  || $(this).val().length === 0 ){
                groupValidation.html('<p>Please enter groups as numbers only e.g. 1,2,3</p>').show();
            }else{
                groupValidation.hide();

                updating.show();

                jQuery.post(url, subParams.build({}), function (data,textStatus,jqXHR) {
                    var groupVals = [],
                        testPrice   = data.product.price;
                    // for session timeout
                    if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                        top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                    }

                    if (sampleId) {
                        var sample      = getData(sampleId),
                            isPooled    = sample.isPooled,
                            maxPool     = sample.maxPool;
                    }

                    updating.hide();

                    function getData(sampleId) {
                        var sampleTypes = data.product.rawSampleTypesArray;
                        for (var i=0; i < sampleTypes.length; i++){
                            if (sampleTypes[i].sampleId == sampleId) {
                            return sampleTypes[i];
                            }
                        }
                        return false;
                    }

                    poolInputs.each(function(index, item) {
                        groupVals.push($(item).val());
                    });

                    groupVals.sort();

                    groupVals = groupVals.filter(String); // remove any blank entries (blanks occur when animal qty increased after tests added to basket)

                    var uniqueGroups = $.unique(groupVals).length;

                    groups.find('.groups__row').remove();

                    for(i=0; i<uniqueGroups; i++){
                        var gCount = i+1;
                        var html = '<div class="groups__row"><div class="groups__group"><p>Pool group <span>' + gCount + '</span></p></div><div class="groups__price"><p>£<span class="JSON_price">' + testPrice + '</span></p></div></div>';
                        groupRow.append(html);
                    }

                    var totalPrice = (testPrice) * (uniqueGroups);
                        
                    groups.find('.groups__totalprice').html('<p>£' + totalPrice.toFixed(2) + '</p>');

                    groups.show();

                    basket.updateBasketState();

                });

                return false;
            }
        });
    },

    hookRemovePoolGroups: function (removeRelated) {
        var count           = 0, // Must be 0
            updating        = removeRelated.find('.updating-basket'),
            productId       = removeRelated.find('.JSON_id').val(),
            poolInputs      = removeRelated.find('.poolActive'),
            animalPrice     = removeRelated.find('.price-col p'),
            groupVals       = [],
            groups          = removeRelated.find('.groups'),
            groupRow        = removeRelated.find('.groups__row--groups'),
            groupTotal      = removeRelated.find('groups__totalprice'),
            url             = 'api/v1/basket-product/' + productId + '?_token='+$("input[name='_token']").val();

        if (removeRelated.find('.JSON_sampleType .persistentInput').length > 0){
            var sampleId = removeRelated.find('select').val();
        }else {
            var sampleId = removeRelated.find('.JSON_sampleType p').attr('id');
        }

        updating.show();

        jQuery.get(url, subParams.build({}), function (data,textStatus,jqXHR) {
            // for session timeout
            if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
            }

            updating.hide();

            function getData(sampleId) {
                var sampleTypes = data.product.rawSampleTypesArray;
                
                for (var i=0; i < sampleTypes.length; i++){
                    if (sampleTypes[i].sampleId == sampleId) {
                    return sampleTypes[i];
                    }
                }
                return false;
            }

            if (sampleId) {
                var sample      = getData(sampleId),
                    isPooled    = sample.isPooled,
                    maxPool     = sample.maxPool,
                    testPrice   = data.product.price;
            }

            if(isPooled === true){
                poolInputs.each(function(index, item) {
                    groupVals.push($(item).val());
                });

                groupVals.sort();

                var uniqueGroups = $.unique(groupVals).length;

                groups.find('.groups__row').remove();

                for(i=0; i<uniqueGroups; i++){
                    var gCount = i+1;
                    var html = '<div class="groups__row"><div class="groups__group"><p>Pool group <span>' + gCount + '</span></p></div><div class="groups__price"><p>£<span class="JSON_price">' + testPrice + '</span></p></div></div>';
                    groupRow.append(html);
                }

                var totalPrice = (testPrice) * (uniqueGroups);
                    
                groups.find('.groups__totalprice').html('<p>£' + totalPrice.toFixed(2) + '</p>');

                groups.show();

                basket.updateBasketState();
            }else{
            }

        });

        return false;
    }
}