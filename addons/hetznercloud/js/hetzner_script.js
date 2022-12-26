jQuery(document).ready(function() {

    jQuery("#ajaxresult").empty();


    jQuery('#os_images').change(function() {
        if (jQuery(this).val() !== '0') {
            jQuery("input[value=REBUILD]").attr('disabled', false);
        } else {
            jQuery("input[value=REBUILD]").attr('disabled', true);
        }
    });
    
    jQuery('#iso').change(function() {
       
        if (jQuery(this).val() !== '0') {
            jQuery(".mount-btn").attr('disabled', false);
        } else {
            jQuery(".mount-btn").attr('disabled', true);
        }
    });
    getOSimage_flavor('os_image_info');
    gettablecontent('gettable_snapshot');
    rebuildImages('getimages');
    loadIsos('getisos');
    getVolumeSize('getvol_size');
    //floatingIPcounter();
    getFloatingIP_tablecontent('floating_ips');
    graphSection_metrics('metrics_live', 'live');

    jQuery(".graph-select").change(function() {
        var optionSelected = jQuery("option:selected", this);
        var valueSelected = this.value;
        graphSection_metrics('metrics_live', valueSelected);

    });
    //console.log(langVar);
    getCurrency();

});
jQuery("#totalpriceFLP").text((jQuery("#noOFfloatIP").val()) * FLPprice);
jQuery('#noOFfloatIP').change(function() {
    noOffloatIP = jQuery(this).val();
    flptotalprice = noOffloatIP * FLPprice;
    jQuery("#totalpriceFLP").text(flptotalprice);
});

function unMountIso(obj) {
    var action = "unmountiso";
    jQuery("#unmount").css('pointer-events', 'none');
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: "customaction=" + action,
        beforeSend: function() {
            jQuery("#unmount").addClass('disabled');
            jQuery('#unmountModal').modal('show');
            jQuery("#isoloader i").addClass("fa-spinner fa-spin");
        },
        success: function(response) {
            jQuery("#isoloader i").removeClass("fa-spinner fa-spin");
            json_response = JSON.parse(response);
            if (json_response.status == 'error') {
                jQuery("#mountmsg").html('<span class="mounterr">' + json_response.message + '</span>');
            } else {
                jQuery("#unmount").addClass('disabled');
                jQuery("#mountmsg").html('<span class="mountsuccess">Successful  Unmounted</span>');
            }
        }
    });
}

function mountIso(obj) {
    var action = "mountiso";
    jQuery(obj).css('pointer-events', 'none');
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: "customaction=" + action + '&iso=' + jQuery("#iso").val(),
        beforeSend: function() {
            jQuery(obj).css('pointer-events', 'none');
            jQuery("#isoloader i").addClass("fa-spinner fa-spin");
        },
        success: function(response) {
            jQuery(obj).css('pointer-events', 'auto');
            jQuery("#isoloader i").removeClass("fa-spinner fa-spin");
            json_response = JSON.parse(response);
            if (json_response.status == 'error') {
                jQuery("#mountmsg").html('<span class="mounterr">' + json_response.message + '</span>');
            } else {
                jQuery("#unmount").removeClass('disabled');
                jQuery("#mountmsg").html('<span class="mountsuccess">Successfuly Mounted</span>');
            }
        }
    });
}

