jQuery(document).ready(function() {
    manageAppLinks();
    jQuery('#server_location, #set_service_provider').change(function() {
        manageAppLinks();
    });
    jQuery('body').on('click', '.growl-close', function() {
        jQuery(this).parent().remove();
    });
});

function manageAppLinks() {
    var url;
    if (jQuery('#server_location').val() == 'europe') {
        if (jQuery('#set_service_provider').val() == 'soyoustart')
            url = 'https://eu.api.soyoustart.com/createApp/';
        else
            url = 'https://eu.api.ovh.com/createApp/';
    } else if (jQuery('#server_location').val() == 'canada') {
        if (jQuery('#set_service_provider').val() == 'soyoustart')
            url = 'https://ca.api.soyoustart.com/createApp/';
        else
            url = 'https://ca.api.ovh.com/createApp/';
    } else if (jQuery('#server_location').val() == 'us') {
        if (jQuery('#set_service_provider').val() == 'soyoustart')
            url = 'https://api.soyoustart.us/createApp/';
        else
            url = 'https://api.ovh.us/createApp/';
    }
    jQuery('#set_ser_pro').attr('href', url);
}

//Get ISO files
function GetISOFiles(datastore, modulelink) {
    if (jQuery("#guest_os_server").val() == '') {
        jQuery("#guest_os_server").focus();
        return false;
    }
    if (jQuery("#guest_os_datastore").val() == '') {
        return false;
    }
    jQuery('#guest_os_iso').html('<option value=""> loading ... </option>');
    jQuery('.osnotfound').remove();
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=getisofiles&datastore=' + datastore + '&serverid=' + jQuery("#guest_os_server").val(),
        success: function(response) {
            jQuery('.osnotfound').remove();
            if (response != '') {
                if (response == 'Not found') {
                    jQuery("#guest_os_iso").html("<option disable='disable' value=''>" + response + "</option>");
                    jQuery('#guest_os_iso').after('<span style="color: #ff1a00;" class="osnotfound"><br/>Please Create "iso" name directory with this datastore "' + datastore + '" and add the ISO files under this directory.</span>');
                } else {
                    jQuery("#guest_os_iso").html("<option disable='disable' value=''>Select</option>" + response);
                    jQuery("#guest_os_iso").focus();
                    if (jQuery("#getisoval").val() != '') {
                        jQuery("#guest_os_iso").val(jQuery("#getisoval").val());
                    }
                }
            } else {
                jQuery("#guest_os_iso").html("<option disable='disable' value=''>Not found</option>");
                jQuery('#guest_os_iso').after('<span style="color: #ff1a00;" class="osnotfound"><br/>Please Create "iso" name directory with this datastore "' + datastore + '" and add the ISO files under this directory.</span>');
            }
        }
    });
}
//Get Datastores
function GetDataStores(modulelink) {
    if (jQuery("#guest_os_server").val() == '') {
        jQuery("#guest_os_server").focus();
        return false;
    }
    jQuery('#guest_os_datastore').html('<option value=""> loading ... </option>');
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=getdatastore&serverid=' + jQuery("#guest_os_server").val(),
        success: function(response) {
            if (response != '') {
                if (response == 'Not found') {
                    jQuery("#guest_os_datastore").html("<option disable='disable' value=''>" + response + "</option>");
                } else {
                    jQuery("#guest_os_datastore").html("<option disable='disable' value=''>Select</option>" + response);
                    if (jQuery("#getDatastoreval").val() != '') {
                        jQuery("#guest_os_datastore").val(jQuery("#getDatastoreval").val());
                        GetISOFiles(jQuery("#guest_os_datastore").val(), jQuery("#guest_os_datastore").attr('link'));
                    }
                }
            } else {
                jQuery("#guest_os_datastore").html("<option disable='disable' value=''>Select</option>");
            }
        }
    });
}

// Get Datacenters

function getDataCenters(modulelink, sid) {
    if (jQuery("#os_list_datacenter").html())
        jQuery('#os_list_datacenter').html('<option value=""> loading ... </option>');
    else
        jQuery('#datacenter').html('<option value=""> loading ... </option>');

    jQuery('.derror').remove();

    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=getdatacenter&serverid=' + sid,
        success: function(response) {
            jQuery('.derror').remove();
            var result = jQuery.parseJSON(response);
            if (result.result != '') {
                if (jQuery("#os_list_datacenter").html()) {
                    if (result.result == 'success') {
                        jQuery("#os_list_datacenter").html("<option disable='disable' value=''>Select</option>" + result.option);
                        jQuery("#os_list_datacenter").focus();
                        if (jQuery("#get_os_listDatacenterval").val()) {
                            jQuery("#os_list_datacenter").val(jQuery("#get_os_listDatacenterval").val());
                            if (jQuery('#os_list_datacenter').val()) {
                                getDcHost();
                            }
                        }

                        if (jQuery("#getDatacenterval").val()) {
                            jQuery("#os_list_datacenter").val(jQuery("#getDatacenterval").val());
                            if (jQuery('#os_list_datacenter').val()) {
                                getDcHost();
                            }
                        }
                    } else {
                        if (result.result == 'Not Found')
                            jQuery("#os_list_datacenter").html("<option disable='disable' value=''>" + result.result + "</option>");
                        else {
                            if (jQuery('#vmserver').html())
                                jQuery('#vmserver').after('<span class="derror" style="color:#ff0000;"><br/>' + result.result + '</span>');
                            else if (jQuery('#guest_os_server').html())
                                jQuery('#guest_os_server').after('<span class="derror" style="color:#ff0000;"><br/>' + result.result + '</span>');
                            jQuery("#os_list_datacenter").html("<option disable='disable' value=''>Select</option>");
                        }
                    }
                } else {
                    if (result.result == 'success') {
                        jQuery("#datacenter").html("<option disable='disable' value=''>Select</option>" + result.option);

                        if (jQuery("#getDatacenterval").val() != '') {
                            jQuery("#datacenter").val(jQuery("#getDatacenterval").val());

                            getIpDcHost();
                        }
                    } else {
                        if (result.result == 'Not Found')
                            jQuery("#datacenter").html("<option disable='disable' value=''>" + result.result + "</option>");
                        else {
                            if (jQuery('#vmserver').html())
                                jQuery('#vmserver').after('<span class="derror" style="color:#ff0000;"><br/>' + result.result + '</span>');
                            else if (jQuery('#guest_os_server').html())
                                jQuery('#guest_os_server').after('<span class="derror" style="color:#ff0000;"><br/>' + result.result + '</span>');
                            jQuery("#datacenter").html("<option disable='disable' value=''>Select</option>");
                        }
                    }
                }
            } else {
                if (jQuery("#os_list_datacenter").html())
                    jQuery('#os_list_datacenter').html("<option disable='disable' value=''>Select</option>");
                else
                    jQuery("#datacenter").html("<option disable='disable' value=''>Select</option>");

            }
        }
    });
}

