function testConnection(obj) {
    jQuery('.spinloader, .successbox, .connected').remove();
    jQuery('#ajaxsts').html('');
    jQuery(obj).after('&nbsp;&nbsp;<i class="fa fa-spinner fa-spin spinloader"></i>');
    jQuery(obj).attr('disabled', true);
    jQuery.ajax({
        url: '',
        method: 'POST',
        data: 'appajax=true&customaction=imap_test_conn&' + jQuery('#imapform').serialize(),
        success: function (response) {
            jQuery('.spinloader').remove();
            jQuery(obj).attr('disabled', false);
            var result = jQuery.parseJSON(response);
            if (result.status == 'error')
                jQuery('#ajaxsts').html(result.msg);
            else
                jQuery(obj).after('&nbsp;&nbsp;' + result.msg);
        }
    });
}