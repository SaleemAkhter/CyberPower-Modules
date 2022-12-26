// Empty JS for your own code to be here

jQuery(document).ready(function() {
    
    setTimeout(function() {
        jQuery('#tabGraphfirst').trigger('click');
        jQuery('.graphtabs li').trigger('click');
        jQuery('.graphtabs li:first').addClass('active');
    },10);
    jQuery("#vmTabContent1").show();

    getDcHost();

    jQuery('#checkedall').click(function() {

        if (jQuery(this).prop('checked'))

            jQuery('.multi_checkbox').prop('checked', true);

        else

            jQuery('.multi_checkbox').prop('checked', false);

    });



    jQuery('#inner_tab4 li').click(function() {



        jQuery('.sel_err').remove();

        var tabnum = jQuery(this).index();

        jQuery("#snapshotresponseMessage").html('');

        jQuery('#vmTabContent40').css('display', 'block')

        jQuery('.tab_content4').css('display', 'none');

        jQuery('#vmTabContent4' + tabnum).css('display', 'block');

        jQuery('#inner_tab4 li').removeClass("active");

        jQuery(this).addClass("active");

        if (jQuery('#vmTabContent4' + tabnum).attr('action') == 'list') {

            VmSanpshotList(jQuery('#vmTabContent4' + tabnum).attr('name'), jQuery('#vmTabContent4' + tabnum).attr('loading'));

        }

    });

    jQuery('#reinstall_dc').change(function() {

        getOsversion('1');

    });

});



// Main tab manager



function mainTabs(obj, tabnum) {

    jQuery('.vmtabs').css('display', 'none');

    jQuery('#vmTabContent' + tabnum).css('display', 'block');

    jQuery('.manage_tab_menu li a').removeClass("selected");

    jQuery(obj).find('a').eq(0).addClass("selected");

    if (tabnum == '1') {

        jQuery('#inner_tab1').find('li').eq(0).click();

    }

    jQuery('.tab_content' + tabnum).css('display', 'none');

    jQuery('#inner_tab0 li').removeClass('active');

    jQuery('#inner_tab2 li').removeClass('active');

    jQuery('#inner_tab3 li').removeClass('active');

    jQuery('#inner_tab4 li').removeClass('active');

    jQuery(".detailtab").css('display', 'none');

    jQuery("#detailtab" + tabnum).css('display', 'block');

    jQuery('#inner_tab0').find('li').eq(0).addClass('active');

    jQuery('#inner_tab2').find('li').eq(0).addClass('active');

    jQuery('#inner_tab3').find('li').eq(0).addClass('active');

    jQuery('#inner_tab4').find('li').eq(0).addClass('active');

    jQuery('#vmTabContent' + tabnum).find('.tab_content' + tabnum).eq(0).css('display', 'block');

    if (tabnum == '5') {

        if (jQuery('#vm_dc').val() != '')

            getDcHost(obj);

    }

}



// Detail tab

function detailTab(obj, tabnum) {

    jQuery('#inner_tab0 li').removeClass("active");

    jQuery(".detailtab").css('display', 'none');

    jQuery("#detailtab" + tabnum).css('display', 'block');

    jQuery(obj).addClass("active");

}

// Power tab maanger



function powerTab(obj, tabnum) {

    jQuery("#responseMessage").html('');

    jQuery('.tab_content2').css('display', 'none');

    jQuery('#vmTabContent2' + tabnum).css('display', 'block');

    jQuery('#inner_tab2 li').removeClass("active");

    jQuery(obj).addClass("active");

}

function managePowerTab(obj, tabnum, tabName) {
    jQuery("#managePowertabsModal").modal('show');
    jQuery("#responseMessage").html('');
    jQuery("#managePowertabsModal #responseMessage").html('');

    jQuery('.tab_content2').css('display', 'none');
    
    jQuery('#managePowertabsModal #power_heading').text(tabName);
    jQuery('#managePowertabsModal #vmTabContent2' + tabnum).css('display', 'block');
}


// Tool tab manager