function apicall(action) {
    jQuery("#overlaydiv").show();
    jQuery(".action-btn").prop('disabled', true);
    var serverAction = action;
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: 'customaction=' + serverAction,
        beforeSend: function() {

            jQuery("#os-image").hide();
            switch (serverAction) {
                case 'shutdown':
                    jQuery("#server_status").text(langVar.statusShutdownRun);
                    jQuery("#shutdown_btn i").removeClass("fa-power-off");
                    jQuery("#shutdown_btn i").addClass("fa-spinner fa-spin");
                    break;
                case 'reboot':
                    jQuery("#server_status").text(langVar.statusRebootRun);
                    jQuery("#reboot_btn i").removeClass("fa-power-off");
                    jQuery("#reboot_btn i").addClass("fa-spinner fa-spin");
                    break;
                case 'reset':
                    jQuery("#server_status").text(langVar.statusResetRun);
                    jQuery("#reset_btn i").removeClass("fa-plug");
                    jQuery("#reset_btn i").addClass("fa-spinner fa-spin");
                    break;
                case 'reset_password':
                    jQuery("#server_status").text(langVar.statusResetPassRun);
                    jQuery("#pass_reset_btn i").removeClass("fa-key");
                    jQuery("#pass_reset_btn i").addClass("fa-spinner fa-spin");

                    break;
                case 'create_image':
                    jQuery("#server_status").text(langVar.statusSnapshotRun);
                    jQuery("#snapshot-btn").addClass("fa-spinner fa-spin");
                    break;
                default:
                    jQuery("#server_status").text(langVar.statusPowerOnRun);
                    jQuery("#poweron_btn i").removeClass("fa-play");
                    jQuery("#poweron_btn i").addClass("fa-spinner fa-spin");
            }
        },
        success: function(response) {
            jQuery("#overlaydiv").hide()

            jQuery("#os-image").show();

            json_response = JSON.parse(response);
            if (json_response.status != "error") {
                jQuery(".action-btn").prop('disabled', false);
                switch (serverAction) {
                    case 'shutdown':
                        jQuery("#reboot_btn,#shutdown_btn,#reset_btn,#pass_reset_btn").hide();
                        jQuery("#poweron_btn").show();
                        jQuery("#server_status").text(langVar.statusOff);
                        jQuery("#shutdown_btn i").addClass("fa-power-off");
                        jQuery("#shutdown_btn i").removeClass("fa-spinner fa-spin");
                        break;
                    case 'poweron':
                        jQuery("#poweron_btn").hide();
                        jQuery("#reboot_btn, #shutdown_btn, #reset_btn,#pass_reset_btn").show();
                        jQuery("#server_status").text(langVar.statusOn);
                        jQuery("#poweron_btn i").removeClass("fa-spinner fa-spin");
                        jQuery("#poweron_btn i").addClass("fa-play");
                        break;
                    case 'reboot':
                        jQuery("#reboot_btn i").removeClass("fa-spinner fa-spin");
                        jQuery("#reboot_btn i").addClass("fa-power-off");
                        jQuery("#server_status").text(langVar.statusOn);
                        break;
                    case 'reset':
                        jQuery("#reset_btn i").removeClass("fa-spinner fa-spin");
                        jQuery("#reset_btn i").addClass("fa-plug");
                        jQuery("#server_status").text(langVar.statusOn);
                        break;
                    case 'reset_password':
                        jQuery("#pass_reset_btn i").removeClass("fa-spinner fa-spin");
                        jQuery("#pass_reset_btn i").addClass("fa-key");
                        jQuery("#server_status").text(langVar.statusOn);
                        break;
                    case 'create_image':
                        jQuery("#snapshot-btn i").removeClass("fa-spinner fa-spin");
                        jQuery("#server_status").text(langVar.statusOn);
                        jQuery("#snapshotbtn").addClass("bk-sn-active");
                        jQuery("#backupbtn").removeClass("bk-sn-active");
                        jQuery("#backup_tab").hide();
                        gettablecontent('gettable_snapshot');
                        jQuery("#snapshot_tab").show();
                        break;
                    default:
                        jQuery("#poweron_btn").hide();
                        jQuery("#reboot_btn, #shutdown_btn, #reset_btn, #pass_reset_btn").show();
                        jQuery("#server_status").text(langVar.statusOn);
                }
                if (serverAction == 'enable_rescue') {
                    jQuery("#enable_rescue").hide();
                    jQuery("#disable_rescue").show();
                    jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b> ' + langVar.successAlertRescueEnabled + '</div>');

                } else if (serverAction == 'disable_rescue') {
                    jQuery("#enable_rescue").show();
                    jQuery("#disable_rescue").hide();
                    jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b> ' + langVar.successAlertRescueDisabled + '</div>');

                } else if (serverAction == 'create_image') {
                    var serverAction_text = langVar.successAlertServSnap;
                    jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b>' + serverAction_text + ' ' + langVar.successfully + '</div>');
                } else if (serverAction == 'reset_password') {
                    var serverAction_text = langVar.successAlertRootPassReset;
                    jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in" ><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b>' + serverAction_text + 'successfully</div>');
                    jQuery('#rootPasswordRest').modal('show');

                } else {
                    var serverAction_text = serverAction;
                    jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b><span style="text-transform:capitalize">' + serverAction_text + '</span> ' + langVar.successfully + '</div>');
                }

            } else {
                jQuery("#ajaxresult").html('<div class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.error + '</b><span style="text-transform:capitalize"' + json_response.msg + '</span></div>');
            }
        },
    });
}

