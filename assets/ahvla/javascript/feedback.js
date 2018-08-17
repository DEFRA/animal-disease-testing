var feedback = {
    init: function(){
        this.bindings();
    },
    bindings: function(){
        $('.js-toggle-feedback').click(function(e){
            e.preventDefault();
            $('#feedback-panel').toggle();
        });
        $('#feedback-form').submit(function(){

            var feedbackMsg = $('#feedback-msg').val();

            // basic validation
            if (feedbackMsg == '') {
                // Clean up older error messages
                $('.validation-message', this).remove();
                // Add error message if textarea is empty
                $('label', this).after($('<span/>',{
                        'class': 'validation-message',
                        'text': 'Please include a message.'
                    })
                );
                // Displays the red bar next to the form
                $('label', this).parent().addClass('flush--top validation');

            } else {

                var pageTitle = $('title').text();
                $('#page-title').val(pageTitle);
                
                $.post('/feedback', $('#feedback-form').serialize(), function(data){
                    $('#feedback-form').remove();
                    $('#feedback-panel').toggle();
                    if (data.results == 1) {
                        $('#feedback-panel').append($('<p/>',{
                            'text': 'Thank you for your feedback.'
                        })).delay(5000).fadeOut();
                    } else {
                        $('#feedback-panel').append($('<p/>',{
                            'text': 'Something went wrong with sending your feedback. Please try again later.'
                        }));
                    }


                });
            }

            return false;
        });
    }
}

$(document).ready(function () {
    feedback.init();
});