function toolTab(obj, tabnum) {

    jQuery("#toolresponseMessage").html('');

    jQuery('#vmTabContent3 .tab_content3').css('display', 'none');

    jQuery('#vmTabContent3' + tabnum).css('display', 'block');

    jQuery('#vmTabContent3 #inner_tab3 li').removeClass("active");

    jQuery(obj).addClass("active");

}

// Power on/off

function powerOff(obj, status, msg) {

    smoke.confirm(msg, function(e) {

        if (e) {

            jQuery("#powerResp").html('&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

            jQuery('#custon_tab_container').addClass('container-blur');

            jQuery('#poweredOn').removeClass('poweredOn');

            jQuery(obj).addClass(status);

            jQuery.ajax({

                url: '',

                type: "POST",

                data: 'ajaxaction=poweroff&class=' + status + '&status=' + status,

                success: function(response1) {

                    jQuery('#custon_tab_container').removeClass('container-blur');

                    var response = jQuery.parseJSON(response1);

                    if (response.message != 'success') {

                        jQuery('#managePowertabsModal #poweredOn').addClass(response.status);

                        jQuery(obj).removeClass(status);

                        //jQuery("#powerResp").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');
                        jQuery("#managePowertabsModal #powerResp").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');
                        
                    } else {

                        VmDetail();

                        getBtns('');

                        jQuery('#managePowertabsModal #poweredOn').removeClass('poweredOn');

                        jQuery(obj).addClass(response.status);

                        //jQuery('#powerResp').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');
                        jQuery('#managePowertabsModal #powerResp').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');
                        setTimeout(function() {
                            location.reload();
                        }, 4000);
                    }

                }

            });

        } else {

        }

    }, {

        ok: 'Yes',

        cancel: 'Not',

        classname: "custom-class",

        reverseButtons: true

    });

}



function powerOn(obj, status) {

    jQuery("#powerResp").html('&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

    jQuery('#custon_tab_container').addClass('container-blur');

    jQuery('#poweredOff').removeClass('poweredOff');

    jQuery(obj).addClass(status);

    jQuery.ajax({

        url: '',

        type: "POST",

        data: 'ajaxaction=poweron&class=' + status + '&status=' + status,

        success: function(response1) {

            jQuery('#custon_tab_container').removeClass('container-blur');

            var response = jQuery.parseJSON(response1);

            if (response.message != 'success') {

                jQuery('#managePowertabsModal #poweredOff').addClass(response.status);

                jQuery(obj).removeClass(status);

                //jQuery("#powerResp").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');
                jQuery("#managePowertabsModal #powerResp").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');
                
            } else {

                VmDetail();

                getBtns('');

                jQuery('#managePowertabsModal #poweredOff').removeClass('poweredOff');

                jQuery(obj).addClass(response.status);

                //jQuery('#powerResp').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');
                jQuery('#managePowertabsModal #powerResp').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');
                setTimeout(function() {
                    location.reload();
                }, 4000);
            }

        }

    });

}



function power_button_action(obj, btntext, status, msg) {

    var page_url = jQuery('#page_url').val();

    if (status == 'poweredOn') {

        smoke.confirm(msg, function(e) {

            if (e) {

                jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

                jQuery('#custon_tab_container').addClass('container-blur');

                //jQuery("#responseMessage").html('');
                jQuery("#managePowertabsModal #responseMessage").html('');

                var action = 'power_off';



                jQuery.ajax({

                    url: '',

                    type: "POST",

                    data: 'ajaxaction=poweroff&btn=' + btntext + '&status=' + status,

                    success: function(response1) {

                        jQuery(obj).html(btntext);

                        jQuery('#custon_tab_container').removeClass('container-blur');

                        var response = jQuery.parseJSON(response1);

                        if (response.message != 'success') {

                            //jQuery("#responseMessage").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');
                            jQuery("#managePowertabsModal #responseMessage").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');

                        } else {

                            getBtns('power');

                            VmDetail();

                            //jQuery('#responseMessage').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');
                            jQuery('#managePowertabsModal #responseMessage').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');
                            setTimeout(function() {
                                location.reload();
                            }, 4000);
                        }

                        jQuery("#powerButtonDiv").html(response.button);

                    }

                });

            } else {

            }

        }, {

            ok: 'Yes',

            cancel: 'Not',

            classname: "custom-class",

            reverseButtons: true

        });

    } else {

        jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

        jQuery('#custon_tab_container').addClass('container-blur');

        //jQuery("#responseMessage").html('');
        jQuery("#managePowertabsModal #responseMessage").html('');

        var action = 'power_on';



        jQuery.ajax({

            url: '',

            type: "POST",

            data: 'ajaxaction=poweron&btn=' + btntext + '&status=' + status,

            success: function(response1) {

                jQuery('#custon_tab_container').removeClass('container-blur');

                var response = jQuery.parseJSON(response1);

                if (response.message != 'success') {

                    //jQuery("#responseMessage").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');
                    jQuery("#managePowertabsModal #responseMessage").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');

                } else {

                    getBtns('power');

                    VmDetail();

                   // jQuery('#responseMessage').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');
                    jQuery('#managePowertabsModal #responseMessage').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');
                    setTimeout(function() {
                        location.reload();
                    }, 4000);
                }

                jQuery("#powerButtonDiv").html(response.button);

            }

        });

    }



}



// Pause/Unpause



function pause_button_action(obj, btntext, status, msg) {

    var page_url = jQuery('#page_url').val();

    if (status == 'poweredOn') {

        smoke.confirm(msg, function(e) {

            if (e) {

                jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

                jQuery('#custon_tab_container').addClass('container-blur');

                //jQuery("#responseMessage").html('');
                jQuery("#managePowertabsModal #responseMessage").html('');

                var action = 'paused';



                jQuery.ajax({

                    url: '',

                    type: "POST",

                    data: 'ajaxaction=paused&btn=' + btntext + '&status=' + status,

                    success: function(response1) {

                        var response = jQuery.parseJSON(response1);

                        if (response.message != 'success') {

                            //jQuery("#responseMessage").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');
                            jQuery("#managePowertabsModal #responseMessage").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');

                        } else {

                            getBtns('pause');

                            setTimeout(function() {
                                location.reload();
                            }, 4000);
                            //VmDetail();

                            //jQuery('#responseMessage').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');
                            jQuery('#managePowertabsModal #responseMessage').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');

                        }

                        jQuery(obj).html(btntext);

                        jQuery('#custon_tab_container').removeClass('container-blur');

                        jQuery("#pauseButtonDiv").html(response.button);

                    }

                });

            } else {

            }

        }, {

            ok: 'Yes',

            cancel: 'Not',

            classname: "custom-class",

            reverseButtons: true

        });

    } else {

        jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

        jQuery('#custon_tab_container').addClass('container-blur');

        //jQuery("#responseMessage").html('');
        jQuery("#managePowertabsModal #responseMessage").html('');

        var action = 'unpaused';



        jQuery.ajax({

            url: '',

            type: "POST",

            data: 'ajaxaction=unpaused&btn=' + btntext + '&status=' + status,

            success: function(response1) {

                jQuery(obj).html(btntext);

                jQuery('#custon_tab_container').removeClass('container-blur');

                var response = jQuery.parseJSON(response1);

                if (response.message != 'success') {

                    //jQuery("#responseMessage").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');
                    jQuery("#managePowertabsModal #responseMessage").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');

                } else {

                    getBtns('pause');
                    setTimeout(function() {
                        location.reload();
                    }, 4000);
                    //VmDetail();

                   // jQuery('#responseMessage').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');
                    jQuery('#managePowertabsModal #responseMessage').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');

                }

                jQuery("#pauseButtonDiv").html(response.button);

            }

        });

    }

}



// Mount/unmount



function mount_button_action(obj, btntext, status, url) {

    jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

    jQuery('#custon_tab_container').addClass('container-blur');

    jQuery("#toolresponseMessage").html('');

    var page_url = jQuery('#page_url').val();

    var action = 'mount';

    if (status == 'true') {

        action = 'unmount';

    }

    jQuery.ajax({

        url: '',

        type: "POST",

        data: 'ajaxaction=' + action + '&btn=' + btntext + '&status=' + status,

        success: function(response1) {

            VmDetail();

            jQuery(obj).html(btntext);

            jQuery('#custon_tab_container').removeClass('container-blur');

            var response = jQuery.parseJSON(response1);

            if (response.message != 'success') {

                jQuery("#toolresponseMessage").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.message + '</div>');

            } else {

                getBtns('mount');

                if (response.action == 'mount') {

                    jQuery('#toolresponseMessage').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + ' ' + response.install_msg + ' <a target="_blank" href="' + response.console_link + '">Click here<a/></div>');

                } else {

                    jQuery('#toolresponseMessage').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>' + response.statusmsg + '</div>');

                }



            }

            jQuery("#mountButtonDiv").html(response.button);



        }

    });

}