// Get Existing Vm

function getExistingVm(modulelink, sid) {
    jQuery('#vmtemplate').html('<option value=""> loading ... </option>');
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=getexistingvm&serverid=' + sid,
        success: function(response) {
            if (response != '') {
                if (response == 'Not found') {
                    jQuery("#vmtemplate").html("<option disable='disable' value=''>" + response + "</option>");
                    jQuery("#vmtemplate").after('<span class="vmerror" style="color:#ff0000;"><br/>Create sample vm\'\s or powered off the created sample vm\'\</span>');
                } else {
                    jQuery("#vmtemplate").html("<option disable='disable' value=''>Select</option>" + response);
                    if (jQuery("#getExistingVm").val() != '') {
                        jQuery("#vmtemplate").val(jQuery("#getExistingVm").val());
                    }
                }
            } else {
                jQuery("#vmtemplate").html("<option disable='disable' value=''>Select</option>");
            }
        }
    });
}

// Get OS Version

function getOsVersion(modulelink, osVersion) {
    jQuery('#guest_os_version').html('<option value=""> loading ... </option>');
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=getOsversion&osVersion=' + osVersion,
        success: function(response) {
            if (response != 'guestOsIdentifier.txt file missing') {
                jQuery('#guest_os_version').html("<option disable='disable' value=''>Select</option>" + response);
                jQuery('#guest_os_version').focus();
                if (jQuery("#getosversionval").val() != '') {
                    jQuery("#guest_os_version").val(jQuery("#getosversionval").val());
                    getOsVersionId(jQuery("#guest_os_family").attr('link'), jQuery("#guest_os_version").val());
                }
            } else {
                jQuery('.error_p').remove();
                jQuery('#os_form').before('<p style="color:#ff0000;" class="error_p">' + response + '</p>');
            }
        }
    });
}

// Get OS veesion id

function getOsVersionId(modulelink, osVersion) {
    jQuery('#guest_os_version_id').val('Loading...');
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=getOsversion&id=true&osVersion=' + osVersion,
        success: function(response) {
            if (response != 'guestOsIdentifier.txt file missing') {
                jQuery('#guest_os_version_id').val(response);
                jQuery('#guest_os_version_id').attr('readonly', true);
            } else {
                jQuery('.error_p').remove();
                jQuery('#os_form').before('<p style="color:#ff0000;" class="error_p">' + response + '</p>');
                jQuery('#guest_os_version_id').attr('');
            }

        }
    });
}

