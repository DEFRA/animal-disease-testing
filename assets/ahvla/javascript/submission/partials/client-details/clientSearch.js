var clientSearch = {

    loading: false,

    map: null,

    markers: [],

    lat: '',
    lng: '',
    latLngObj: null,

    persistFields: [],

    init: function (persistFields) {

        clientSearch.persistFields=persistFields;

        $('#clientSearchBox, #animalsSearchBox, #edited_client_cphh, #animal_cphh').keyup(function () {
            clientSearch.loadClientList($(this));
            return false;
        });

        var search_mode_client = $('#search_mode_client').val();
        var search_mode_animal = $('#search_mode_animal').val();

        util.hideDropDown('clientSearchResultRefDiv','clientSearchResults');
        util.hideDropDown('animalsSearchResultRefDiv','animalSearchResults');


        if (search_mode_client === "clientCPHSearch") {
            $('#clientSearchResults').detach().appendTo('#client-cphh-input');
        }

        if (search_mode_animal === "animalCPHSearch") {
            $('#animalSearchResults').detach().appendTo('#animal-cphh-input');
        }

        // postcode to map
        $('#animal-location-input').keyup(function () {

            var location = $(this).val();

            serverRequest.delay(
                'animal-location-input',
                function(){
                    if (typeof google != "undefined") {
                        clientSearch.gmlocation(location);
                    }
                },
                1000 );
        });

        // set map location
        $(document).on("click", ".clientSearchResultRefDiv", function () {

            // clear existing markers
            clientSearch.clearMarkers();

            var rowId = $(this).find("input[row_id]:first").attr('row_id');

            // do ajax call to get google for latlong
            var eastnorth1 = $('#location' + rowId).val();
            var easynorth = eastnorth1.split(",");

            var eastings = easynorth[0];
            var northings = easynorth[1];
            var parameters = {'eastings': eastings, 'northings': northings};

            jQuery.get('googleLatLong', parameters, function (data,textStatus,jqXHR) {

                // for session timeout
                if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                    top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                }

                clientSearch.lat = data.lat;
                clientSearch.lng = data.lng;

                // store locally
                $('#latbox').val(clientSearch.lat);
                $('#lngbox').val(clientSearch.lng);

                // show on map if there
                clientSearch.showOnMap();

                // manually persist hidden latlng fields
                persistentForm.saveInput($('#latbox'));
                persistentForm.saveInput($('#lngbox'));
            });
        });

        if (typeof google != "undefined") {
            this.gm();
        }

        clientEditor.init();
    },

    clearMarkers: function() {

        // clear existing markers
        for (var i = 0; i < clientSearch.markers.length; i++) {
            clientSearch.markers[i].setMap(null);
        }

        clientSearch.markers = [];
    },

    showOnMap: function() {

        if (clientSearch.map!=null) {

            if (typeof google != "undefined") {

                this.latLngObj = new google.maps.LatLng(clientSearch.lat, clientSearch.lng);

                var marker = new google.maps.Marker({
                    map: clientSearch.map,
                    draggable: true,
                    position: this.latLngObj
                });

                clientSearch.markers.push(marker);

                clientSearch.centerMap(clientSearch.defaultLocation);

                google.maps.event.addListener(marker, 'dragend', function (event) {
                    $('#latbox').val(event.latLng.lat());
                    $('#lngbox').val(event.latLng.lng());

                    // manually persist hidden latlng fields
                    persistentForm.saveInput($('#latbox'));
                    persistentForm.saveInput($('#lngbox'));
                });
            }
        }
    },

    saveFields: function() {

        for (field in this.persistFields) {
            persistentForm.saveInput( $('input[name="'+this.persistFields[field]+'"]') );
        }
    },

    loadClientList: function(filterTextNode, callback) {

        var searchResultsId = 'clientSearchResults';
        var searchResultsRefDiv = 'clientSearchResultRefDiv';
        var url = '/api/v1/pvs-client';

        var mode = '';

        var searchId = filterTextNode.attr('id');

        if (searchId === 'animalsSearchBox' || searchId === 'animal_cphh') {
            var searchResultsId = 'animalSearchResults';
            var searchResultsRefDiv = 'animalsSearchResultRefDiv';
            var url = '/api/v1/pvs-animals-address';
        }

        if (searchId === 'edited_client_cphh') {
            var mode = 'clientCPHSearch';
            $('#search_mode_client').val(mode);
        }

        if (searchId === 'animal_cphh') {
            var mode = 'animalCPHSearch';
            $('#search_mode_animal').val(mode);
        }

        if (filterTextNode.val().length < 2) {
            $('#'+searchResultsId).hide();
            if ( callback != undefined ) callback();
            return false;
        }

        var callback = function (data) {
            // keep clients hidden if filter is too small
            if (filterTextNode.val().length < 2) {
                $('#'+searchResultsId).hide();
            }
            clientSearch.saveFields();
            clientEditor.hookEditClientButtonsBehave();
            clientEditor.hookEditAnimalsAddressButtonsBehave();

            // loop through data and enable pre-selected clients
            var dataLength = data.length;
            for (var i = 0; i < dataLength; i ++) {
                if (data[i].isSelected) {
                    var $input = $('#'+searchResultsId).find('input[value=' + data[i].uniqId + ']').first();
                    $input.prop('checked', true);
                    $input.closest('.'+searchResultsRefDiv).find('input[type="radio"]').click();
                }
            }

        };

        serverRequest.loadDivWithResults(
            url,
            subParams.build({filter: filterTextNode.val(), mode:mode}),
            searchResultsId,
            searchResultsRefDiv,
            callback
        );
        return false;
    },

    centerMap: function (defaultLocation) {

        if (clientSearch.latLngObj!=null) {
            clientSearch.map.setCenter(clientSearch.latLngObj);
            if (defaultLocation) {
                clientSearch.map.setZoom(5);
            }
            else {
                clientSearch.map.setZoom(12);
            }
        }
    },

    /* kick off the first instance of the map when loading page */
    gm: function () {

        // get default current location, Kendal
        var defaultLat = '54.328006';
        var defaultLng = '-2.746290';
        var latbox = ($('#latbox').val() == '') ? defaultLat : $('#latbox').val();
        var lngbox = ($('#lngbox').val() == '') ? defaultLng : $('#lngbox').val();

        if ((latbox == defaultLat) && (lngbox == defaultLng)) {
            clientSearch.defaultLocation = true;
        } else {
            clientSearch.defaultLocation = false;
        }


        clientSearch.markers = [];

        clientSearch.latLngObj = new google.maps.LatLng(latbox, lngbox);
        this.map = new google.maps.Map(document.getElementById('map-canvas'), {
            zoom: 7,
            center: clientSearch.latLngObj
        });

        if (( $('#latbox').val() != '' ) && ( $('#lngbox').val() != '' )) {
            this.map.setZoom(12);
        }

        // start a marker
        marker = new google.maps.Marker({
            map: clientSearch.map,
            draggable: true,
            position: clientSearch.latLngObj
        });

        clientSearch.markers.push(marker);

        // latlong
        google.maps.event.addListener(marker, 'dragend', function (event) {
            $('#latbox').val(event.latLng.lat());
            $('#lngbox').val(event.latLng.lng());

            // manually persist hidden latlng fields
            persistentForm.saveInput($('#latbox'));
            persistentForm.saveInput($('#lngbox'));
        });
    },

    /* jump to postcode */
    gmlocation: function (location) {

        var geocoder = new google.maps.Geocoder();

        geocoder.geocode({'address': location}, function (results, status) {

            // clear existing markers
            clientSearch.clearMarkers();

            if (status == google.maps.GeocoderStatus.OK) {

                marker = new google.maps.Marker({
                    position: results[0].geometry.location,
                    map: clientSearch.map,
                    draggable: true
                });

                clientSearch.markers.push(marker);

                // latlong
                google.maps.event.addListener(marker, 'dragend', function (event) {
                    $('#latbox').val(event.latLng.lat());
                    $('#lngbox').val(event.latLng.lng());

                    // manually persist hidden latlng fields
                    persistentForm.saveInput($('#latbox'));
                    persistentForm.saveInput($('#lngbox'));
                });

                var latLng = marker.getPosition();
                clientSearch.map.setCenter(latLng);
                clientSearch.map.setZoom(12);

                // save locally
                $('#latbox').val(results[0].geometry.location.lat());
                $('#lngbox').val(results[0].geometry.location.lng());

                // manually persist hidden latlng fields
                persistentForm.saveInput($('#latbox'));
                persistentForm.saveInput($('#lngbox'));
            }
        });
    }
}