var backup_table = jQuery('#backup_table').DataTable({
    rowReorder: {
        selector: 'td:nth-child(2)'
    },
    responsive: true,
    "bLengthChange": false,
    "bInfo": false,
    searching: false,
    info: false,
});

var snapshot_table = jQuery('#snapshot_table').DataTable({
    rowReorder: {
        selector: 'td:nth-child(2)'
    },
    responsive: true,
    "bLengthChange": false,
    "bInfo": false,
    searching: false,
    info: false,
});

var floatingIP_table = jQuery('#floating_ipTable').DataTable({
    rowReorder: {
        selector: 'td:nth-child(2)'
    },
    responsive: true,
    "bLengthChange": false,
    "bInfo": false,
    searching: false,
    info: false,
});

/*bandwidth circlegraph********************/
var progressBarOptions = {
    startAngle: 180,
    size: 150,
    value: bandwidthUsageTotal_in_TB / totalbandwidth,
    fill: {
        color: '#fe4e5b'
    }
}
jQuery('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(event, progress, stepValue) {
    jQuery(this).find('strong').text(((String(stepValue.toFixed(2)).substr(1)) * 100) + "%");

});
/*bandwidth circlegraph********************/

jQuery("#backupbtn").click(function() {

    jQuery("#backupbtn").addClass("bk-sn-active");
    jQuery("#snapshotbtn").removeClass("bk-sn-active");
    jQuery("#snapshot_tab").hide();
    gettablecontent('gettable_backup');
    jQuery("#backup_tab").show();

});
jQuery("#snapshotbtn").click(function() {
    jQuery("#snapshotbtn").addClass("bk-sn-active");
    jQuery("#backupbtn").removeClass("bk-sn-active");
    jQuery("#backup_tab").hide();
    gettablecontent('gettable_snapshot');
    jQuery("#snapshot_tab").show();
});

function gettablecontent(action) {
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: "customaction=" + action,
        beforeSend: function() {
            if (action == "gettable_backup") {
                jQuery("#backup_tableloader").addClass("fa-spinner fa-spin");
            } else {
                jQuery("#snapshot_tableloader").addClass("fa-spinner fa-spin");
            }

        },
        success: function(response) {
            //jQuery(".tableoverlay").hide();

            // alert(response);
            if (action == 'gettable_backup') {
                jQuery("#backup_table tbody").html(response);
                jQuery("#backup_tableloader").removeClass("fa-spinner fa-spin");

            } else {
                jQuery("#snapshot_table tbody").html(response);
                jQuery("#snapshot_tableloader").removeClass("fa-spinner fa-spin");

            }
        }
    });
}

function rebuildImages(action) {
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: "customaction=" + action,
        beforeSend: function() {
            jQuery("#rebuildloader i").addClass("fa-spinner fa-spin");
        },
        success: function(response) {
            jQuery("#rebuildloader i").removeClass("fa-spinner fa-spin");
            //jQuery("#poweron_btn").hide();
            json_response = JSON.parse(response);
            jQuery.each(json_response, function(index1, value1) {
                if (value1.type == 'system') {
                    jQuery('#os_images').append($('<option>', {
                        value: value1.name,
                        text: value1.description
                    }));
                }
            });
        }
    });
}

function loadIsos(action) {
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: "customaction=" + action,
        beforeSend: function() {
            jQuery("#isoloader i").addClass("fa-spinner fa-spin");
        },
        success: function(response) {
            jQuery("#isoloader i").removeClass("fa-spinner fa-spin");
            //jQuery("#poweron_btn").hide();
            //json_response = JSON.parse(response);
            jQuery('#iso').append(response);
            // jQuery.each(json_response, function(index1, value1) {
            //     jQuery('#iso').append($('<option>', {
            //         value: value1.name,
            //         text: value1.description
            //     }));
            // });
        }
    });
}

function getOSimage_flavor(action) {

    jQuery.ajax({
        url: "",
        type: 'POST',
        data: "customaction=" + action,
        beforeSend: function() {
            jQuery(".server-satus-cantos > img").hide();
            jQuery("#imageloader i").addClass("fa-spinner fa-spin");

        },
        success: function(response) {
            jQuery("#imageloader i").removeClass("fa-spinner fa-spin");
            jQuery(".server-satus-cantos > img").show();
            json_response = JSON.parse(response);
            //alert(response);
            if (json_response != null) {
                jQuery(".server-satus-cantos > img").attr("src", rootpath + '/modules/servers/hetznercloud/templates/images/' + json_response.os_flavor + '.svg');
                jQuery(".server-satus-cantos > img").attr("alt", rootpath + '/modules/servers/hetznercloud/templates/images/' + json_response.os_flavor + '.svg');
                jQuery(".server-satus-cantos > img").css({
                    "width": "115px",
                    "height": "115px"
                });
                jQuery(".server-satus-cantos > img + p").text(json_response.description);
            } else {
                jQuery(".server-satus-cantos > img").attr("alt", langVar.imgNotAvail);

            }
        }
    });
}

