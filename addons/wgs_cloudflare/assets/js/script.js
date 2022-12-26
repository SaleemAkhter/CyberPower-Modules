jQuery(document).ready(function () {
    jQuery('input[name="servicetype"]').click(function () {
        jQuery('.servertypeform').hide();
        jQuery('#' + jQuery(this).val()).show();
    });
});