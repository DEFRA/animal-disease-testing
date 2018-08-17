var sendSamples = {

    dependents: [],

    init: function(){

        $("input[name='send_samples_package']").change(function(){

            if (sendSamples.getSelectedSendSample()=='together') {
                sendTogether.toggleVisibility();
            }
            else if (sendSamples.getSelectedSendSample()=='separate') {
                sendSeparate.toggleVisibility();
            }
        })

        // decide what sending arrangement to show
        sendTogether.toggleVisibility();
        sendSeparate.toggleVisibility();
    },

    getSelectedSendSample: function () {

        return $("input[name='send_samples_package']:visible:checked").val()
    }

}