function getVolumeSize(action) {
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: "customaction=" + action,
        beforeSend: function() {
            jQuery("#vol_size").text('Loading..');
        },
        success: function(response) {
            if (response != 0) {
                jQuery("#vol_size").text(response);
            } else {
                prebtagtext = jQuery("#vol_size").prev().text();
                jQuery("#vol_size").parent().html("<span>" + langVar.disklocal + "<b>" + prebtagtext + "</b></span>");
            }
        }
    });
}

function graphSection_metrics(action, time) {
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: "customaction=" + action + "&time=" + time,
        beforeSend: function() {
            jQuery("#graphloader i").addClass("fa-spinner fa-spin");
            jQuery(".graph-select").attr('disabled', true);
        },
        success: function(response) {
            json_response = JSON.parse(response);
            jQuery("#graphloader i").removeClass("fa-spinner fa-spin");
            //console.log(json_response);
            //alert(json_response);
            jQuery('#CPU').html('<div id="container1" style="height: 370px; width: 100%;margin-top:40px;"></div>' + json_response.cpu);
            jQuery('#Throughput').html('<div id="container2" style="height: 370px; width: 100%;margin-top:40px;"></div>' + json_response.disk_throughput);
            jQuery('#IOPS').html('<div id="container3" style="height: 370px; width: 100%;margin-top:40px;"></div>' + json_response.disk_iops);
            jQuery('#PPS').html('<div id="container4" style="height: 370px; width: 100%;margin-top:40px;"></div>' + json_response.netPPS);
            jQuery('#Traffic').html('<div id="container5" style="height: 370px; width: 100%;margin-top:40px;"></div>' + json_response.netbandwidth);
            jQuery(".graph-select").attr('disabled', false);
            return false;
        }

    });
}

jQuery('#submit').click(function() {
    var rebuild = jQuery("#rebuild_form").serialize();
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: rebuild,
        beforeSend: function() {
            jQuery("#overlaydiv").show();
            jQuery("#rebuildloader i").addClass("fa-spinner fa-spin");

        },
        success: function(response) {
            json_response = JSON.parse(response);
            jQuery("#overlaydiv").hide()
            jQuery("#rebuildloader i").removeClass("fa-spinner fa-spin");
            jQuery("#poweron_btn").hide();
            jQuery("#reboot_btn, #shutdown_btn, #reset_btn, #pass_reset_btn").show();
            if (json_response.status != "error") {
                var serverAction_text = langVar.servRebuild;
                jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b>' + serverAction_text + ' ' + langVar.successDone + '</div>');
                setTimeout(function() {
                    window.location.reload();
                }, 3000);

            } else {
                jQuery("#ajaxresult").html('<div class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.error + '</b>' + json_response.msg + '</div>');
            }
        },
    });
    // getOSimage_flavor('os_image_info');
});

