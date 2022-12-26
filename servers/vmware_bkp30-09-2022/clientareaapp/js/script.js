jQuery(document).ready(function () {
//    jQuery('.dropdown-action img').click(function () {
//        jQuery('.dropdown-action-menu').addClass('activepanel');
//    });
//    jQuery('body').click(function (e)
//    {
//        var container = jQuery('.dropdown-action-menu');
//        if (!container.is(e.target) && container.has(e.target).length === 0)
//        {
//            container.removeClass('activepanel');
//        }
//    });
    jQuery('.dropdown-action-menu').mouseleave(function () {
        jQuery(this).removeClass('activepanel');
    })
});

function toggleMenuBar(obj, id) {
    jQuery('#ipaction_' + id).toggleClass('activepanel');
}

function getFirewall(id, app, ip) {
    jQuery('#firewall_' + id).html('<img src="../modules/servers/vmware/clientareaapp/img/ajax-loader.gif">');
    jQuery.ajax({
        url: '',
        type: "POST",
        data: 'appajaxaction=getfirewall&ip=' + ip,
        success: function (response) {
            var result = jQuery.parseJSON(response);
            if (result.msg == 'createfirewall') {
                jQuery('#addfirewall_' + id).show();
                jQuery('#enablefirewall_' + id).hide();
                jQuery('#disablefirewall_' + id).hide();
                jQuery('#configurefirewall_' + id).hide();
                jQuery('#firewall_' + id).html('');
            } else {
                if (result.status == '1' && result.showbtn == 'yes') {
                    jQuery('#addfirewall_' + id).hide();
                    jQuery('#enablefirewall_' + id).hide();
                    jQuery('#disablefirewall_' + id).show();
                    jQuery('#removefirewall_' + id).show();
                    jQuery('#configurefirewall_' + id).show();
                    jQuery('#getfirewallrule_' + id).show();
                    jQuery('#firewall_' + id).html(result.msg);
                } else if (result.status == '0' && result.showbtn == 'yes') {
                    jQuery('#addfirewall_' + id).hide();
                    jQuery('#enablefirewall_' + id).show();
                    jQuery('#disablefirewall_' + id).hide();
                    jQuery('#removefirewall_' + id).show();
                    jQuery('#configurefirewall_' + id).show();
                    jQuery('#getfirewallrule_' + id).show();
                    jQuery('#firewall_' + id).html(result.msg);
                } else if (result.status == '0' && result.showbtn == 'no') {
                    jQuery('#addfirewall_' + id).hide();
                    jQuery('#enablefirewall_' + id).hide();
                    jQuery('#disablefirewall_' + id).hide();
                    jQuery('#removefirewall_' + id).hide();
                    jQuery('#configurefirewall_' + id).hide();
                    jQuery('#firewall_' + id).html(result.msg);
                }
            }
        }
    });
}

function createFireWall(obj, id, app, ip, textmsg) {
    jQuery('#ipaction_' + id).removeClass('activepanel');
    jQuery("#appresponse").html('&nbsp;<i class="fa fa-spinner fa-pulse"></i>');
    jQuery('#custon_tab_container').addClass('container-blur');
    var htmltext = '';
    jQuery.ajax({
        url: '',
        type: "POST",
        data: 'appajaxaction=createfirewall&ip=' + ip,
        success: function (response) {
            var result = jQuery.parseJSON(response);
            if (result.status == 'error') {
                htmltext = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + result.msg + '<div>'
                jQuery('#appresponse').html(htmltext);
            } else {
                htmltext = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + textmsg + '<div>'
                getFirewall(id, app, ip);
                jQuery('#appresponse').html(htmltext);
            }
            jQuery('#custon_tab_container').removeClass('container-blur');
        }
    });
}

function enableFireWall(obj, id, app, ip, textmsg) {
    jQuery('#ipaction_' + id).removeClass('activepanel');
    jQuery("#appresponse").html('&nbsp;<i class="fa fa-spinner fa-pulse"></i>');
    jQuery('#custon_tab_container').addClass('container-blur');
    var htmltext = '';
    jQuery.ajax({
        url: '',
        type: "POST",
        data: 'appajaxaction=enablefirewall&ip=' + ip,
        success: function (response) {
            var result = jQuery.parseJSON(response);
            if (result.status == 'error') {
                htmltext = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + result.msg + '<div>'
                jQuery('#appresponse').html(htmltext);
            } else {
                htmltext = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + textmsg + '<div>'
                getFirewall(id, app, ip);
                jQuery('#enablefirewall_' + id).hide();
                jQuery('#appresponse').html(htmltext);
            }
            jQuery('#custon_tab_container').removeClass('container-blur');
        }
    });
}

