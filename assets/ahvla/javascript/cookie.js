$(document).ready(function () {
    "use strict";
    var $message = $('#global-cookie-message');

    var addCookieMessage = function () {
        var showCookieMessage = ($message && GOVUK.cookie('seen_cookie_message') === null);
        if (showCookieMessage) {
            $message.show();
            $('#global-cookie-message-button').click(closeCookieMessage);
        }
    };

    var closeCookieMessage = function (e) {
        e.preventDefault();
        $message.hide();
        GOVUK.cookie('seen_cookie_message', 'yes', {days: 28});
    };

    if (window.GOVUK && window.GOVUK.cookie) {
        addCookieMessage();
    }
});