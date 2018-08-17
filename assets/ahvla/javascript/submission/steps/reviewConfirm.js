var reviewConfirm = {

    init: function(){
        basket.init();

        reviewConfirm.toggleHideShow();

        $('#email_notification').change(function(){
            reviewConfirm.toggleHideShow();
        });

        $('#mobile_notification').change(function(){
            reviewConfirm.toggleHideShow();
        });

        persistentForm.init('ReviewConfirmForm');
    },

    toggleHideShow: function(){

        if($("input[name='email_notification']:checked").val()){
            util.jsShow('email_notification_panel');
        } else {
            util.jsHide('email_notification_panel');
        }

        if($("input[name='mobile_notification']:checked").val()){
            util.jsShow('mobile_notification_panel');
        } else {
            util.jsHide('mobile_notification_panel');
        }

    }
}