function disableFireWall(obj, id, app, ip, textmsg) {
    jQuery('#ipaction_' + id).removeClass('activepanel');
    var cnfrm = confirm("Are you sure want to disable firwall on this IP?");
    if (cnfrm) {
        jQuery("#appresponse").html('&nbsp;<i class="fa fa-spinner fa-pulse"></i>');
        jQuery('#custon_tab_container').addClass('container-blur');
        var htmltext = '';
        jQuery.ajax({
            url: '',
            type: "POST",
            data: 'appajaxaction=disablefirewall&ip=' + ip,
            success: function (response) {
                var result = jQuery.parseJSON(response);
                if (result.status == 'error') {
                    htmltext = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + result.msg + '<div>'
                    jQuery('#appresponse').html(htmltext);
                } else {
                    htmltext = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + textmsg + '<div>'
                    getFirewall(id, app, ip);
                    jQuery('#disablefirewall_' + id).hide();
                    jQuery('#appresponse').html(htmltext);
                }
                jQuery('#custon_tab_container').removeClass('container-blur');
            }
        });
    }
}

function removeFireWall(obj, id, app, ip) {

    var cnfrm = confirm("Are you sure want to remove firwall from this IP?");
    if (cnfrm) {
        jQuery("#appresponse").html('&nbsp;<i class="fa fa-spinner fa-pulse"></i>');
        jQuery('#custon_tab_container').addClass('container-blur');
        var htmltext = '';
        jQuery.ajax({
            url: '',
            type: "POST",
            data: 'appajaxaction=removefirewall&ip=' + ip,
            success: function (response) {
                var result = jQuery.parseJSON(response);
                if (result.status == 'error') {
                    htmltext = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + result.msg + '<div>'
                    jQuery('#appresponse').html(htmltext);
                } else {
                    htmltext = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>Firewall has been successfully removed.<div>'
                    getFirewall(id, app, ip);
                    jQuery('#removefirewall_' + id).hide();
                    jQuery('#appresponse').html(htmltext);
                }
                jQuery('#custon_tab_container').removeClass('container-blur');
            }
        });
    }
}

function getReverse(id, app, ip) {
    jQuery('#reverse_' + id).html('<img src="../modules/servers/vmware/clientareaapp/img/ajax-loader.gif">');
    jQuery.ajax({
        url: '',
        type: "POST",
        data: 'appajaxaction=getipreverse&ip=' + ip,
        success: function (response) {
            jQuery('#reverse_' + id).html(response);
            if (response != '-') {
                jQuery('#addreverse_' + id).after('<li id="removereverse_' + id + '"><a href="javascript:void(0);" onclick="removeReverse(this, \'' + ip + '\', \'' + id + '\', \'' + app + '\');">' + jQuery('#reverse_' + id).attr('msg') + '</a></li>');
            } else {
                jQuery('#removereverse_' + id).remove();
            }
        }
    });
}

function openIpModal(obj, ip, num, appname) {
    jQuery('#manageipModal').modal('show');
    jQuery('#num, #appname').remove();
    jQuery('#ipmanagebody').append('<span id="num" style="display:none;">' + num + '</span><span id="appname" style="display:none;">' + appname + '</span>');
    jQuery('#manageipModal .modal-header h4 span').text(' (' + ip + ')');
//    jQuery('#ip').val(ip);
    jQuery('#ipreverse').val(ip);
}

function addReverse() {
    jQuery('#ipsts').html('');
    if (jQuery('#reverse').val() == '') {
        jQuery('#ipsts').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + jQuery('#app_reverse_req').text() + '<div>');
        jQuery('#reverse').focus();
    } else {
        jQuery('#ipsts').html('&nbsp;<i class="fa fa-spinner fa-pulse ipreverse"></i>');
        jQuery('#addReverse').attr('disabled', true);
        jQuery.ajax({
            url: '',
            type: "POST",
            data: 'appajaxaction=setreverse&reverse=' + jQuery('#reverse').val() + '&ip=' + jQuery('#ipreverse').val(),
            success: function (response) {
                jQuery('#addReverse').attr('disabled', false);
                var htmltext = '';
                if (response == 'success') {
                    htmltext = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + jQuery('#app_added_success').text() + '<div>'
                    var num = jQuery('#num').text();
                    var appname = jQuery('#appname').text();
                    jQuery('#manageipModal').modal('hide');
                    jQuery('#reverse').val('');
//                    jQuery('#addreverse_' + num).after('<li id="removereverse_' + num + '"><a href="javascript:void(0);" onclick="removeReverse(this, \'' + jQuery('#ip').val() + '\', \'' + jQuery('#ipreverse').val() + '\', \'' + num + '\', \'' + appname + '\');">Delete Reverse</a></li>');
                    getReverse(num, appname, jQuery('#ipreverse').val());
                } else
                    htmltext = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + response + '<div>'
                jQuery('#ipsts').html(htmltext);
            }
        });
    }
    return false;
}