jQuery(document).ready(function() {

    if (jQuery("#guest_os_server").val()) {
        //        GetDataStores(jQuery("#guest_os_datastore").attr('link'));
        //        if (jQuery('#guest_os_server').val())
        getDataCenters(jQuery("#os_list_datacenter").attr('link'), jQuery('#guest_os_server').val());
    }

    jQuery("#guest_os_datastore").change(function() {
        GetISOFiles(jQuery(this).val(), jQuery(this).attr('link'));
    });

    jQuery("#guest_os_server").change(function() {
        if (jQuery(this).val()) {
            getDataCenters(jQuery("#os_list_datacenter").attr('link'), jQuery(this).val());
            //            GetDataStores(jQuery("#guest_os_datastore").attr('link'));
            //            if (jQuery("#guest_os_datastore").val() != '') {
            //                GetISOFiles(jQuery("#guest_os_datastore").val(), jQuery("#guest_os_datastore").attr('link'));
            //            }
        }
    });

    jQuery('#os_list_hostname').change(function() {
        if (!jQuery('#os_list_hostname').attr('did')) {
            //            getHostNetwork();
            if (jQuery("#sys_pw").parent('td').html()) {
                getHostResources();
            } else {
                getHostDatastore();
            }
            // getDcHostResourcePool();
        }
    });

    jQuery('#os_list_network_adaptor').change(function() {
        jQuery('#guest_os_datastore').focus();
    });

    jQuery('#os_list_resourcepool').change(function() {
        jQuery('#os_list_hostname').focus();
    });

    jQuery('#guest_os_iso').change(function() {
        jQuery('#guest_os_family').focus();
    });

    jQuery('#os_list_datacenter').change(function() {
        jQuery("#os_list_resourcepool").focus();
        getDcHost();
    });

    //    jQuery('#datacenter').change(function () {
    //        getIpDcHost();
    //    });

    jQuery("#vmserver").change(function() {

        if (jQuery("#vmserver").val() != '') {
            getDataCenters(jQuery("#vmserver").attr('link'), jQuery('#vmserver').val());
            //            getExistingVm(jQuery("#vmserver").attr('link'), jQuery(this).val());
        }
    });

    jQuery("#vmservers").change(function() {
        if (jQuery("#vmservers").val() != '') {
            getDataCenters(jQuery("#vmservers").attr('link'), jQuery(this).val());
            getAllVms(jQuery("#vmservers").attr('link'), jQuery("#vmservers").val());
        }
    });

    jQuery("#exiting_vmservers").change(function() {
        if (jQuery("#exiting_vmservers").val() != '') {

            getDataCenters(jQuery("#exiting_vmservers").attr('link'), jQuery('#exiting_vmservers').val());
            //            getCustomDataCenters(jQuery("#exiting_vmservers").attr('link'), jQuery('#exiting_vmservers').val());
            //            getAllVms(jQuery("#exiting_vmservers").attr('link'), jQuery("#vmservers").val());
        }
    });

    jQuery("#guest_os_family").change(function() {
        getOsVersion(jQuery(this).attr('link'), jQuery(this).val());
        jQuery('#guest_os_version_id').val('');
    });


    jQuery("#guest_os_version").change(function() {
        getOsVersionId(jQuery("#guest_os_family").attr('link'), jQuery(this).val());
    });

    if (jQuery("#getDatacenterval").val()) {
        getDataCenters(jQuery("#vmserver").attr('link'), jQuery("#vmserver").val());
        //        jQuery("#datacenter").val(jQuery("#getDatacenterval").val());
    }

    //    if (jQuery("#getExistingVm").val() != '') {
    //        getExistingVm(jQuery("#vmserver").attr('link'), jQuery("#vmserver").val());
    //    }

    jQuery("#addOS").click(function() {
        getOsVersion(jQuery("#guest_os_family").attr('link'), jQuery("#guest_os_family").val());
        jQuery('#guest_os_server').focus();
    });

    if (jQuery("#getosversionval").val() != '') {
        getOsVersion(jQuery("#guest_os_family").attr('link'), jQuery("#guest_os_family").val());
    }

    jQuery("#datacenter").change(function() {
        getIpDcHost();
        if (jQuery("#datacenter").val() == "")
            jQuery("#datacenter").focus();
        else if (jQuery("#selectserverlocation").val() == "")
            jQuery("#selectserverlocation").focus();
        //        else
        //            getIP(jQuery("#datacenter").attr('link'), jQuery("#datacenter").val());
    });

    jQuery("#selectserverlocation").change(function() {
        if (jQuery("#datacenter").val() == "")
            jQuery("#datacenter").focus();
        else if (jQuery("#selectserverlocation").val() == "")
            jQuery("#selectserverlocation").focus();
        else
            getIP(jQuery("#selectserverlocation").attr('link'), jQuery("#datacenter").val());
    });

    jQuery("#product").change(function() {
        if (jQuery("#product").val() != "")
            getProductSetting(jQuery("#product").attr('link'), jQuery("#product").val());
    });

    jQuery('#hostname').change(function() {
        if (jQuery(this).val()) {
            if (jQuery('#datacenter').val())
                getIP(jQuery("#exiting_vmservers").attr('link'), jQuery('#datacenter').val(), jQuery(this).val());
            if (jQuery(this).val())
                getAllVms(jQuery("#exiting_vmservers").attr('link'), jQuery('#exiting_vmservers').val(), jQuery('#hostname option:selected').attr('obj'));
        }
    });
});

function getAdditionalIP(obj, id, vmname, modulelink) {
    jQuery(obj).html('<i class="fa fa-minus-circle" aria-hidden="true"></i>');
    jQuery(obj).attr('onclick', 'hideAdditionalIP(this,"' + id + '","' + vmname + '","' + modulelink + '")');
    jQuery(obj).parent().parent().after('<tr class="ajaxtr_' + id + '"><td colspan="9">Loading...</td></tr>');
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=getadditional&id=' + id + '&vmname=' + vmname,
        success: function(response) {
            jQuery('.ajaxtr_' + id).remove();
            jQuery(obj).parent().parent().after(response);
        }
    });
}

function hideAdditionalIP(obj, id, vmname, modulelink) {
    jQuery(obj).html('<i class="fa fa-plus-circle" aria-hidden="true"></i>');
    jQuery(obj).attr('onclick', 'getAdditionalIP(this,"' + id + '","' + vmname + '","' + modulelink + '")');
    jQuery('.ajaxtr_' + id).remove();
}

//function showAdditionalIP(obj, id, vmname, modulelink) {
//    jQuery(obj).html('<i class="fa fa-minus-circle" aria-hidden="true"></i>');
//    jQuery(obj).attr('onclick', 'hideAdditionalIP(this,"' + id + '","' + vmname + '","' + modulelink + '")');
//    jQuery('.ajaxtr_' + vmname).slideDown('fast');
//
//}


