var cancelDialog = {

    init: function() {

        // load confirmation box
        $.ajax({
            url: "/cancel-submission-dialog",
            success: function (data,textStatus,jqXHR) {

                // for session timeout
                if(jqXHR.getResponseHeader("Login-Screen") != null ) {
                    top.location.href=jqXHR.getResponseHeader("Login-Screen")+'?timedout=1';
                    return;
                }

                $('body').append(data);
            },
            dataType: 'html'
        });

        // $("dialog").trap(),
        $('body').on('click', '.js-dialog', function(e) {
            if (navigator.userAgent.indexOf('MSIE 6.0') !== -1) {
                return true;
            }
            e.preventDefault();
            var t = $(this);
            var n = t.attr("popup");
            var r = $("#" + n);

            var submissionId = t.attr("submission-id");

            $("input[name='submission-id']").val(submissionId);

            r.attr({
                tabindex: "-1",
                open: "true",
                role: "dialog",
                "aria-labelledby": "dialog-title"
            });

            r.append('<a href="javascript: void(0);" class="dialog-close js-dialog-close" role="button">close</a>');

            var i = $('<div class="dialog-backdrop opacityIE"></div>');

            $("body").prepend(i), r.show(), r.focus();

            var o = $("#global-header, #footer");

            o.attr("aria-hidden", "true");

            var a = r.find(".js-dialog-close");
            var s = function() {
                    // a.remove();
                    r.removeAttr("open role aria-labelledby tabindex");
                    r.hide();
                    $(".dialog-backdrop").remove();
                    o.attr("aria-hidden", "false");
                    // Leaving the dialog and focusing on the enabling link is a good idea,
                    // but now that the 'Cancel' button is in the proposition menu, the focus
                    // state will leave it with an orange background. Hence, disabling.
                    //t.focus();
                };

            i.on("click", function() {
                s()
            });

            a.on("click", function() {
                s()
            });

            $(document).keyup(function(e) {
                27 === e.keyCode && s()
            })
        })
    }
}