function removeReverse(obj, ip, num, appname) {
    jQuery('#ipaction_' + num).removeClass('activepanel');
    var cnfrm = confirm("Are you sure want to remove this reverse?");
    if (cnfrm) {
        jQuery("#appresponse").html('&nbsp;<i class="fa fa-spinner fa-pulse"></i>');
        jQuery('#custon_tab_container').addClass('container-blur');
        jQuery.ajax({
            url: '',
            type: "POST",
            data: 'appajaxaction=removereverse&ip=' + ip,
            success: function (response) {
                jQuery('#appresponse').html('');
                var htmltext = '';
                if (response == 'success') {
                    htmltext = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>Reverse has been successfully removed.<div>';
                    getReverse(num, appname, ip);
                    jQuery('#removereverse_' + num).remove();
                } else
                    htmltext = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + response + '<div>'
                jQuery('#appresponse').html(htmltext);
                jQuery('#custon_tab_container').removeClass('container-blur');
            }
        });
    }
}

function configureFireWall(obj, id, app, ip) {
    jQuery('#configFirewallModal').modal('show');
    jQuery('#confignum, #configappname').remove();
    jQuery('#configfirewallsts').html('');
    jQuery('#configfirewallbody').append('<span id="confignum" style="display:none;">' + id + '</span><span id="configappname" style="display:none;">' + app + '</span>');
    jQuery('#firewallip').val(ip);
}

function manageFields(obj) {
    jQuery('.protocole, .flags').hide();
    if (jQuery(obj).val() == 'tcp') {
        jQuery('.protocole, .flags').show();
    } else if (jQuery(obj).val() == 'udp') {
        jQuery('.protocole').show();
    }
}

function configureFirewallOnIp() {
    jQuery('#configfirewallsts').html('');
    if (jQuery('#priority').val() == '') {
        jQuery('#configfirewallsts').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + jQuery('#app_priority_req').text() + '<div>');
    } else if (!jQuery('#deny').prop('checked') && !jQuery('#permit').prop('checked')) {
        jQuery('#configfirewallsts').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + jQuery('#app_action_req').text() + '<div>');
    } else if (jQuery('#protocol').val() == '') {
        jQuery('#configfirewallsts').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + jQuery('#app_protocol_req').text() + '<div>');
    } else if (jQuery('#fragments').prop('checked') && (jQuery('#sourceport').val() != '' || jQuery('#destinationport').val() != '')) {
        jQuery('#configfirewallsts').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + jQuery('#app_firewall_rule_fregment_error').text() + '<div>');
    } else {
        jQuery('#configfirewallsts').html('&nbsp;<i class="fa fa-spinner fa-pulse ipreverse"></i>');
        jQuery('#configfirwal').attr('disabled', true);
        jQuery.ajax({
            url: '',
            type: "POST",
            data: 'appajaxaction=configurefirewallrule&' + jQuery('#configureFirewallForm').serialize(),
            success: function (response) {
                var result = jQuery.parseJSON(response);
                jQuery('#configfirwal').attr('disabled', false);
                var htmltext = '';
                if (result.status == 'success') {
                    jQuery('#getfirewallrule_' + jQuery('#confignum').text()).show();
                    htmltext = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + jQuery('#app_firewall_rule_added_success').text() + '<div>'
                } else
                    htmltext = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + result.msg + '<div>'
                jQuery('#configfirewallsts').html(htmltext);
            }
        });
    }
}

function getFireWallRule(obj, id, app, ip) {
    jQuery('#firewallRuleModal').modal('show');
    jQuery('#firewallrulebody').html('');
    jQuery('#firewallrulests').html('&nbsp;<i class="fa fa-spinner fa-pulse ipreverse"></i>');
    jQuery('#firewallRuleModal .modal-header h4').text('Firewall rules (' + ip + ')');
    jQuery.ajax({
        url: '',
        type: "POST",
        data: 'appajaxaction=getfirewallrule&ip=' + ip,
        success: function (response) {
            jQuery('#firewallrulests').html('');
            jQuery('#firewallrulebody').html(response);
        }
    });
}
function removeFirewallRule(obj, sequence, ip, textmsg) {
    jQuery('#firewallrulests').html('&nbsp;<i class="fa fa-spinner fa-pulse ipreverse"></i>');
    jQuery.ajax({
        url: '',
        type: "POST",
        data: 'appajaxaction=removefirewallrule&ip=' + ip + '&sequence=' + sequence,
        success: function (response) {
            jQuery('#firewallrulests').html('');
            var result = jQuery.parseJSON(response);
            if (result.status == 'success') {
                jQuery('#firewallrulests').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + textmsg + '<div>');
                jQuery(obj).parent().parent().remove();
            } else {
                jQuery('#firewallrulests').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>' + result.msg + '<div>');
            }
        }
    });
}