function checkvm(obj, modulelink) {
    var vmname = jQuery(obj).val();
    jQuery("#spinner").remove();
    jQuery("#errordiv").remove();
    if (vmname != '') {
        jQuery("#ipstatus").attr('disabled', true);
        jQuery(obj).after('<i class="fa fa-spinner fa-pulse fa-3x fa-fw" id="spinner" style="font-size: 17px;color: #000;"></i>');
        jQuery.ajax({
            type: 'post',
            url: modulelink,
            data: 'action=ajax&customaction=checkvm&vmname=' + vmname,
            success: function(response) {
                jQuery("#ipstatus").val(response);
                jQuery("#spinner").remove();
                jQuery("#ipstatus").attr('disabled', false);
                if (response == '2') {
                    jQuery(obj).after('&nbsp;<span style="color:#ff0000;" id="errordiv">This Vm have already assigned IP,<br /> you can assign the additional IP.</span>');
                }
            }
        });
    }
}

// Get Datacenters

function getAllVms(modulelink, sid, host_obj) {

    jQuery('#vmname').html('<option value=""> loading ... </option>');
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=getallvms&serverid=' + sid + '&host_obj=' + host_obj,
        success: function(response) {
            if (response != '') {
                if (response == 'Not found') {
                    jQuery("#vmname").html("<option disable='disable' value=''>" + response + "</option>");
                } else {
                    jQuery("#vmname").html("<option disable='disable' value=''>Select</option>" + response);
                    //                    if (jQuery("#getDatacenterval").val() != '') {
                    //                        jQuery("#datacenter").val(jQuery("#getDatacenterval").val());
                    //                    }
                }
            } else {
                jQuery("#vmname").html("<option disable='disable' value=''>Select</option>");
            }
        }
    });
}

function getIP(modulelink, datacenter, hostname) {
    jQuery('#ips').html('<option value=""> loading ... </option>');
    //    jQuery('#hostname').html('<option value=""> loading ... </option>');
    var pid = jQuery('#product').val();
    //    if (pid) {
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=getips&datacenter=' + datacenter + '&sid=' + jQuery('#exiting_vmservers').val() + '&pid=' + pid + '&hostname=' + hostname,
        success: function(response) {
            if (response != '') {
                var result = jQuery.parseJSON(response);
                if (result.options == 'Not Found') {
                    jQuery("#ips").html("<option disable='disable' value=''>" + result.options + "</option>");
                } else {
                    jQuery("#ips").html("<option disable='disable' value=''>Select</option>" + result.options);
                }
            } else {
                jQuery("#ips").html("<option disable='disable' value=''>Select</option>");
            }
        }
    });
    //    }
}

function getProductSetting(modulelink, pid) {
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=getproductsetting&pid=' + pid,
        success: function(response) {
            if (response != '') {
                jQuery(".bandwidth").show();
                jQuery("#bandwidth").attr('required', true);
            } else {
                jQuery(".bandwidth").hide();
                jQuery("#bandwidth").attr('required', false);
            }
        }
    });
}

// Decline request

function declineReq(obj, dbId, vmName, modulelink, sid) {

    var cnfrm = confirm('Are you sure want to decline this request ?');
    if (cnfrm) {
        jQuery('.reason textarea[name="reason"]').attr('readonly', false);
        jQuery('.decline_vm').show();
        jQuery('.reason textarea[name="reason"]').val('');
        jQuery('#addAdimUser').modal('show');
        jQuery('#declineVm').val(vmName);
        jQuery('#declineDbId').val(dbId);
        jQuery('#declineMdLink').val(modulelink);
        jQuery('#declineSid').val(sid);
    }
}

// submit decline request

function submitDeclineReq(obj) {
    if (jQuery('.reason textarea[name="reason"]').val() == '') {
        jQuery('.reason textarea[name="reason"]').focus();
        jQuery('.reason textarea[name="reason"]').css('border', '2px solid #ff0000');
        return false;
    }
    jQuery('.reason textarea[name="reason"]').css('border', '1px solid #ccc');
    var modulelink = jQuery('#declineMdLink').val();
    jQuery('#decresult').html('<i class="fa fa-spinner fa-pulse"></i>');

    jQuery('.decline').css('pointer-events', 'none');
    jQuery('.approve').css('pointer-events', 'none');
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=declineMigrate&vm_name=' + jQuery('#declineVm').val() + '&id=' + jQuery('#declineDbId').val() + '&reason=' + jQuery('.reason textarea[name="reason"]').val() + '&serviceid=' + jQuery('#declineSid').val(),
        success: function(response) {
            jQuery('.decline').css('pointer-events', 'auto');
            jQuery('.approve').css('pointer-events', 'auto');
            jQuery('#decresult').html(response);
            setTimeout(function() {
                location.reload();
            }, 5000);
        }
    });
}

// Submit aproval request

function approveReq(obj, id, modulelink, vmname, host_to, resource_pool, server_id, sid, datacenter, btntext) {
    var cnfrm = confirm('Are you sure want to accept this request ?');
    if (cnfrm) {
        jQuery('.decline').css('pointer-events', 'none');
        jQuery('.approve').css('pointer-events', 'none');
        jQuery(obj).html('<i class="fa fa-spinner fa-spin"></i>');
        jQuery.ajax({
            type: 'post',
            url: modulelink,
            data: 'action=ajax&customaction=acceptMigrate&vm_name=' + vmname + '&id=' + id + '&host_to=' + host_to + '&serviceid=' + sid + '&serverid=' + server_id + '&r_pool=' + resource_pool + '&datacenter=' + datacenter,
            success: function(response) {
                jQuery('.decline').css('pointer-events', 'auto');
                jQuery('.approve').css('pointer-events', 'auto');

                jQuery(obj).html(btntext);
                var result = jQuery.parseJSON(response);
                jQuery('#ajaxres').html(result.msg);
                if (result.result == 'success') {
                    setTimeout(function() {
                        location.reload();
                    }, 7000);
                } else {
                    setTimeout(function() {
                        jQuery('#ajaxres').html('');
                    }, 7000);
                }
            }
        });
    }
}