// Soft reboot



function soft_reboot_button_action(obj, btntext, msg) {

    smoke.confirm(msg, function(e) {

        if (e) {

            jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

            jQuery('#custon_tab_container').addClass('container-blur');

            jQuery.ajax({

                url: '',

                type: "POST",

                data: 'ajaxaction=softreboot',

                success: function(response) {

                    VmDetail();

                    jQuery(obj).html(btntext);

                    jQuery('#custon_tab_container').removeClass('container-blur');

                    //jQuery('#responseMessage').html(response);
                    jQuery('#managePowertabsModal #responseMessage').html(response);

                }

            });

        } else {



        }

    }, {

        ok: 'Yes',

        cancel: 'Not',

        classname: "custom-class",

        reverseButtons: true

    });

}



// Hard reboot



function hard_reboot_button_action(obj, btntext, msg) {

    smoke.confirm(msg, function(e) {

        if (e) {

            jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

            jQuery('#custon_tab_container').addClass('container-blur');

            jQuery.ajax({

                url: '',

                type: "POST",

                data: 'ajaxaction=hardreboot',

                success: function(response) {

                    VmDetail();

                    jQuery(obj).html(btntext);

                    jQuery('#custon_tab_container').removeClass('container-blur');

                    //jQuery('#responseMessage').html(response);
                    jQuery('#managePowertabsModal #responseMessage').html(response);

                }

            });

        } else {



        }

    }, {

        ok: 'Yes',

        cancel: 'Not',

        classname: "custom-class",

        reverseButtons: true

    });

}