function tableaction(action, id, body) {
    var postData = 'customaction=' + action + '&os_image_selected=' + id;
    if (action == 'rebuild') {
        $('#backuptable_rebuildWarningModal').modal('show');

        jQuery("#backuptable_submit").click(function() {
            jQuery.ajax({
                url: "",
                type: 'POST',
                data: postData,
                beforeSend: function() {
                    jQuery("#overlaydiv").show();
                },
                success: function(response) {

                    jQuery("#overlaydiv").hide();
                    jQuery("#poweron_btn").hide();
                    jQuery("#reboot_btn, #shutdown_btn, #reset_btn, #pass_reset_btn").show();
                    json_response = JSON.parse(response);

                    if (json_response.status != "error") {
                        setTimeout(function() {
                            window.location.reload();
                        }, 10000);
                        var serverAction_text = langVar.servRebuild;
                        jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b>' + serverAction_text + ' ' + langVar.successDone + '</div>');


                    } else {
                        jQuery("#ajaxresult").html('<div class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.error + '</b>' + json_response.msg + '</div>');
                    }
                }
            });
            //getOSimage_flavor('os_image_info');
        });
    }
    if (action == 'convertToSnapshot') {
        $('#backuptable_backupToSnapshot').modal('show');
        jQuery("#backupToSnapshot_submit").click(function() {
            //alert(postData+body);
            jQuery.ajax({
                url: "",
                type: 'POST',
                data: postData + body,
                beforeSend: function() {
                    jQuery("#overlaydiv").show();
                },
                success: function(response) {
                    jQuery("#overlaydiv").hide();
                    json_response = JSON.parse(response);

                    if (json_response.status != "error") {
                        gettablecontent("gettable_backup");
                        gettablecontent("gettable_snapshot");
                        var serverAction_text = "BACKUP TO SNAPSHOT ";
                        jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b>' + serverAction_text + ' ' + langVar.successDone + '</div>');
                    } else {
                        jQuery("#ajaxresult").html('<div class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.error + '</b>' + json_response.msg + '</div>');
                    }
                }
            });
        });

    }
    if (action == 'deleteImage') {

        $('#delete_backupOrSnapshot').modal('show');
        jQuery("#delete_backupOrSnapshot_submit").click(function() {
            jQuery.ajax({
                url: "",
                type: 'POST',
                data: postData,
                beforeSend: function() {
                    jQuery("#overlaydiv").show();
                },
                success: function(response) {
                    jQuery("#overlaydiv").hide();
                    json_response = JSON.parse(response);
                    //alert(json_response); 

                    var serverAction_text = langVar.imgDelete;
                    if (response == null) {
                        jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b>' + serverAction_text + ' ' + langVar.successDone + '</div>');

                    }
                    if (json_response.error) {
                        jQuery("#ajaxresult").html('<div class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.error + '</b>' + json_response.error.message + '</div>');
                    }
                }
            });
            gettablecontent('gettable_backup');
            gettablecontent('gettable_snapshot');
        });

    }
    if (action == 'change_protection') {
        $('#protection_Snapshot').modal('show');
        if (body == '&delete=false') {
            $('#protection_Snapshot h4.modal-title.text-center').text(langVar.disableProtection);
            $('#protection_Snapshot_submit').text(langVar.disableProtection);
        } else {
            $('#protection_Snapshot h4.modal-title.text-center').text(langVar.enableProtection);
            $('#protection_Snapshot_submit').text(langVar.enableProtection);
        }
        jQuery("#protection_Snapshot_submit").click(function() {
            //alert(postData+body);
            jQuery.ajax({
                url: "",
                type: 'POST',
                data: postData + body,
                beforeSend: function() {
                    jQuery("#overlaydiv").show();
                },
                success: function(response) {
                    jQuery("#overlaydiv").hide();
                    //alert(response); 
                    json_response = JSON.parse(response);

                    if (json_response.status != "error") {
                        if (body == '&delete=true') {
                            var serverAction_text = langVar.imgProtecEnable;
                        } else {
                            var serverAction_text = langVar.imgProtecDisable;
                        }
                        jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b>' + serverAction_text + ' ' + langVar.successDone + '</div>');


                    } else {
                        jQuery("#ajaxresult").html('<div class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.error + '</b>' + json_response.msg + '</div>');
                    }
                }
            });
            //alert("test");
            gettablecontent('gettable_snapshot');
        });
    }
    if (action == 'change_description') {
        tdata = jQuery("#" + id).html();
        jQuery("#" + id).html('<form id="' + id + '_form" action="" method="GET"><div class="input-group" ><input type="text" class="form-control" value="' + tdata + '" name="imageName" ><div class="input-group-btn okbtn" ><input class="btn btn-default" type="submit" name="submit" style="line-height: 2.2;" value="OK"></div></div></form>');

        var formID = "#" + id + "_form";
        jQuery("#" + id + "_form input[type=text]").focus();

        jQuery(document).mouseup(function(e) {

            var container = jQuery("#" + id + "_form");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                jQuery(formID).remove();

                jQuery("#" + id).html(tdata);
            }
        });

        jQuery(formID).submit(function(e) {
            //alert(formID);
            var changeDesData = jQuery("#" + id + "_form").serialize();
            //alert(postData+"&"+changeDesData);
            e.preventDefault();

            jQuery.ajax({

                url: "",
                type: 'POST',
                data: postData + "&" + changeDesData,
                beforeSend: function() {
                    jQuery("#overlaydiv").show();
                },
                success: function(response) {
                    jQuery("#overlaydiv").hide();
                    //alert(response);
                    json_response = JSON.parse(response);
                    var newImageName = json_response.image.description;
                    jQuery(formID).remove();
                    jQuery("#" + id).html(newImageName);
                    tdata = newImageName;

                    //json_response  = JSON.parse(response);

                }
            });
        });
    }
}

function getFloatingIP_tablecontent(action) {
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: "customaction=" + action,
        beforeSend: function() {
            jQuery("#FloatingIP_tableloader").addClass("fa-spinner fa-spin");

        },
        success: function(response) {
            jQuery("#floating_ipTable tbody").html(response);
            jQuery("#FloatingIP_tableloader").removeClass("fa-spinner fa-spin");
        }
    });
}

function floatingIP_tableaction(action, id, body) {
    var postData = 'customaction=' + action + '&floatingIP_selected=' + id;

    if (action == 'floating_ip_instruction') {
        jQuery("#floating_ip_modal").modal('show');
        jQuery('span#ipAddress').text(id);
    }

    if (action == 'floating_ip_change_description') {
        iptdata = jQuery("#" + id).html();
        jQuery("#" + id).html('<form id="' + id + '_form" action="" method="GET"><div class="input-group" ><input type="text" class="form-control" value="' + iptdata + '" name="floating_ipName" ><div class="input-group-btn okbtn" ><input class="btn btn-default" type="submit" name="submit" style="line-height: 2.2;" value="OK"></div></div></form>');

        var formID = "#" + id + "_form";
        jQuery("#" + id + "_form input[type=text]").focus();

        jQuery(document).mouseup(function(e) {

            var container = jQuery("#" + id + "_form");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                jQuery(formID).remove();

                jQuery("#" + id).html(iptdata);
            }
        });

        jQuery(formID).submit(function(e) {
            //alert(formID);
            var changeDesData = jQuery("#" + id + "_form").serialize();
            //alert(postData+"&"+changeDesData);
            e.preventDefault();

            jQuery.ajax({

                url: "",
                type: 'POST',
                data: postData + "&" + changeDesData,
                beforeSend: function() {
                    jQuery("#overlaydiv").show();
                },
                success: function(response) {
                    jQuery("#overlaydiv").hide();
                    //alert(response);
                    json_response = JSON.parse(response);
                    var newIpDesc = json_response.floating_ip.description;
                    jQuery(formID).remove();
                    jQuery("#" + id).html(newIpDesc);
                    iptdata = newIpDesc;

                    //json_response  = JSON.parse(response);

                }
            });
        });
    }

    if (action == 'change_IPprotection') {
        $('#protection_floatingIP').modal('show');
        if (body == '&delete=false') {
            $('#protection_floatingIP h4.modal-title.text-center').text(langVar.disableProtection);
            $('#protection_floatingIP_submit').text(langVar.disableProtection);
        } else {
            $('#protection_floatingIP h4.modal-title.text-center').text(langVar.enableProtection);
            $('#protection_floatingIP_submit').text(langVar.enableProtection);
        }
        jQuery("#protection_floatingIP_submit").click(function() {
            //alert(postData+body);
            jQuery.ajax({
                url: "",
                type: 'POST',
                data: postData + body,
                beforeSend: function() {
                    jQuery("#overlaydiv").show();
                },
                success: function(response) {
                    jQuery("#overlaydiv").hide();
                    //alert(response); 
                    json_response = JSON.parse(response);

                    if (!json_response.action.error) {
                        if (body == '&delete=true') {
                            var serverAction_text = langVar.enableProtection;
                        } else {
                            var serverAction_text = langVar.disableProtection;
                        }
                        jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b> ' + serverAction_text + '' + langVar.successDone + '</div>');
                        getFloatingIP_tablecontent('floating_ips');

                    } else {
                        jQuery("#ajaxresult").html('<div class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.error + '</b>' + json_response.msg + '</div>');
                    }
                }
            });
            //alert("test");

        });
    }

    if (action == 'reverseDNSedit') {
        var str = body;
        var ipAddress = str.substr(4);
        jQuery('#edit_reverseDNS').modal('show');
        jQuery('input#ip_no').attr("value", ipAddress);
        jQuery('input#ip_no').prop("readonly", true);
        var revDNStext = jQuery("#dns_ptr" + id).text();
        jQuery('input[name=dns_ptr]').attr("value", revDNStext);
        jQuery("#edit_reverseDNS_submit").attr("disabled", true);
        jQuery("input[name=dns_ptr]").keyup(function() {
            ValidIpAddressRegex = /^(([1-9]?\d|1\d\d|2[0-5][0-5]|2[0-4]\d)\.){3}([1-9]?\d|1\d\d|2[0-5][0-5]|2[0-4]\d)$/;
            ValidHostnameRegex = /^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)+([A-Za-z0-9][A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/;
            ValidIPAddress_HostnameRegex = /^((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$\.)+(^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)+([A-Za-z0-9][A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$)/;
            var dns_check1 = new RegExp(ValidIpAddressRegex);
            var dns_check2 = new RegExp(ValidHostnameRegex);
            var dns_check3 = new RegExp(ValidIPAddress_HostnameRegex);
            var dns_ptrVal = jQuery('input[name=dns_ptr]').val();
            if (dns_check1.test(dns_ptrVal) == true || dns_check2.test(dns_ptrVal) == true || dns_check3.test(dns_ptrVal) == true) {
                //alert(dns_check1.test(dns_ptrVal));
                jQuery("#edit_reverseDNS_submit").attr("disabled", false);
            } else {
                // alert("Invalid host name");
                jQuery("#edit_reverseDNS_submit").attr("disabled", true);
            }

        });
        jQuery("#edit_reverseDNS_submit").click(function() {
            dns_ptrVal = jQuery('input[name=dns_ptr]').val();

            jQuery.ajax({
                url: "",
                type: 'POST',
                data: postData + body + "&dns_ptr=" + dns_ptrVal,
                beforeSend: function() {
                    jQuery("#overlaydiv").show();
                    jQuery("#FloatingIP_tableloader").addClass("fa-spinner fa-spin");

                },
                success: function(response) {
                    jQuery("#overlaydiv").hide();
                    jQuery("#FloatingIP_tableloader").removeClass("fa-spinner fa-spin");
                    json_response = JSON.parse(response);

                    if (json_response.action) {
                        serverAction_text = "EDIT REVERSE DNS";
                        jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b>' + serverAction_text + '' + langVar.successDone + '</div>');
                        getFloatingIP_tablecontent('floating_ips');
                        jQuery('input[name=dns_ptr]').val(revDNStext);

                    } else if (json_response.error) {
                        // jQuery('input[name=dns_ptr]').attr("value",revDNStext);            
                        jQuery("#ajaxresult").html('<div class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.error + '</b>' + json_response.error.details.fields[0].messages[0] + '</div>');
                        // var dns_ptrVal= jQuery('input[name=dns_ptr]').val();
                        jQuery('input[name=dns_ptr]').val(revDNStext);

                    }
                }
            });

        });
    }
    if (action == 'reverseDNSreset') {
        // alert(revDNStext);
        jQuery.ajax({
            url: "",
            type: 'POST',
            data: postData + body + "&dns_ptr=" + null,
            beforeSend: function() {
                jQuery("#overlaydiv").show();
                jQuery("#FloatingIP_tableloader").addClass("fa-spinner fa-spin");

            },
            success: function(response) {
                jQuery("#overlaydiv").hide();
                jQuery("#FloatingIP_tableloader").removeClass("fa-spinner fa-spin");
                json_response = JSON.parse(response);

                if (json_response.action) {
                    serverAction_text = langVar.resetRevDns;
                    jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b>' + serverAction_text + '' + langVar.successDone + '</div>');
                    getFloatingIP_tablecontent('floating_ips');

                } else if (json_response.error) {
                    jQuery("#ajaxresult").html('<div class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.error + '</b>' + json_response.error.details.fields[0].messages[0] + '</div>');
                }
            }
        });

    }
    if (action == "unassignFIP") {
        jQuery.ajax({
            url: "",
            type: 'POST',
            data: postData,
            beforeSend: function() {
                jQuery("#overlaydiv").show();
                jQuery("#FloatingIP_tableloader").addClass("fa-spinner fa-spin");

            },
            success: function(response) {
                jQuery("#overlaydiv").hide();
                jQuery("#FloatingIP_tableloader").removeClass("fa-spinner fa-spin");
                json_response = JSON.parse(response);

                if (json_response.status == 'success') {
                    jQuery('#flpAssignUnassignModal').modal('show');
                    jQuery('#flpAssignUnassign').text(langVar.flpunassign);
                    jQuery('#flpAssignUnassignResponse').text(langVar.flpunassign + '' + langVar.successDone);

                    serverAction_text = langVar.flpunassign;
                    jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b>' + serverAction_text + '' + langVar.successDone + '</div>');
                    getFloatingIP_tablecontent('floating_ips');

                } else if (json_response.error) {
                    jQuery('#flpAssignUnassignModal').modal('show');
                    jQuery('#flpAssignUnassign').text(langVar.flpunassign + ' ' + langVar.error);
                    jQuery('#flpAssignUnassignResponse').text(json_response.error.details.fields[0].messages[0]);

                    jQuery("#ajaxresult").html('<div class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.error + '</b>' + json_response.error.details.fields[0].messages[0] + '</div>');
                }
            }
        });
    }
    if (action == "assignFIP") {
        jQuery.ajax({
            url: "",
            type: 'POST',
            data: postData,
            beforeSend: function() {
                jQuery("#overlaydiv").show();
                jQuery("#FloatingIP_tableloader").addClass("fa-spinner fa-spin");

            },
            success: function(response) {
                jQuery("#overlaydiv").hide();
                jQuery("#FloatingIP_tableloader").removeClass("fa-spinner fa-spin");
                json_response = JSON.parse(response);

                if (json_response.action) {
                    jQuery('#flpAssignUnassignModal').modal('show');
                    jQuery('#flpAssignUnassign').text(langVar.flpassign);
                    jQuery('#flpAssignUnassignResponse').text(langVar.flpassign + '' + langVar.successDone);
                    serverAction_text = langVar.flpassign;
                    jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b>' + serverAction_text + '' + langVar.successDone + '</div>');
                    getFloatingIP_tablecontent('floating_ips');

                } else if (json_response.error) {
                    jQuery('#flpAssignUnassignModal').modal('show');
                    jQuery('#flpAssignUnassign').text(langVar.flpassign + ' ' + langVar.error);
                    jQuery('#flpAssignUnassignResponse').text(json_response.error.details.fields[0].messages[0]);
                    jQuery("#ajaxresult").html('<div class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.error + '</b>' + json_response.error.details.fields[0].messages[0] + '</div>');
                }
            }
        });
    }

}
jQuery('#addFloatingIP_submit').click(function() {
    var addfloatingIPdata = jQuery("#addFloatingIP_form").serialize();
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: addfloatingIPdata,
        beforeSend: function() {
            jQuery("#overlaydiv").show();
            jQuery("#FloatingIP_tableloader i").addClass("fa-spinner fa-spin");
        },
        success: function(response) {
            json_response = JSON.parse(response);
            jQuery("#overlaydiv").hide()
            jQuery("#FloatingIP_tableloader i").removeClass("fa-spinner fa-spin");
            if (json_response.status != "error") {
                var serverAction_text = langVar.addfloatingIPsuccess;
                jQuery("#ajaxresult").html('<div class="alert alert-success alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.success + '</b>' + serverAction_text + ' ' + langVar.successfully + '</div>');
                jQuery("#addFloating_IPSuccess").modal('show');

            } else if (json_response.status == "error") {
                jQuery('#addFloatingIPResponseModal').modal('show');
                jQuery('#flpAddResponse').text(json_response.msg);

                jQuery("#ajaxresult").html('<div class="alert alert-danger alert-dismissible fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><b>' + langVar.error + '</b>' + json_response.msg + '</div>');
            }
        },
    });
    // getOSimage_flavor('os_image_info');
});

function floatingIPcounter() {
    var addfloatingIPdata = "customaction=countFIPStatus";
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: addfloatingIPdata,
        beforeSend: function() {
            jQuery("#overlaydiv").show();
            jQuery("#FloatingIP_tableloader i").addClass("fa-spinner fa-spin");
        },
        success: function(response) {
            jQuery("#overlaydiv").hide()
            jQuery("#FloatingIP_tableloader i").removeClass("fa-spinner fa-spin");
            json_response = JSON.parse(response);
            jQuery("#flpstatus").html('<b>' + langVar.totalFloatingIP + '</b>' + json_response.floatingIPcount + '&nbsp <b>' + langVar.usedFloatingIP + '</b>' + json_response.usedFloatIP);
        }
    });
    return false;

}

function getCurrency() {
    jQuery.ajax({
        url: "",
        type: 'POST',
        data: 'customaction=currencyIDCode&currCode=' + currencyID,
        success: function(response) {
            json_response = JSON.parse(response);
            jQuery(".currCode").text(json_response.prefix);
            jQuery(".currCodeSuffix").text(json_response.suffix);
        }
    });
    return false;
}