// Show reaon

function showReason(obj) {
    var reason = jQuery(obj).attr('reason');
    jQuery('#addAdimUser').modal('show');
    jQuery('.decline_vm').hide();
    jQuery('.reason textarea[name="reason"]').css('border', '1px solid #ccc');
    jQuery('.reason textarea[name="reason"]').attr('readonly', true);
    jQuery('.reason textarea[name="reason"]').val(reason);
}

// Get DC resourcepool

function getDcHostResourcePool() {
    if (jQuery('#os_list_hostname').val()) {
        jQuery('#os_list_resourcepool').html('<option value=""> loading ... </option>');

        var hostObj = jQuery('#os_list_hostname option:selected').attr('obj');

        if (jQuery('#guest_os_server').val())
            var serverId = jQuery('#guest_os_server').val();
        else if (jQuery('#vmserver').val())
            var serverId = jQuery('#vmserver').val();
        else if (jQuery('#vmservers').val())
            var serverId = jQuery('#vmservers').val();
        else if (jQuery('#exiting_vmservers').val())
            var serverId = jQuery('#exiting_vmservers').val();
        jQuery.ajax({
            type: 'post',
            url: jQuery('#os_list_datacenter').attr('link'),
            data: 'action=ajax&customaction=get_host_resourcepool&serverid=' + serverId + '&dc=' + jQuery('#os_list_datacenter').val() + '&host_obj=' + hostObj + '&host=' + jQuery('#os_list_hostname').val(),
            success: function(response) {
                jQuery('.poolerr').remove();
                var result = jQuery.parseJSON(response);
                //getHostNetwork();
                if (result.result == '') {
                    jQuery("#os_list_resourcepool").html("<option disable='disable' value=''>Not found</option>");
                } else if (result.result == 'Not found') {
                    jQuery("#os_list_resourcepool").html("<option disable='disable' value=''>" + result.result + "</option>");
                } else {
                    if (result.result != '' && result.result != 'succes') {
                        jQuery('.poolerr').remove();
                        jQuery('#os_list_resourcepool').after('<span class="poolerr" style="line-height: 25px;color:#ff0000;"><br/>' + result.result + '</span>');
                    }
                    jQuery("#os_list_resourcepool").html("<option disable='disable' value=''>Select</option>" + result.resourcepool);
                    jQuery("#os_list_resourcepool").focus();

                    if (jQuery("#get_os_list_resourcepool").val() != '') {
                        jQuery("#os_list_resourcepool").val(jQuery("#get_os_list_resourcepool").val());
                    }
                }
            }
        });
    }
}

// Get datacenter host

function getDcHost() {
    if (jQuery('#os_list_datacenter').val()) {
        jQuery('#os_list_hostname').html('<option value=""> loading ... </option>');

        if (jQuery('#guest_os_server').val())
            var serverId = jQuery('#guest_os_server').val();
        else if (jQuery('#vmserver').val())
            var serverId = jQuery('#vmserver').val();
        else if (jQuery('#vmservers').val())
            var serverId = jQuery('#vmservers').val();
        else if (jQuery('#exiting_vmservers').val())
            var serverId = jQuery('#exiting_vmservers').val();

        jQuery.ajax({
            type: 'post',
            url: jQuery('#os_list_datacenter').attr('link'),
            data: 'action=ajax&customaction=get_dc_host&serverid=' + serverId + '&dc=' + jQuery('#os_list_datacenter').val(),
            success: function(response) {
                jQuery('.hosterr').remove();
                var result = jQuery.parseJSON(response);
                if (result.result == '') {
                    jQuery("#os_list_hostname").html("<option disable='disable' value=''>Not found</option>");
                } else if (result.result == 'Not found') {
                    jQuery("#os_list_hostname").html("<option disable='disable' value=''>" + result.result + "</option>");
                } else {
                    if (result.result != '' && result.result != 'succes') {
                        jQuery('.hosterr').remove();
                        jQuery('#os_list_datacenter').after('<span class="hosterr" style="line-height: 25px;color:#ff0000;"><br/>' + result.result + '</span>');
                    }
                    jQuery("#os_list_hostname").html("<option disable='disable' value=''>Select</option>" + result.hostname);

                    if (jQuery("#get_os_list_hostname").val() != '') {
                        jQuery("#os_list_hostname").val(jQuery("#get_os_list_hostname").val());
                    }
                    if (jQuery('#os_list_hostname').val()) {
                        //  getDcHostResourcePool();
                        if (jQuery("#sys_pw").parent('td').html()) {
                            getHostResources();
                        } else {
                            getHostDatastore();
                        }
                    }
                }
            }
        });
    }
}

// Get host resources