// Upgrade Vm



function upgrade_button_action(obj, btntext, msg) {

    smoke.confirm(msg, function(e) {

        if (e) {

            jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

            jQuery('#custon_tab_container').addClass('container-blur');

            jQuery.ajax({

                url: '',

                type: "POST",

                data: 'ajaxaction=upgrade',

                success: function(response) {

                    VmDetail();

                    jQuery(obj).html(btntext);

                    jQuery('#custon_tab_container').removeClass('container-blur');

                    jQuery('#toolresponseMessage').html(response);

                }

            });

        } else {



        }

    }, {

        ok: 'Yes',

        cancel: 'Not',

        classname: "custom-class",

        reverseButtons: true

    });

}



// Create Snapshot



function create_snap_shot_button_action(obj, btntext) {

    var snapshot_name = jQuery('#snapshot_name').val();

    var snapshot_desc = jQuery('#snapshot_desc').val();

    if (snapshot_name == '') {

        jQuery('#snapshot_desc').css('border', '1px solid #ccc');

        jQuery('#snapshot_name').focus();

        jQuery('#snapshot_name').css('border', '1px solid #ff0000');

        return false;

    } else if (snapshot_desc == '') {

        jQuery('#snapshot_name').css('border', '1px solid #ccc');

        jQuery('#snapshot_desc').focus();

        jQuery('#snapshot_desc').css('border', '1px solid #ff0000');

        return false;

    } else {

        jQuery('#snapshot_name').css('border', '1px solid #ccc');

        jQuery('#snapshot_desc').css('border', '1px solid #ccc');



        jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

        jQuery('#custon_tab_container').addClass('container-blur');

        jQuery.ajax({

            url: '',

            type: "POST",

            data: jQuery("#createsnapshotForm").serialize(),

            success: function(response) {

                var result = jQuery.parseJSON(response);

                jQuery(obj).html(btntext);

                jQuery('#custon_tab_container').removeClass('container-blur');

                jQuery('#snapshotresponseMessage').html(result.msg);

                if (result.status == 'success') {

                    setTimeout(function() {

                        jQuery('#inner_tab4').find('li').eq(1).trigger('click');

                    }, 2000);

                }

            }

        });

    }

}



