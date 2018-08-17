var howGetInTouch = {

    init: function () {

    },

    toggleVisibility: function(){

        if (contactedSelection.getSelectedContactCode()==1) {
            util.show('howGetInTouch');
        } else {
            util.hide('howGetInTouch');
        }
    }
}