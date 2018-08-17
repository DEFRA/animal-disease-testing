var sendSeparate = {

    init: function () {

    },

    toggleVisibility: function(){

        if (sendSamples.getSelectedSendSample()=='separate') {
            util.hide('together');
            util.show('separate');
        }
    }
}