// Vm detauil



function VmDetail() {

    jQuery.ajax({

        url: '',

        type: "POST",

        data: 'ajaxaction=detail',

        success: function(response) {

            var result = jQuery.parseJSON(response);

            jQuery('#vmdetailcont').html(result.html);

        }

    });

}



// Rename snapshot



function rename_snap_shot_button_action(obj, id, savetext, btntext, loadingtext, vmname) {

    jQuery('.sel_err').remove();

    jQuery(obj).html(savetext);



    var inputVal = jQuery('#snapShotList').find('.updateinput').attr('id');



    if (!inputVal) {

        jQuery('#name_' + id).attr('class', 'updateinput');

        jQuery('#name_' + id).css('background', '#fff');

        jQuery('#name_' + id).attr('readonly', false);

        jQuery('#desc_' + id).css('background', '#fff');

        jQuery('#desc_' + id).attr('readonly', false);

    } else {



        if (jQuery('#name_' + id).val() == '') {

            jQuery('#name_' + id).css('border', '1px solid #ff0000');

            jQuery('#name_' + id).focus();

            return false;

        } else if (jQuery('#desc_' + id).val() == '') {

            jQuery('#name_' + id).css('border', 'none');

            jQuery('#desc_' + id).css('border', '1px solid #ff0000');

            jQuery('#desc_' + id).focus();

            return false;

        } else {

            jQuery('#name_' + id).css('border', 'none');

            jQuery('#desc_' + id).css('border', 'none');

            jQuery(obj).html(savetext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

            jQuery('#custon_tab_container').addClass('container-blur');

            jQuery.ajax({

                url: '',

                type: "POST",

                data: 'ajaxaction=snapshot_update&name=' + jQuery('#name_' + id).val() + '&desc=' + jQuery('#desc_' + id).val() + '&spid=' + id + '&vmname=' + jQuery('#snapShotList').attr('name') + '&org_name=' + jQuery("#orgional_name_" + id).val(),

                success: function(response) {

                    var result = jQuery.parseJSON(response);

                    if (result.status == 'error') {

                        jQuery('#name_' + id).val(jQuery("#orgional_name_" + id).val());

                        jQuery('#desc_' + id).val(jQuery("#orgional_desc_" + id).val());

                    } else {

                        setTimeout(function() {

                            VmSanpshotList(vmname, loadingtext);

                        }, 3000);



                    }

                    jQuery('#custon_tab_container').removeClass('container-blur');

                    jQuery('#snapshotresponseMessage').html(result.msg);



                    jQuery(obj).html(btntext);

                    jQuery('#name_' + id).attr('class', 'readonlyinput');

                    jQuery('#name_' + id).css('background', '#f5f5f5');

                    jQuery('#name_' + id).attr('readonly', true);

                    jQuery('#desc_' + id).css('background', '#f5f5f5');

                    jQuery('#desc_' + id).attr('readonly', true);

                }

            });

        }

    }

}



// revert from latest snap shot



function revert_from_latest_snap_shot_button_action(obj, vmname, btntext, msg) {

    jQuery('.sel_err').remove();

    smoke.confirm(msg, function(e) {

        if (e) {

            jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

            jQuery('#custon_tab_container').addClass('container-blur');

            jQuery.ajax({

                url: '',

                type: "POST",

                data: 'ajaxaction=revert_latest_sp&vmname=' + vmname,

                success: function(response) {

                    jQuery(obj).html(btntext);

                    jQuery('#custon_tab_container').removeClass('container-blur');

                    jQuery('#snapshotresponseMessage').html(response);

                }

            });

        } else {



        }

    }, {

        ok: 'Yes',

        cancel: 'Not',

        classname: "custom-class",

        reverseButtons: true

    });

}



// Remove all snap shots



function remove_all_snap_shot_button_action(obj, vmname, btntext, msg, loadingtext) {

    jQuery('.sel_err').remove();

    smoke.confirm(msg, function(e) {

        if (e) {

            jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

            jQuery('#custon_tab_container').addClass('container-blur');

            jQuery.ajax({

                url: '',

                type: "POST",

                data: 'ajaxaction=remove_all_sp&vmname=' + vmname,

                success: function(response) {

                    jQuery('.snapshotListButtonDiv a').addClass('btn_disabled');

                    jQuery(obj).html(btntext);

                    jQuery('#custon_tab_container').removeClass('container-blur');

                    jQuery('#snapshotresponseMessage').html(response);

                    VmSanpshotList(vmname, loadingtext);

                }

            });

        } else {



        }

    }, {

        ok: 'Yes',

        cancel: 'Not',

        classname: "custom-class",

        reverseButtons: true

    });

}



// Snap shot list



function VmSanpshotList(vmname, loadingtext) {

    jQuery('.sel_err').remove();

    jQuery('#snapShotList').html('<tr role="row" class="odd"><td colspan="100%" style="text-align:center;">' + loadingtext + '</td></tr>');

    jQuery.ajax({

        url: '',

        type: "POST",

        data: 'ajaxaction=snapshot_list',

        success: function(response) {

            if (response == '' || response == 'Array') {

                jQuery('.snapshotListButtonDiv a').addClass('btn_disabled');

                jQuery('#snapShotList').html('<tr><td colspan="100%" style="color: #ff0000;">List not found</td></tr>');

            } else {

                jQuery('.snapshotListButtonDiv a').removeClass('btn_disabled');

                jQuery('#snapShotList').html(response);

            }

        }

    });

}



// Remove selected snapshot



function remove_multiple_snap_shot_button_action(obj, vmname, btntext, msg, loadingtext) {

    smoke.confirm(msg, function(e) {

        if (e) {



            var selected = '';

            jQuery('.multi_checkbox').each(function() {

                if (jQuery(this).prop('checked')) {

                    selected = 'yes';

                }

            });



            if (selected == '') {

                jQuery('.sel_err').remove();

                jQuery('#removeAllForm').before('<p class="sel_err"><font color="red">At least one option must be selected.</font><p>');

            } else {

                jQuery('.sel_err').remove();

                jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

                jQuery('#custon_tab_container').addClass('container-blur');

                var removeChild = 'no';

                if (jQuery('#removechild').prop('checked')) {

                    removeChild = 'yes';

                }

                jQuery.ajax({

                    url: '',

                    type: "POST",

                    data: jQuery('#removeAllForm').serialize() + '&child=' + removeChild + '&ajaxaction=delete_multi',

                    success: function(response) {

                        jQuery('#custon_tab_container').removeClass('container-blur');

                        jQuery('#snapshotresponseMessage').html(response);

                        jQuery('#removechild').prop('checked', false);

                        jQuery(obj).html(btntext);

                        setTimeout(function() {

                            VmSanpshotList(vmname, loadingtext);

                        }, 1000);

                        setTimeout(function() {

                            jQuery('#snapshotresponseMessage').html('');

                        }, 10000);

                    }

                });



                i++;

            }

        } else {



        }

    }, {

        ok: 'Yes',

        cancel: 'Not',

        classname: "custom-class",

        reverseButtons: true

    });



}



// get all btn on any hit



function getBtns(action) {

    jQuery.ajax({

        url: '',

        type: "POST",

        data: 'ajaxaction=btns&crntaction=' + action,

        success: function(response) {

            var result = jQuery.parseJSON(response);

            if (action != 'power') {

                jQuery('#managePowertabsModal #powerButtonDiv').html(result.powerbtn);

            }

            if (action != 'pause') {

                jQuery('#managePowertabsModal #pauseButtonDiv').html(result.pausebtn);

            }

            if (action != 'mount') {

                jQuery('#mountButtonDiv').html(result.mountbtn);

            }

        }

    });

}



function getOsversion(fromExisting) {
    //console.log(fromExisting);
    //var osValue = jQuery("select[name='os_name']").val();
    var osValue = jQuery("#managePowertabsModal select[name='os_name']").val();
    //console.log(osValue);
    var pid = jQuery("#reinstallVmWare input[name='pid']").val();
    //var pid = jQuery("#managePowertabsModal #reinstallVmWare input[name='pid']").val();
    //console.log('pid',pid);
    var sid = jQuery("#reinstallVmWare input[name='sid']").val();
    //console.log('sid',sid);
    var api_server = jQuery("#reinstallVmWare input[name='api_server']").val();
    //console.log('api_server',api_server);
    var datacenter = jQuery("input[name='vm_datacenter']").val();
    //console.log('datacenter',datacenter);
    jQuery("#managePowertabsModal #os_version").find("option").remove();

    jQuery("#managePowertabsModal #os_version").append("<option value>Loading...</option>");

    jQuery.ajax({

        type: "post",

        url: "",

        data: "ajaxaction=getoslist&reinstall=1&fromexist=" + fromExisting + "&os_list=" + osValue + "&pid=" + pid + "&dc=" + datacenter + "&api_server=" + api_server + "&serviceid=" + sid,

        success: function(response) {
            console.log(response)
            jQuery("#managePowertabsModal #os_version").find("option").remove();

            jQuery("#managePowertabsModal #os_version").append("<option value>Select</option>" + response);

        }

    });

}



function reinstall_button_action(obj, btntext, msg, url, sid) {
    if (jQuery("#os_version").val() == '') {

        jQuery("#os_version").css('border', '1px solid #ff0000');

        jQuery("#os_version").focus();

        return false;

    }

    smoke.confirm(msg, function(e) {

        if (e) {

            jQuery("#os_version").css('border', '1px solid #ccc');

            jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

            jQuery('#custon_tab_container').addClass('container-blur');

            try {

                jQuery.ajax({

                    url: '',

                    type: "POST",

                    data: jQuery('#managePowertabsModal #reinstallVmWare').serialize(),

                    async: true,

                    success: function(response) {

                        var result = jQuery.parseJSON(response);

                        jQuery('#custon_tab_container').removeClass('container-blur');

                        jQuery('#managePowertabsModal #vmresponsediv').html(result.msg);

                        jQuery(obj).html(btntext);

                        if (result.status == 'success') {

                            setTimeout(function() {

                                location.reload();

                            }, 4000);

                        }

                    },

                    error: function(xhr, status, error) {
                        jQuery("#managePowertabsModal #vmresponsediv").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Error! ' + xhr.status + '</div>');
                    }

                });

            } catch (e) {
                jQuery("#managePowertabsModal #vmresponsediv").html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>Error! ' + e.message + '</div>');

            }

        }

    }, {

        ok: 'Yes',

        cancel: 'Not',

        classname: "custom-class",

        reverseButtons: true

    });

}



// Get admin side server deatil



function getServerDetail(obj) {

    jQuery('#getServerDetail').addClass('fa-spin');

    jQuery('#getServerDetail').css('pointer-events', 'none');

    jQuery.ajax({

        url: '',

        type: "POST",

        data: 'getserverDetail=detail',

        success: function(response) {

            jQuery('#ajaxresponse').html(response);

            jQuery('#serverlist').html(jQuery('#ajaxresponse .serverlist').html());

            jQuery('#ajaxresponse').html('');

        }

    });

}



// Migrate Vm



function migrate_button_action(obj, btntext, vnmname, systemUrl, alerttext) {

    if (jQuery('select[name="vm_host_name"]').val() == '') {

        jQuery('select[name="vm_host_name"]').css('border', '1px solid #ff0000');

        jQuery('select[name="vm_host_name"]').focus();

        return false;

    } else if (jQuery('select[name="vm_dc"]').val() == '') {

        jQuery('select[name="vm_dc"]').css('border', '1px solid #ff0000');

        jQuery('select[name="vm_dc"]').focus();

        return false;

    }

    smoke.confirm(alerttext, function(e) {

        if (e) {

            jQuery('select[name="vm_host_name"]').css('border', '1px solid #ccc');

            jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>');

            jQuery('#custon_tab_container').addClass('container-blur');

            jQuery.ajax({

                url: '',

                type: "POST",

                data: jQuery('#migrateVmWare').serialize(),

                async: true,

                success: function(response) {

                    jQuery('#custon_tab_container').removeClass('container-blur');

                    jQuery('#migrateresponseMessage').html(response);

                    jQuery(obj).html(btntext);

                    jQuery(obj).css('pointer-events', 'none');

                }

            });

        } else {



        }

    }, {

        ok: 'Yes',

        cancel: 'Not',

        classname: "custom-class",

        reverseButtons: true

    });

}





// get vm dc host

function getDcHost(obj) {

    if (jQuery('#vm_dc').val() != '') {

        jQuery("#vm_host_name").html("<option value>loading..</option>");

        jQuery.ajax({

            type: "post",

            url: "",

            data: "ajaxaction=getdchost&dc=" + jQuery('#vm_dc').val(),

            success: function(response) {

                jQuery("#vm_host_name").find("option").remove();

                jQuery("#vm_host_name").append("<option value>Select</option>" + response);

            }

        });

    }

}



function vmware_viewgraph(obj, sid, tabnum, type) {

    jQuery('.tab_content1').css('display', 'none');

    jQuery('#vmTabContent1' + tabnum).css('display', 'block');

    jQuery('#inner_tab1 li').removeClass("active");

    jQuery('#vmTabContent1' + tabnum).html('<center>Loading...</center>');

    if (type == 'disk')

        var customData = 'ajaxaction=disks&type=' + tabnum;

    else

        var customData = 'ajaxaction=viewgraph&type=' + tabnum + '&serviceid=' + jQuery('#inner_tab1 li').attr('sid');

    jQuery.ajax({

        url: '',

        type: "POST",

        data: customData,

        success: function(response) {

            jQuery('#vmTabContent1' + tabnum).html(response);

        }

    });

    jQuery(obj).addClass("active");

}


function resetpw_button_action(obj, btntext, pwreq, cfpwreq, pwdontmatch, pwregexerrortext) {
    jQuery(".pwerror").remove();
    var pw = jQuery.trim(jQuery("#vmpw").val());
    var cfpw = jQuery.trim(jQuery("#cfvmpw").val());

    if ("" == pw) {
        jQuery("#vmpw").focus().after('<span class="pwerror">' + pwreq + '</span>');
        return false;
    } else if (!pw.match(/^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/)) {
        jQuery("#vmpw").focus().after(
            '<span class="pwerror">' + pwregexerrortext + ' Test@123</span>'
        );
        return false;
    }
    if ("" == cfpw) {
        jQuery("#cfvmpw").focus().after('<span class="pwerror">' + cfpwreq + '</span>');
        return false;
    }
    if ("" != pw && "" != cfpw && pw != cfpw) {
        jQuery("#cfvmpw").focus().after('<span class="pwerror">' + pwdontmatch + '</span>');
        return false;
    }
    ///jQuery('#custon_tab_container').addClass('container-blur');

    jQuery("#vmchangepwresponse").html("");
    jQuery(obj).html(btntext + '&nbsp;<i class="fa fa-spinner fa-pulse"></i>').css('pointer-events', 'none');
    jQuery.ajax({
        type: "post",
        url: "",
        data: jQuery('#resetvmpwform').serialize(),
        success: function(response) {
            jQuery(obj).html(btntext).css('pointer-events', 'auto');
            var result = jQuery.parseJSON(response);
            jQuery("#vmchangepwresponse").html(result.msg);
        }

    });
}