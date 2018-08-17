var basket = {

    init: function () {
        basket.displayRemoveFromBasketLinks();
        basket.hookAddProductBehaviour();
        basket.hookRemoveBehaviour();
        basket.hookRemoveSampleId();
        basket.hookRemoveSampleIdPackage();
        basket.hookPairedSerologyBehaviour();
        basket.hookChangeSampleType();
        basket.hookUpdateBasketAfterRemove();
        clinicalHistory.init();
    },

    // Links are displayed to remove items in basket.
    // For no-JS, seperate links are displayed from the server.
    displayRemoveFromBasketLinks: function () {
        $('.removeFromBasketLink').show();
    },

    hookAddProductBehaviour: function () {
        $('.addProductToBasket').unbind('click').bind('click', function () {
            var productId = $(this)
                .closest('.testSearchResultTemplate, .adviser-wrapper-template')
                .find('.JSON_id')
                .first()
                .html();

            basket.addProduct(productId);

            return false;
        });
    },

    hookRemoveBehaviour: function () {
        $('.removeFromBasket').unbind('click').bind('click', function () {
            var basketProductElement = $(this)
                .closest('.js-basketProduct');
            var productId = basketProductElement
                .find('.JSON_id')
                .val();

            var url = 'api/v1/basket-product/delete/' + productId + '?_token='+$("input[name='_token']").val();

            jQuery.post(url, subParams.build({}), function (data,textStatus,jqXHR) {

                // for session timeout
                if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                    top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                }

                if (data.result) {
                    basketProductElement.remove();
                }

                basket.updateBasketState();

                if ($('.js-basketProduct:visible').length) {
                    $('#NoTestsInBasket').hide();
                    $('#basketContainer').show();
                } else {
                    $('#NoTestsInBasket').show();
                    $('#basketContainer').hide();
                }
            });

            return false;
        });
    },

    hookPairedSerologyBehaviour: function () {
        $('.isFOPButton').unbind('click').bind('click', function () {
            var $basketTable = $(this).closest('.basket-table');
            var $isSOPSection = $basketTable.find('.isSOPSection');

            if($(this).val() === 'true'){
                $isSOPSection.removeClass('hidden');
            } else {
                $isSOPSection.addClass('hidden');
                basket.disableSOP($basketTable);
            }
        });

        $('.isSOPButton').unbind('click').bind('click', function () {
            var $basketTable = $(this).closest('.basket-table');

            if($(this).val() === 'true'){
                basket.enableSOP($basketTable);
            } else {
                basket.disableSOP($basketTable);
            }
        });

        $('.isPackageSOPButton').unbind('click').bind('click', function () {
            var $sopButtonName = $(this).attr("name");
            var $package = $sopButtonName.replace('package_isSOP_','');

            var packageTests = $.find('.package_tests_'+$package);

            if($(this).val() === 'true'){
                $(packageTests).find('.basket-table.js-basketProduct').each(function() {
                    basket.enableSOP($(this));
                });
            } else {
                $(packageTests).find('.basket-table.js-basketProduct').each(function() {
                    basket.disableSOP($(this));
                });
            }
        });
    },


    enableSOP: function ($basketTable) {
        var $pairedSamples = $basketTable.find('.paired-animal-samples');
        basket.emptyPairedSamples($basketTable);
        basket.populatePairedSamples($basketTable);

        $pairedSamples.removeClass('hidden');

        basket.updateBasketState();
    },

    disableSOP: function ($basketTable) {
        var $pairedSamples = $basketTable.find('.paired-animal-samples');
        var $isSOPSection = $basketTable.find('.isSOPSection');
        var productId = $basketTable.find('.JSON_id').val();

        $isSOPSection.find('#isSOP_false_' + productId).closest('label.pairedSerology').click().addClass('selectedOption');
        $isSOPSection.find('#isSOP_true_' + productId).closest('label.pairedSerology').removeClass('selectedOption');
        $pairedSamples.addClass('hidden');

        basket.emptyPairedSamples($basketTable);

        basket.updateBasketState();
    },

    emptyPairedSamples: function ($basketTable) {
        $basketTable.find('.paired-animal-samples .basket-table.body').remove();
    },

    // NOT used ??
    populatePackagePairedSamples: function ($basketTable) {
        var $originalSampleTable = $basketTable.find('.animalSamplesList');
        var $pairedSampleTable = $basketTable.find('.paired-animal-samples');

        var $newPairedTable = $originalSampleTable.clone(true, true).appendTo($pairedSampleTable);

        $newPairedTable.removeClass().addClass('basket-table body');
        $newPairedTable.find('.product_animal_sample_id').removeClass().addClass('animal-row');
        $newPairedTable.find('.sample-id').removeClass().addClass('pair-sample-id');
        $newPairedTable.find('.quantity').removeClass().addClass('animal-id');
        $newPairedTable.find('.removeAnimalId').remove();
        $newPairedTable.find('input.sampleId').each(function(){
            var SOPName = $(this).prop('name') + '_SOP';
            var SOPId = $(this).prop('id') + '_SOP';
            $(this).prop('name', SOPName);
            $(this).prop('id', SOPId);
            $(this).removeAttr('value');
        });

    },

    populatePairedSamples: function ($basketTable) {
        var $originalSampleTable = $basketTable.find('.animalSamplesList');
        var $pairedSampleTable = $basketTable.find('.paired-animal-samples');

        var $newPairedTable = $originalSampleTable.clone(true, true).appendTo($pairedSampleTable);

        $newPairedTable.removeClass().addClass('basket-table body');
        $newPairedTable.find('.product_animal_sample_id').removeClass().addClass('animal-row product_animal_sample_id_SOP');
        $newPairedTable.find('.sample-id').removeClass().addClass('pair-sample-id');
        $newPairedTable.find('.quantity').removeClass().addClass('animal-id');
        $newPairedTable.find('.removeAnimalId').remove();
        $newPairedTable.find('input.sampleId').each(function(){
            var SOPName = $(this).prop('name') + '_SOP';
            var SOPId = $(this).prop('id') + '_SOP';
            $(this).prop('name', SOPName);
            $(this).prop('id', SOPId);
            $(this).removeAttr('value');
        });

    },

    hookChangeSampleType: function () {
        $('.sampleTypeSelect').bind('change', function () {
            var $basketTable = $(this).closest('.basket-table');
            var $pairedOptions = $basketTable.find('#pairedSerologyOptions');
            var productId = $basketTable.find('.JSON_id').val();
            var sampleId = $(this).val();
            var sampleTypeElementName = $(this).attr('name');

            var url = 'api/v1/basket-product/product/' + productId + '/sample/' + (sampleId || 'null') + '?_token='+$("input[name='_token']").val();

            // Is the product within a package? Then get the package id instead.
            if (sampleTypeElementName.indexOf('packageSampleTypesSelect_') >= 0) {
                var packageId = $basketTable.find('.package_id').val();
                var url = 'api/v1/basket-product/package/' + packageId + '/product/' + productId + '/sample/' + (sampleId || 'null') + '?_token='+$("input[name='_token']").val();
            }

            jQuery.post(url, subParams.build({}), function (data,textStatus,jqXHR) {
                // for session timeout
                if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                    top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                }

                if(data.sample !== null && data.sample.isPairable === true){
                    $pairedOptions.removeClass('hidden');
                } else {
                    $pairedOptions.addClass('hidden');
                    basket.disableSOP($basketTable);
                }
            });
        });
    },

    addProduct: function (productId) {
        var url = 'api/v1/basket-product/' + productId + '?_token='+$("input[name='_token']").val();

        jQuery.post(url, subParams.build({}), function (data,textStatus,jqXHR) {

            // for session timeout
            if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
            }

            $("#small-basket").load(
                "/small-basket?" + $.param(subParams.build({})),
                function(data,textStatus,jqXHR) {
                    // for session timeout
                    if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                        top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                    }
                }
            );
            
            $("#small-basket").fadeIn(100).fadeOut(100).fadeIn(100);
            basket.updateBasketState();
        });
    },

    updateBasketState: function () {
        $('#basketContainer').show();

        basket.updateTotals();

        if (validation.formSetForValidation()) {
            validation.callServerFormValidation();
        }
    },

    updateTotals: function () {
        var totalWithoutVat = 0;
        var totalItemsCount = 0;
        $('.js-basketProduct:visible').find('.JSON_price').each(function () {
            totalWithoutVat = totalWithoutVat + Number($(this).html());
            totalItemsCount = totalItemsCount + 1;
        });

        var vat = totalWithoutVat * 0.2;
        var total = totalWithoutVat + vat;

        $('.basketTotalPrice').html(totalWithoutVat.toFixed(2));
        $('.basketTotalPriceVat').html(vat.toFixed(2));
        $('.basketTotalPriceWithVat').html(total.toFixed(2));
        $('.basketTotalItemsCount').html(totalItemsCount);

        var totalRemoved = $('.js-basketProduct:visible').find('.removingAnimalID').length;
        $('.totalAnimalsRemoved').html(totalRemoved);
    },

    appendSampleIdInputs: function (newElement, product) {
        $.each(product.animalIdsSamples, function (key, animalIdSample) {
            var newAnimalSampleElement = newElement.find('.product_animal_sample_id').first().clone();
            newAnimalSampleElement.show();

            newAnimalSampleElement.find('.sampleId').val(animalIdSample.sampleId);
            newAnimalSampleElement.find('.sampleId').attr('name', 'sampleid_' + animalIdSample.animal.id + '_' + product.id);

            newAnimalSampleElement.find('.animalId').html(animalIdSample.animal.description);

            if (product.animalIdsSamples.length == 1) {
                newAnimalSampleElement.find('.removeAnimalId').remove();
            } else {
                var removeName = 'removeAnimalId_' + product.id + '_' + animalIdSample.animal.id;
                newAnimalSampleElement.find('.removeAnimalId').attr('name', removeName);
            }

            newElement.find('.animalSamplesList').append(newAnimalSampleElement.prop('outerHTML'));
        });

        return newElement;
    },

    hookRemoveSampleId: function () {
        var basketItem          = $('.js-basketProduct');
        basketItem.each(function() {
            var removedAnimals  = 0;
            var $this           = $(this);

            $this.find('.removeAnimalId').on('click', function(e) {
                e.preventDefault();
                var removeRelated   = $this,
                    animal          = $(this).closest('.product_animal_sample_id'),
                    sampleId        = $(this).closest('.product_animal_sample_id').find('.sampleId'),
                    animalId        = $(this).closest('.product_animal_sample_id').find('.JSON_animalId'),
                    poolId          = $(this).closest('.product_animal_sample_id').find('.poolId'),
                    aninmalPrice    = $(this).closest('.product_animal_sample_id').find('.price-col'),
                    itemPrice       = $(this).closest('.product_animal_sample_id').find('.ITEM_price'),
                    totalPackageAnimals = $this.find('.package__tests').find('.product_animal_sample_id').length,
                    totalTestAnimals    = $this.find('.product_animal_sample_id').length,
                    totalAnimals    = (totalTestAnimals)-(totalPackageAnimals),
                    validationMsg   = $this.find('.groups__validation'),
                    regex = /removeAnimalId_(\w+)_(\d{1,2})+/,
                    matches = $(this).attr('id').match(regex),
                    removeBtnName = $(this).attr('name'),
                    removeRelatedId = $(this).closest('.js-basketProduct').find('.package__tests').find('.product_animal_sample_id[data-id="' + removeBtnName + '"]');

                if(animal.hasClass('removingAnimalID')){
                    removedAnimals -= 1;                
                    $(this).removeClass('enableAnimal');
                    label = 'label_' + $(this).attr('id');
                    $('#'+label).text('Remove');
                    animal.removeClass('removingAnimalID');
                    sampleId.prop("disabled", false);
                    animalId.css("opacity", 1);
                    aninmalPrice.css("opacity", 1);
                    itemPrice.addClass('JSON_price');
                    removeRelatedId.removeClass('removingPackageAnimalID');
                    
                    if(poolId.val() === '') {
                        poolId.prop("disabled", true);
                    }else {
                        poolId.prop("disabled", false).addClass('poolActive');
                    }

                    if(removedAnimals === 0) {
                        $('.basket-continue').show();
                        $('.removing-animals').hide();
                    }else {
                        $('.basket-continue').hide();
                        $('.removing-animals').show();
                    }

                    if(removedAnimals === totalAnimals){
                        validationMsg.html('<p>You must have at least 1 animal per test.</p>').show();
                    }else{
                        validationMsg.hide();
                    }

                    basket.updateBasketState();
                    testPooling.hookRemovePoolGroups(removeRelated);
                }else{
                    removedAnimals += 1;
                    $(this).addClass('enableAnimal');
                    label = 'label_' + $(this).attr('id');
                    $('#'+label).text('Add');
                    animal.addClass('removingAnimalID');
                    sampleId.prop("disabled", true);
                    poolId.prop("disabled", true).removeClass('poolActive');
                    animalId.css("opacity", 0.3);
                    aninmalPrice.css("opacity", 0.3);
                    itemPrice.removeClass('JSON_price');
                    removeRelatedId.addClass('removingPackageAnimalID');

                    if(removedAnimals === 0) {
                        $('.basket-continue').show();
                        $('.removing-animals').hide();
                    }else {
                        $('.basket-continue').hide();
                        $('.removing-animals').show();
                    }

                    if(removedAnimals === totalAnimals){
                        validationMsg.html('<p>You must have at least 1 animal per test.</p>').show();
                    }else{
                        validationMsg.hide();
                    }

                    basket.updateBasketState();
                    testPooling.hookRemovePoolGroups(removeRelated);
                }
            });
        });
    },

    hookUpdateBasketAfterRemove: function () {
        var removeButton = $('.js-basketProduct').find('.removeAnimalId');
        var regex = /removeAnimalId_([a-zA-Z0-9_\-]+)_(\d{1,2})+/
        $('.removing-animals__button').unbind('click').bind('click', function () {
            $('.removing-animals__status').show();
            removeButton.each(function( index ) {
                var matches = $(this).attr('id').match(regex);
                
                if ($(this).closest('.product_animal_sample_id').hasClass('removingAnimalID')) {
                    $.ajax({
                        type: 'POST',
                        url: 'api/v1/basket-product/animal/delete/' + matches[1] + '/' + matches[2] + '?_token='+$("input[name='_token']").val(),
                        data: subParams.build({}),
                        success: function (data,textStatus,jqXHR) {

                            // for session timeout
                            if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                                top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                            }

                            if (data.result) {
                                var productAnimalsContainer = removeButton.closest('.animalSamplesList');
                                var totalPackageAnimals = productAnimalsContainer.closest('.js-basketProduct').find('.package__tests').find('.removingPackageAnimalID');
                                removeButton.closest('.removingAnimalID').remove();
                                totalPackageAnimals.remove();

                                var length = productAnimalsContainer.find('.product_animal_sample_id').length;
                                if (length == 1) {
                                    productAnimalsContainer.find('.product_animal_sample_id').find('.removeAnimalId').remove();
                                }

                                basket.updateBasketState();
                                $('.removing-animals').hide();
                                $('.removing-animals__status').hide();
                            }
                        },
                        async:false
                    });
                }
            });

            $('.basket-continue').show();
            return false;
        });
    },

    removeSampleIdInput: function (removeButton) {
        var regex = /removeAnimalId_(\w+)_(\d{1,2})+/
        var matches = removeButton.attr('id').match(regex);
        var removeRelated = removeButton.closest('.js-basketProduct');

        jQuery.post('api/v1/basket-product/animal/delete/' + matches[1] + '/' + matches[2] + '?_token='+$("input[name='_token']").val(), subParams.build({}), function (data,textStatus,jqXHR) {

            // for session timeout
            if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
            }

            if (data.result) {
                var productAnimalsContainer = removeButton.closest('.animalSamplesList');
                var animalId = removeButton.closest('.product_animal_sample_id').find('.JSON_animalId').html();
                removeButton.closest('.js-basketProduct').find('.paired-animal-samples .JSON_animalId:contains("'+ animalId +'")').closest('.animal-row').remove();

                removeButton.closest('.product_animal_sample_id').remove();

                var length = productAnimalsContainer.find('.product_animal_sample_id').length;
                if (length == 1) {
                    productAnimalsContainer.find('.product_animal_sample_id').find('.removeAnimalId').remove();
                }

                testPooling.hookRemovePoolGroups(removeRelated);
                basket.updateBasketState();
            }
        });
    },

    hookRemoveSampleIdPackage: function () {
        $('.package__details').find('.removeAnimalId').unbind('click').bind('click', function () {
            basket.removeSampleIdInputPackage($(this));
            return false;
        });
    },

    removeSampleIdInputPackage: function (removeButton) {
        var regex = /removeAnimalId_(\w+)_(\d{1,2})+/;
        var matches = removeButton.attr('id').match(regex);
        var removeRelated = removeButton.closest('.js-basketProduct');
        var removeBtnName = removeButton.attr('id');
        var removeRelatedId = removeButton.closest('.js-basketProduct').find('.package__tests').find('.product_animal_sample_id[data-id="' + removeBtnName + '"]');
        var removeRelatedIdSOP = removeButton.closest('.js-basketProduct').find('.package__tests').find('.product_animal_sample_id_SOP[data-id="' + removeBtnName + '"]');

        jQuery.post('api/v1/basket-product/animal/delete/' + matches[1] + '/' + matches[2] + '?_token='+$("input[name='_token']").val(), subParams.build({}), function (data,textStatus,jqXHR) {

            // for session timeout
            if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
            }

            if (data.result) {
                var productAnimalsContainer = removeButton.closest('.animalSamplesList');
                removeButton.closest('.product_animal_sample_id').remove();
                removeRelatedId.remove();
                removeRelatedIdSOP.remove();

                var length = productAnimalsContainer.find('.product_animal_sample_id').length;
                if (length == 1) {
                    productAnimalsContainer.find('.product_animal_sample_id').find('.removeAnimalId').remove();
                }

                testPooling.hookRemovePoolGroups(removeRelated);

                basket.updateBasketState();
            }
        });
    }

}