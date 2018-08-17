var clinicalHistory = {

    init: function () {
    	clinicalHistory.charCount();
        clinicalHistory.datePickerStop();
        clinicalHistory.datePickerStart();
    },

    datePickerStop: function(){
        $('#enter-date-manually-link').click(function(event){
            var $input = $('.js-date').pickadate();
            var picker = $input.pickadate('picker');
            picker.stop();
            $('#year').val('');
            $('#year').attr('name', 'sample_date_year');
            $('#enter-date-manually-link').hide();
            $('#calendar-date-entry-text').hide();
            $('#manual-date-entry-text').show();
            $('#sample-date-example').show();
            $('#use-date-picker-link').show();
            event.preventDefault();
        });

        return false;
    },

    datePickerStart: function(){
        $('#use-date-picker-link').click(function(event){
            util.startDatePicker();
            $('#use-date-picker-link').hide();
            $('#sample-date-example').hide();
            $('#manual-date-entry-text').hide();
            $('#enter-date-manually-link').show();
            $('#calendar-date-entry-text').show();
            event.preventDefault();
        });
    },

    charCount: function(){
        var text_max = 1000;
        $('#written_clinical_history_count').html(text_max + ' characters remaining.');

        $('#written_clinical_history').keyup(function() {
            var text_length = $('#written_clinical_history').val().length;
            var text_remaining = text_max - text_length;

            $('#written_clinical_history_count').html(text_remaining + ' characters remaining.');

            if(text_remaining <= 0){
                $('#written_clinical_history_count').css('color','red');
            }else{
                $('#written_clinical_history_count').css('color','black');
            }
        });
    }
}