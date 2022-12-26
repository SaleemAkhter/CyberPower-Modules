$( document ).ready(function() {

    /*** get tab panel ***/
    var elementActive = $('.tab-pane.active.in');
    /* activate other panels if they are not actived*/
    if (elementActive.length == 1)
    {
        $('#manage').addClass('in').addClass('active');
        $('#domain').addClass('in').addClass('active');
    }

});