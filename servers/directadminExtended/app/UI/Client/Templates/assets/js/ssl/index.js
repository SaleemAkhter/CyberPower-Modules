function showCsr(data, targetId, event)
{
    mgPageControler.vueLoader.loadModal(event, 'CSR', 'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Ssl_Buttons_ViewCSR', data.htmlData.csr, {}, true);
}
jQuery( document ).ready(function() {
jQuery('#dnsprovider').hide();

    function updateInput(){
        $('input:checkbox').iCheck('update');
        $commonname = $('input[name="webs[]"]:checked:first').val();
        $('input[name="commonname"]').val($commonname);
    }

    jQuery(document).on('change', "#checkAll", function() {
        jQuery('#wildcard').prop('checked', false);
        jQuery('.webs-section').show();
        jQuery('input[name="webs[]"]:checkbox').prop('checked', this.checked);
        updateInput();
    });

    jQuery(document).on('change', "input[name='webs[]']:checkbox", function() {
        jQuery('#checkAll').prop('checked', false);
        updateInput();
    });

    jQuery(document).on('change', "#wildcard", function() {
        if (this.checked) {
           jQuery('#dnsprovider').show();
           jQuery(".wildcardsubdomainentries").show();
           jQuery(".subdomainentries").hide();
           jQuery('#checkAll').prop('checked', true);
           jQuery('.subdomainentries input[name="webs[]"]:checkbox').prop('checked', false);
           jQuery('.wildcardsubdomainentries input[name="webs[]"]').prop('checked', true);
           // jQuery('.subdomainentries input[name="webs[]"]:checkbox:last').prop('checked', true);
           // jQuery(".webs-section").not(":first").not(":last").hide();
       }else{
           jQuery(".wildcardsubdomainentries").hide();
           jQuery(".subdomainentries").show();
           jQuery('#dnsprovider').hide();
           jQuery('#checkAll').prop('checked', false);
           jQuery('.wildcardsubdomainentries input[name="webs[]"]:checkbox').prop('checked', false);
           // jQuery('.subdomainentries input[name="webs[]"]:checkbox:last').prop('checked', true);
           // jQuery(".webs-section").not(":first").not(":last").show();
       }
       updateInput();
       jQuery(".ui-form-submit").on("click",function(){
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: "{$rawObject->getFormAction()}",
            data: "action=loadall&id=" + id,
            complete: function(data) {
                $('#main').html(data.responseText);
            }

        });
    });
   });

        // jQuery(document).on('change', "#dns", function() {
        jQuery('#dns').on('change', function() {
            jQuery('#dnsModal').modal('show');
        });
        $('#wildcardcheckbox').iCheck('destroy');
    });