function getHostResources() {
    var hostObj = jQuery('#os_list_hostname option:selected').attr('obj');

    jQuery('.hosterr, .vmerror').remove();
    if (jQuery('#os_list_hostname').val()) {
        jQuery('#vmtemplate').html('<option value=""> loading ... </option>');

        if (jQuery('#guest_os_server').val())
            var serverId = jQuery('#guest_os_server').val();
        else if (jQuery('#vmserver').val())
            var serverId = jQuery('#vmserver').val();
        else if (jQuery('#vmservers').val())
            var serverId = jQuery('#vmservers').val();
        if (jQuery('#vmserver').val())
            var existvm = 'true';
        else
            var existvm = 'false';

        jQuery.ajax({
            type: 'post',
            url: jQuery('#os_list_hostname').attr('link'),
            data: 'action=ajax&customaction=get_all_host_resources&serverid=' + serverId + '&dc=' + jQuery('#os_list_datacenter').val() + '&host_obj=' + hostObj + '&host=' + jQuery('#os_list_hostname').val() + '&existvm=' + existvm,
            success: function(response) {
                var result = jQuery.parseJSON(response);
                if (result.error) {
                    jQuery('.hosterr, .vmerror').remove();
                    jQuery('#os_list_hostname').after('<span class="hosterr" style="line-height: 25px;color:#ff0000;"><br/>' + result.error + '</span>');
                }

                if (result.vm == '') {
                    jQuery("#vmtemplate").html("<option disable='disable' value=''>Not Found</option>");
                    jQuery("#vmtemplate").after('<span class="vmerror" style="color:#ff0000;"><br/>Create sample vm\'\s with your vCenter or powered off the created sample vm\'\s</span>');
                } else if (result.vm == 'Not Found') {
                    jQuery("#vmtemplate").html("<option disable='disable' value=''>" + result.vm + "</option>");
                    jQuery("#vmtemplate").after('<span class="vmerror" style="color:#ff0000;"><br/>Create sample vm\'\s with your vCenter or powered off the created sample vm\'\</span>');
                } else {
                    jQuery("#vmtemplate").html("<option disable='disable' value=''>Select</option>" + result.vm);
                    if (jQuery("#getExistingVm").val() != '') {
                        jQuery("#vmtemplate").val(jQuery("#getExistingVm").val());
                    }
                }
            }
        });
    }
}

function getHostNetwork() {
    jQuery('#os_list_network_adaptor').html('<option value=""> loading ... </option>');
    var hostObj = jQuery('#os_list_hostname option:selected').attr('obj');
    jQuery('.networkerr').remove();
    if (jQuery('#os_list_hostname').val()) {
        if (jQuery('#guest_os_server').val())
            var serverId = jQuery('#guest_os_server').val();
        else if (jQuery('#vmserver').val())
            var serverId = jQuery('#vmserver').val();
        else if (jQuery('#vmservers').val())
            var serverId = jQuery('#vmservers').val();

        jQuery.ajax({
            type: 'post',
            url: jQuery('#os_list_hostname').attr('link'),
            data: 'action=ajax&customaction=get_all_host_network&serverid=' + serverId + '&dc=' + jQuery('#os_list_datacenter').val() + '&host_obj=' + hostObj + '&host=' + jQuery('#os_list_hostname').val(),
            success: function(response) {
                var result = jQuery.parseJSON(response);
                // getHostDatastore();
                if (result.error) {
                    jQuery('.networkerr').remove();
                    jQuery('select[name="guest_network_adptr"]').after('<span class="networkerr" style="line-height: 25px;color:#ff0000;"><br/>' + result.error + '</span>');
                }
                if (result.network == '') {
                    jQuery("#os_list_network_adaptor").html("<option disable='disable' value=''>Not found</option>");
                } else if (result.network == 'Not found') {
                    jQuery("#os_list_network_adaptor").html("<option disable='disable' value=''>" + result.network + "</option>");
                } else {
                    jQuery("#os_list_network_adaptor").html("<option disable='disable' value=''>Select</option>" + result.network);
                    jQuery('#os_list_network_adaptor').focus();
                    if (jQuery("#getNetworkval").val() != '') {
                        jQuery("#os_list_network_adaptor").val(jQuery("#getNetworkval").val());

                    }
                }
            }
        });
    }
}

function getHostDatastore() {
    jQuery('#guest_os_datastore').html('<option value=""> loading ... </option>');
    var hostObj = jQuery('#os_list_hostname option:selected').attr('obj');
    jQuery('.datastoreerr').remove();
    if (jQuery('#os_list_hostname').val()) {
        if (jQuery('#guest_os_server').val())
            var serverId = jQuery('#guest_os_server').val();
        else if (jQuery('#vmserver').val())
            var serverId = jQuery('#vmserver').val();
        else if (jQuery('#vmservers').val())
            var serverId = jQuery('#vmservers').val();

        jQuery.ajax({
            type: 'post',
            url: jQuery('#os_list_hostname').attr('link'),
            data: 'action=ajax&customaction=get_all_host_datastores&serverid=' + serverId + '&dc=' + jQuery('#os_list_datacenter').val() + '&host_obj=' + hostObj + '&host=' + jQuery('#os_list_hostname').val(),
            success: function(response) {
                var result = jQuery.parseJSON(response);
                // getHostResources();
                if (result.error) {
                    jQuery('.datastoreerr').remove();
                    jQuery('select[name="datastore"]').after('<span class="datastoreerr" style="line-height: 25px;color:#ff0000;"><br/>' + result.error + '</span>');
                }
                if (result.datastore == '') {
                    jQuery("#guest_os_datastore").html("<option disable='disable' value=''>Not Found</option>");
                } else if (result.datastore == 'Not found') {
                    jQuery("#guest_os_datastore").html("<option disable='disable' value=''>" + result.datastore + "</option>");
                } else {
                    jQuery("#guest_os_datastore").html("<option disable='disable' value=''>Select</option>" + result.datastore);
                    if (jQuery("#getDatastoreval").val() != '') {
                        jQuery("#guest_os_datastore").val(jQuery("#getDatastoreval").val());
                        GetISOFiles(jQuery("#guest_os_datastore").val(), jQuery("#guest_os_datastore").attr('link'));

                    }
                }
            }
        });
    }
}

