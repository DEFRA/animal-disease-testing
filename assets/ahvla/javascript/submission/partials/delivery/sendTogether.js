var sendTogether = {

    init: function () {

    },

    toggleVisibility: function(){

        if (sendSamples.getSelectedSendSample()=='together') {
            util.hide('separate');
            util.show('together');
        }
    }
}