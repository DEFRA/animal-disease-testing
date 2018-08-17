var search = {

    filterSubmissionsForm: null,

    cache: new Array(),

    init: function (filterSubmissionsForm) {

        search.filterSubmissionsForm = filterSubmissionsForm;

        search.draftDisableDate();

        // status toggle - disable date when draft
        $("#status").on("change", function() {
            search.draftDisableDate();
        });

        // search button
        $('#filterSubmission').click(function (e) {
            $('#filterSubmission').attr("disabled", true);
            // we always start on page 1 when button is pressed
            $("#page").val(1);
            search.changeHash();
            return false;
        });

        // store first initial result
        search.startUrl();

        if (navigator.userAgent.indexOf('MSIE 7.0') !== -1) {
            window.attachEvent("onhashchange", function(){
                if ( window.location.hash != '' ) {
                    search.doSearch();
                }
            });
        } else if (navigator.userAgent.indexOf('MSIE 8.0') !== -1) {
            window.attachEvent("onhashchange", function(){
                if ( window.location.hash != '' ) {
                    search.doSearch();
                }
            });
        } else {
            window.addEventListener("hashchange", function(){
                if ( window.location.hash != '' ) {
                    search.doSearch();
                }
            });
        }

        // turn into ajax link
        search.setPreviousNextLink();

        search.hideSubmittedDate();
    },

    draftDisableDate: function() {

        var getStatus = $('#status').val();

        if (getStatus == "Draft") {
            $('#date').val("#date option:first").attr("disabled", true);
        } else {
            $('#date').attr("disabled", false);
        }

    },

    startUrl: function () {
        var hash = window.location.hash;
        var fields = search.searchParams();
        var content = $('#result').html();
        search.cache[hash] = { "content":content, "fields": fields};
    },

    changeHash: function () {

        var searchUrl = search.searchUrls() + new Date().getTime();
        var hash = md5(searchUrl);

        // if user presses search twice with same parameters
        if (window.location.hash=='#'+hash) {
            $('#filterSubmission').attr("disabled", false);
        }

        window.location.hash = hash;
    },

    searchParams: function () {

        var fields = new Array();

        // filters
        fields['clientId'] = $("input[name='clientId']").val();

        // clinician
        fields['clinician'] = $("input[name='clinician']").val();

        // status
        fields['status'] = $("select[name='status']").val();

        // date
        fields['date'] = $("select[name='date']").val();

        // page
        fields['page'] = $("#page").val();

        return fields;
    },

    searchUrls: function () {

        var fields = search.searchParams();

        var filters = '';

        filters += 'clientId='+encodeURIComponent(fields['clientId']);
        filters += '&clinician='+encodeURIComponent(fields['clinician']);
        filters += '&status='+encodeURIComponent(fields['status']);
        filters += '&date='+encodeURIComponent(fields['date']);
        filters += '&page='+fields['page'];

        return filters;
    },

    doSearch: function () {

        $( "#result" ).html('Loading...');

        // form the URL
        var filters = search.searchUrls();

        var hash = window.location.hash;

        if (search.cache[hash]!=undefined) {

            $("#result").html(search.cache[hash].content);

            fields = search.cache[hash].fields;

            for (var prop in fields) {

                field = $(".search-field[name='" + prop + "']");

                fieldType = field.get(0).tagName;

                field.val(fields[prop]);
            }

            search.setPreviousNextLink();

            $('#filterSubmission').attr("disabled", false);
        }
        else {

            $.ajax({
                url: "submissions/filter?" + filters,
                cache: true,
                success: function (content,textStatus,jqXHR) {

                    // for session timeout
                    if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                        top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                        return;
                    }

                    $("#result").html(content);

                    var fields = search.searchParams();

                    search.cache[hash] = { "content":content, "fields": fields};

                    search.setPreviousNextLink();

                    $('#filterSubmission').attr("disabled", false);
                }
            });
        }
    },

    setPreviousNextLink: function() {

        $('#previous-page').click(function () {

            var page = $('#previous-page').attr('next_page');

            // search.doSearch(page);

            $("#page").val(page);
            search.changeHash();

            return false;
        });

        $('#next-page').click(function () {

            var page = $('#next-page').attr('next_page');

            // search.doSearch(page);

            $("#page").val(page);
            search.changeHash();

            return false;
        });
    },

    removeBlankOptionsFromDateSelect: function() {
        $('#date option')
            .filter(function() {
                return !this.value || $.trim(this.value).length == 0 || $.trim(this.text).length == 0;
            })
            .remove();
    },

    hideSubmittedDate: function() {

        if($('#status').val() === 'Draft') {
            $('#submitted-date').hide();
        }else {
            $('#submitted-date').show();
            $("#date").val('LAST_MONTH');
            search.removeBlankOptionsFromDateSelect();
        }

        $('#status').on('change', function () {
            if($('#status').val() === 'Draft') {
                $('#submitted-date').hide();
            }else {
                $('#submitted-date').show();
                if ($('#date').val() == null) {
                    $("#date").val('LAST_MONTH');
                    search.removeBlankOptionsFromDateSelect();
                }
            }
        });

    }

}