function getCustomDataCenters(modulelink, sid) {
    var pid = jQuery('#product').val();
    if (pid) {
        jQuery('#product').css('border', '1px solid #ccc');
        jQuery('#datacenter').html('<option value="">Loading...</option>');
        jQuery.ajax({
            type: 'post',
            url: modulelink,
            data: 'action=ajax&customaction=getdatacenter&serverid=' + sid + '&pid=' + pid,
            success: function(response) {
                if (response == 'Not Found') {
                    jQuery('#datacenter').html('<option value="">' + response + '</option>');
                } else {
                    jQuery('#datacenter').html('<option value="">Select</option>' + response);
                }
            }
        });
    } else {
        alert('Please select the product!');
        jQuery('#product').focus();
        jQuery('#product').css('border', '1px solid #ff0000');
        jQuery('#exiting_vmservers').val('');
        return false;
    }

}

function getIpDcHost() {

    if (jQuery('#guest_os_server').val())
        var serverId = jQuery('#guest_os_server').val();
    else if (jQuery('#vmserver').val())
        var serverId = jQuery('#vmserver').val();
    else if (jQuery('#vmservers').val())
        var serverId = jQuery('#vmservers').val();
    else if (jQuery('#exiting_vmservers').val())
        var serverId = jQuery('#exiting_vmservers').val();

    if (jQuery('#datacenter').val()) {
        if (jQuery('#hostname').html())
            jQuery("#hostname").html("<option disable='disable' value=''>Loading...</option>");
        else
            jQuery("#os_list_hostname").html("<option disable='disable' value=''>Loading...</option>");
        jQuery('.hosterr').remove();
        jQuery.ajax({
            type: 'post',
            url: jQuery('#os_list_datacenter').attr('link'),
            data: 'action=ajax&customaction=get_dc_host&onlyhost=true&serverid=' + serverId + '&dc=' + jQuery('#datacenter').val(),
            success: function(response) {
                jQuery('.hosterr').remove();
                var result = jQuery.parseJSON(response);
                if (result.result == '') {
                    if (jQuery('#hostname').html())
                        jQuery("#hostname").html("<option disable='disable' value=''>Not found</option>");
                    else
                        jQuery("#os_list_hostname").html("<option disable='disable' value=''>Not found</option>");
                } else if (result.result == 'Not found') {
                    if (jQuery('#hostname').html())
                        jQuery("#hostname").html("<option disable='disable' value=''>" + result.result + "</option>");
                    else
                        jQuery("#os_list_hostname").html("<option disable='disable' value=''>" + result.result + "</option>");
                } else {
                    if (result.result != '' && result.result != 'succes') {
                        jQuery('.hosterr').remove();

                        if (jQuery('#datacenter').html())
                            jQuery("#datacenter").html("<option disable='disable' value=''>" + result.result + "</option>");
                        else
                            jQuery('#os_list_datacenter').after('<span class="hosterr" style="line-height: 25px;color:#ff0000;"><br/>' + result.result + '</span>');
                    }

                    if (jQuery('#hostname').html())
                        jQuery("#hostname").html("<option disable='disable' value=''>Select</option>" + result.hostname);
                    else
                        jQuery("#os_list_hostname").html("<option disable='disable' value=''>Select</option>" + result.hostname);

                    if (jQuery("#get_os_list_hostname").val() != '') {
                        jQuery("#os_list_hostname").val(jQuery("#get_os_list_hostname").val());
                    }
                }
            }
        });
    }
}

function getServerStatus(obj, id, link) {

    jQuery('#status_' + id).html('<i class="fa fa-spinner fa-pulse"></i>');
    jQuery.ajax({
        type: 'post',
        url: link,
        data: 'action=ajax&customaction=get_server_status&serverid=' + id,
        success: function(response) {
            jQuery('#status_' + id).html(response);
        }
    });
}

function enableAllCheckbox(obj) {
    jQuery('.multicheckip').each(function() {
        jQuery(this).prop('checked', jQuery(obj).prop('checked'));
    });
}

function submitDeleteIpForm(obj) {
    var tick = false;
    jQuery('.multicheckip').each(function() {
        if (jQuery(this).prop('checked'))
            tick = true;
    });
    if (tick) {
        var cnfrm = confirm('Are you sure want to delete selected items ?');
        if (cnfrm) {
            jQuery('#deleteMulitpleIps').submit();
        }
    } else {
        alert('Select atleast one IP!');
    }
}

function submitMarkIpAsFree(obj) {
    var tick = false;
    jQuery('.multicheckip').each(function() {
        if (jQuery(this).prop('checked'))
            tick = true;
    });
    if (tick) {
        jQuery('input[name="deletemultiple"]').after('<input type="hidden" name="freemultiple" value="Free Selected">');
        jQuery('input[name="deletemultiple"]').remove();
        jQuery('#deleteMulitpleIps').submit();
    } else {
        alert('Select atleast one IP!');
    }
}

function submitMarkIpAsReserve(obj) {
    var tick = false;
    jQuery('.multicheckip').each(function() {
        if (jQuery(this).prop('checked'))
            tick = true;
    });
    if (tick) {
        jQuery('input[name="deletemultiple"]').after('<input type="hidden" name="reservemultiple" value="Reserved">');
        jQuery('input[name="deletemultiple"]').remove();
        jQuery('#deleteMulitpleIps').submit();
    } else {
        alert('Select atleast one IP!');
    }
}

function deactivateApp(app, token) {
    var confirmMsg = confirm('Are you sure to want to deactivate this app?');
    if (confirmMsg)
        window.location = 'addonmodules.php?module=vmware&action=apps&command=deactivate&app=' + app + token;
}

function getHost(id, hostid) {
    jQuery('#' + id).html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=gethostname&hostid=' + hostid + '&serverid=' + serverid,
        success: function(response) {
            jQuery('#' + id).html(response);
        }
    });
}

function getBw(id, vmid, vmname, sid, relid) {
    jQuery('#' + id).html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
    var timerange = jQuery('select[name="timerange"]').val();
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=getvmBw&vmid=' + vmid + '&vmname=' + vmname + '&sid=' + sid + '&relid=' + relid + '&timerange=' + timerange,
        success: function(response) {
            jQuery('#' + id).html(response);
        }
    });
}

function getAllHosts() {
    if (jQuery('select[name="vcenterserver"]').val() != '') {
        jQuery('select[name="hostid"]').html('<option value="">Loading...</option>');
        jQuery.ajax({
            type: 'post',
            url: modulelink,
            data: 'action=ajax&customaction=getallhostname&serverid=' + serverid + "&postHost=" + postHost,
            success: function(response) {
                jQuery('select[name="hostid"]').html("<option disable='disable' value=''>Select</option>" + response);
                jQuery('#usagehostname').val(jQuery('select[name="hostid"] option:selected').text());
            }
        });
    }
}

function syncSetting(obj) {

    if (jQuery('select[name="vcenterserver"]').val() != '') {
        jQuery('#syncloader').show();
        jQuery(obj).css('pointer-events', 'none');
        jQuery.ajax({
            type: 'post',
            url: modulelink,
            data: 'action=ajax&customaction=getallsettindc&serverid=' + jQuery('select[name="vcenterserver"]').val(),
            success: function(response) {
                jQuery(obj).css('pointer-events', 'auto');
                jQuery('#syncloader').hide();
                jQuery("#settingHtml").html(response);
            }
        });
    }
}

function getDcHostSetting(obj, dcId, dcName) {
    if (jQuery(obj).attr('class') == 'collapsed' || jQuery(obj).attr('class') === undefined) {
        jQuery('#loader-' + dcId).show();
        jQuery("#dchtml-" + dcId).html('');
        jQuery('.dctitle').each(function() {
            jQuery(this).find('a').css('pointer-events', 'none');
        });
        jQuery.ajax({
            type: 'post',
            url: modulelink,
            data: 'action=ajax&customaction=getallsettindchost&dcid=' + dcId + '&dcName=' + dcName + '&serverid=' + jQuery('select[name="vcenterserver"]').val(),
            success: function(response) {
                jQuery('.dctitle').each(function() {
                    jQuery(this).find('a').css('pointer-events', 'auto');
                });
                jQuery('#loader-' + dcId).hide();
                jQuery("#dchtml-" + dcId).html(response);
            }
        });
    }
}

function saveHostSetting(obj, hostid, dcid, btntxt = "Save Changes") {
    jQuery(obj).css('pointer-events', 'none').html(btntxt + ' <i style="font-size: 14px;" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>');
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=saveHostSetting&dcid=' + dcid + '&serverid=' + jQuery('select[name="vcenterserver"]').val() + '&' + jQuery("#form-" + hostid).serialize(),
        success: function(response) {
            jQuery(obj).css('pointer-events', 'auto').html(btntxt);
            var result = jQuery.parseJSON(response);
            jQuery('#growls').append(result.message);

            setTimeout(function() {
                jQuery('.' + result.uniqid).fadeOut(300);
                jQuery('.' + result.uniqid).remove();
            }, 5000);
        }
    });
}

function getHostSetting(obj, hostid, hostname, dcid) {

    if (jQuery(obj).attr('class') == 'collapsed' || jQuery(obj).attr('class') === undefined) {
        jQuery('#loader-' + hostid).show();
        jQuery("#hosthtml-" + hostid).html('');
        jQuery('.hosttitle, .dctitle').each(function() {
            jQuery(this).find('a').css('pointer-events', 'none');
        });
        jQuery.ajax({
            type: 'post',
            url: modulelink,
            data: 'action=ajax&customaction=getallhostsetting&dcid=' + dcid + '&hostid=' + hostid + '&hostname=' + hostname + '&serverid=' + jQuery('select[name="vcenterserver"]').val(),
            success: function(response) {
                jQuery('.hosttitle, .dctitle').each(function() {
                    jQuery(this).find('a').css('pointer-events', 'auto');
                });
                jQuery('#loader-' + hostid).hide();
                jQuery("#hosthtml-" + hostid).html(response);
            }
        });
    }
}

function updateRDNS(obj, id) {
    jQuery(obj).val(jQuery.trim(jQuery(obj).val()));
    jQuery("#spinner" + id).show();
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=updaterdns&id=' + id + '&rdns=' + jQuery.trim(jQuery(obj).val()),
        success: function(response) {
            jQuery("#spinner" + id).hide();
            var result = jQuery.parseJSON(response);
            jQuery('#growls').append(result.message);

            setTimeout(function() {
                jQuery('.' + result.uniqid).fadeOut(300);
                jQuery('.' + result.uniqid).remove();
            }, 5000);
        }
    });
}

function updateDesc(obj, id) {
    jQuery("#descspinner" + id).show();
    jQuery.ajax({
        type: 'post',
        url: modulelink,
        data: 'action=ajax&customaction=updaterdesc&id=' + id + '&desc=' + jQuery(obj).val(),
        success: function(response) {
            jQuery("#descspinner" + id).hide();
            var result = jQuery.parseJSON(response);
            jQuery('#growls').append(result.message);

            setTimeout(function() {
                jQuery('.' + result.uniqid).fadeOut(300);
                jQuery('.' + result.uniqid).remove();
            }, 5000);
        }
    });
}