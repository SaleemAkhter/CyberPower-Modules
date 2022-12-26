
// Get Snapshot detail 

function getsnapshotList(){
    jQuery.ajax({
        type:'post',
        url:'',
        data:{'customaction':'getSnapShotDetail'},
        success:function(response){
            jQuery("#snapshotdetail").html(response);
        }
    });
}

// get config list 

function getConfigList(){
	jQuery.ajax({
		type:'post',
		url:'',
		data:{'customaction': 'getConfigListDetail'},
		success:function(response){
			$("#rescueDetail").html(response);
		}
		
	});
}

// Rescue 

function rescue(obj){
	$(obj).html('Rescue&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
	var disksda = $("#disksda").val();
	var disksdb = $("#disksdb").val();
	jQuery.ajax({
		type:'post',
		url:'',
		data:{'customaction':'rescueDisk','disksda':disksda,'disksdb':disksdb},
		success:function(response){
           var result = jQuery.parseJSON(response);
           if (result.status == 'success') {
				smoke.signal("Successfully Reboot OS But now under processing Please wait.....", function(e){
					setInterval(function () {
						checkstatus();
					}, 10000);	
				}, {
					duration: 3000,
					classname: "custom-class"
				});			   
           } else {
			smoke.alert(result.msg, {ok:"close"});
          }			
		}
	});
}

//show linode graph
function cpu_detail(obj){
	$(".graphs-tabs li a").removeClass("active");
	$(obj).addClass("active");
	$("#container1").empty();
	jQuery(".graph_spin").show();
	var graph_range =  $("#graph_select").val();
	jQuery.ajax({
			  type: 'post',
			  url: '',
			  data: {'customaction':'cpu_graph','graph_range':graph_range},
		 success: function (response) {	
			jQuery(".graph_spin").hide();
			jQuery('#container1').html(response);
		}
	});
    
}
function ipv4_detail(obj){	
	$(".graphs-tabs li a").removeClass("active");
	$(obj).addClass("active");
	$("#container1").empty();
	$(".graph_spin").show();
	var graph_range =  $("#graph_select").val();
		jQuery.ajax({
			  type: 'post',
			  url: '',
			  data: {'customaction':'ipv4_graph','graph_range':graph_range},
		 success: function (response) {	
			$(".graph_spin").hide();
			jQuery('#container1').html(response);
		}
	});
    
}
function ipv6_detail(obj){
	$(".graphs-tabs li a").removeClass("active");
	$(obj).addClass("active");
	$("#container1").empty();
	$(".graph_spin").show();
	var graph_range =  $("#graph_select").val();
		jQuery.ajax({
			  type: 'post',
			  url: '',
			  data: {'customaction':'ipv6_graph','graph_range':graph_range},
		 success: function (response) {	
			$(".graph_spin").hide();
			jQuery('#container1').html(response);
		}
	});
    
}

function addSnapshot(obj,linodeid){
    $(obj).html('Take Snapshot&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
	var label = $("#snapshotlabel").val();
    jQuery.ajax({
        type:'post',
		url:'',
		data:{'customaction':'takeSnapshot','snapshotlabel':label},
	success: function(response){
		var result = jQuery.parseJSON(response);
		 if (result.status == 'success') {
			 smoke.signal("Successfully Create snapshot But now under processing Please wait .....", function(e){
				setInterval(function () {
				   checkstatus();
            }, 10000);	
			}, {
				duration: 3000,
				classname: "custom-class"
			});
			window.location.reload();
		}else{
			//alert(result.msg);
			smoke.alert(result.msg, {ok:"close"});
			window.location.reload();
			//$(obj).html('Take Snapshot');
		}
	}
    });    
}

function checksnapshotstatus(snapshotID){
	jQuery.ajax({
        type:'post',
		url:'',
		data:{'customaction':'checksnapshotstatus','snapshotID':snapshotID},
	success: function(response){
		var result = jQuery.parseJSON(response);
        if (result.status == 'success') {
			window.location.reload();
		}else{
			smoke.alert(result.msg, {ok:"close"});
		}
	}
    });
}
function diskio_detail(obj){
	$(".graphs-tabs li a").removeClass("active");
	$(obj).addClass("active");
	$("#container1").empty();
	$(".graph_spin").show();
	var graph_range =  $("#graph_select").val();
		jQuery.ajax({
			  type: 'post',
			  url: '',
			  data: {'customaction':'diskio_graph','graph_range':graph_range},
		 success: function (response) {
			$(".graph_spin").hide();
			jQuery('#container1').html(response);
		}
	});
    
}

function graph_select(event){
	 var graph_range = event.target.value;
	 var active_graphtype  = $(".graphs-tabs li a.active").text();
		if(active_graphtype == 'IPv4 Traffic'){
			var customaction = 'ipv4_graph';
		}else if(active_graphtype == 'IPv6 Traffic'){
			var customaction = 'ipv6_graph';
		}else if(active_graphtype == 'Disk IO'){
			var customaction = 'diskio_graph';
		}else{
			var customaction = 'cpu_graph'; 
		}
	$("#container1").empty();
	$(".graph_spin").show();
	jQuery.ajax({
			  type: 'post',
			  url: '',
			  data: {'customaction':customaction,'graph_range':graph_range},
		 success: function (response) {
			$(".graph_spin").hide();
			jQuery('#container1').html(response);
		}
	});
}
// update linode label ....

function changehtml(obj,label){
	var html = '<input type="text" name="linodelabel" value="'+ label +'" class="form-control">&nbsp;<i class="fa-li fa fa-spinner fa-spin" style="display:none;"></i><a href="javascript:void(0);" onclick="updatelabel(\''+ label +'\');"><i class="fa fa-check" aria-hidden="true"></i></a><a href="javascript:void(0);" onclick="cancellabel(\''+ label +'\');"><i class="fa fa-times" aria-hidden="true"></i></a>';
	jQuery("#serverlabel").html(html);
}

function updatelabel(obj){
	$(".fa-spin").show();
	var linodelabel = jQuery("input[name=linodelabel]").val();
	if(linodelabel == ''){
		//alert('Length must be 3-32 characters');
		smoke.alert('Length must be 3-32 characters', {ok:"close"});
	}else{
		jQuery.ajax({
			type:'post',
			url:'',
			data:{'customaction' : 'updateBackup','linodeLabel':linodelabel},
			success:function(response){	 
				var result = jQuery.parseJSON(response);
                if (result.status == 'success') {	
					var html = '<h3>'+ linodelabel +'</h3><a href="javascript:void(0);" onclick="changehtml(this,\''+ linodelabel +'\');" style="color:#000;"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
					jQuery("#serverlabel").html(html);
				} else {
					smoke.alert(result.msg, {ok:"close"});
					var html = '<h3>'+ obj +'</h3><a href="javascript:void(0);" onclick="changehtml(this,\''+ obj +'\');" style="color:#000;"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
					jQuery("#serverlabel").html(html);
				}
				}			
		});
	}
	
}


function cancellabel(obj){
	var html = '<h3>'+ obj +'</h3><a href="javascript:void(0);" onclick="changehtml(this,\''+ obj +'\');" style="color:#000;"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
	jQuery("#serverlabel").html(html);
}

// cancel backup
function cancelBackup(obj){
	  jQuery(obj).html('Cancel Backup&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
	  jQuery.ajax({
		  type: 'post',
		  url:'',
		  data:{'customaction' : 'cancelBackup'},
		  success:function(response){
			  var result = jQuery.parseJSON(response);
                if (result.status == 'success') {
					smoke.signal("Successfully Cancel Backup But now Under processing Please wait....", function(e){
						checkstatus();
					}, {
						duration: 3000,
						classname: "custom-class"
					});
				} else {
					smoke.alert(result.msg, {ok:"close"});
            }
		  }
	  });
}
	
// copy to clipboardData

function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

// enable backup 

function enableBackup(obj,linodeid,serviceid,userid,paymentmethod){
	 jQuery(obj).html('Enable Backup&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
	 	  jQuery.ajax({
		  type: 'post',
		  url:'',
		  data:{'customaction' : 'enableBackup','linodeid':linodeid,'serviceid':serviceid,'userid':userid,'paymentmethod':paymentmethod},
		  success:function(response){
			  var result = jQuery.parseJSON(response);
                if (result.result == 'success') {
                    document.location.href = 'viewinvoice.php?id='+result.invoiceid;
				} else {
					smoke.alert(result.message, {ok:"close"});
            }
		  }
	  });
}

// Reset root password


function resetrootpassword(obj){
	jQuery(obj).html('Reset Root Password&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
	var disklabel = $("#disknameid").val();
	var rootpassword = $("#pass_log_id").val();
	jQuery.ajax({
		type:'post',
		url:'',
		data:{'customaction' : 'resetrootpassword','disklabel':disklabel,'rootpassword':rootpassword},
		  success:function(response){	
			var result = jQuery.parseJSON(response);
                if (result.status == 'success') {
					smoke.signal("Successfully Reset Root Password But now under processing Please wait .....", function(e){
						setInterval(function () {
							checkstatus();
					}, 10000);	
					}, {
						duration: 3000,
						classname: "custom-class"
					});
				} else {
					smoke.alert(result.msg, {ok:"close"});
            }
			//jQuery(obj).html('<i class="fa fa-key" aria-hidden="true" style="margin-right: 8px;"></i>Reset Root Password');
		  }
	});
}

// Reverse DNS linode

function linodereversedns(obj, linodeid, serviceid, language) {
    if (typeof language === "undefined" || language === null)
        language = '';

    var chk = jQuery('#reversednsSec').css('display');
    jQuery('#rdnstable').css('display', 'block');
    jQuery('#reversStatus').html('');
    if (chk == 'block') {
        return false;
    }
    jQuery('#dataSec, #detailSec, #powerSec, #usageSec, #logSec, #updateSec, #reversednsSec').css('display', 'none');
    jQuery('.manage_tab_menu li a').removeClass('selected');
    jQuery(obj).addClass('selected');
    getipList(linodeid, serviceid, language);
    jQuery('#reversednsSec').css('display', 'block');
}


// Reboot Linode

function rebootLinode(obj, linodeId, configId, serviceid, language, alerttext) {
    if (typeof language === "undefined" || language === null)
        language = '';

    smoke.confirm(alerttext, function (e) {
        if (e) {
//    jQuery('#loaderabckground, #serverloader').css('display', 'block');
            jQuery(obj).html('Reboot&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
           // jQuery('#custon_tab_container').addClass('container-blur');
            jQuery.ajax({
                type: 'post',
                url: '',
                data: {'customaction': 'reboot', 'linodeid': linodeId, 'configid': configId, 'serviceid': serviceid, 'language': language},
                success: function (response) {
                    var result = jQuery.parseJSON(response);
            if (result.status == 'success') {
					smoke.signal("Successfully Reboot Server But now under processing Please wait .....", function(e){
                        //window.location.reload();	
							setInterval(function () {
								checkstatus();
							}, 10000);
					}, {
						duration: 3000,
						classname: "custom-class"
					});
				} else {
					smoke.alert(result.msg, {ok:"close"});
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

// power on Linode

function powerOnLinode(obj, btntext) {
    jQuery(obj).html(btntext + '&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
  //  jQuery('#custon_tab_container').addClass('container-blur');
    jQuery.ajax({
        type: 'post',
        url: '',
        data: {'customaction': 'poweron'},
        success: function (response) {
            var result = jQuery.parseJSON(response);
            if (result.status == 'success') {
					smoke.signal("Successfully Power ON But now under processing Please wait .....", function(e){
                        //window.location.reload();	
							setInterval(function () {
								checkstatus();
							}, 10000);
					}, {
						duration: 3000,
						classname: "custom-class"
					});
				} else {
					smoke.alert(result.msg, {ok:"close"});
            }
        }
    });
}

// Shut down Linode

function shutdownLinode(obj, alerttext, btntext) {

    smoke.confirm(alerttext, function (e) {
        if (e) {
            jQuery(obj).html(btntext + '&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
            //jQuery('#custon_tab_container').addClass('container-blur');
            jQuery.ajax({
                type: 'post',
                url: '',
                data: {'customaction': 'shutdown'},
                success: function (response) {
                    var result = jQuery.parseJSON(response);
                if (result.status == 'success') {
					smoke.signal("Successfully Shut Down But now under processing Please wait .....", function(e){
                       		setInterval(function () {
								checkstatus();
							}, 10000);
					}, {
						duration: 3000,
						classname: "custom-class"
					});
				} else {
					smoke.alert(result.msg, {ok:"close"});
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

// Boot linode 

function bootLinode(obj, linodeId, configId, serviceid, language) {
    if (typeof language === "undefined" || language === null)
        language = '';

    jQuery(obj).html('Boot&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
   // jQuery('#custon_tab_container').addClass('container-blur');
    jQuery.ajax({
        type: 'post',
        url: '',
        data: {'customaction': 'boot', 'linodeid': linodeId, 'configid': configId, 'serviceid': serviceid, 'language': language},
        success: function (response) {
            jQuery('#loaderabckground, #serverloader').css('display', 'none');
            jQuery(obj).html('Boot');
            jQuery('#custon_tab_container').removeClass('container-blur');
            jQuery('#powerstatus').removeClass('powerstatus');
            jQuery('#powerstatus').html('');
            var result = jQuery.parseJSON(response);
            if (result.status == 'success') {
                jQuery('#powerstatus').addClass('powerstatus');
                jQuery('#powerstatus').html(result.msg);
                setTimeout(function () {
                    jQuery('#powerstatus').removeClass('powerstatus');
                    jQuery('#powerstatus').html('');
                    jQuery("#linode_detail").trigger('click');
                    //window.location.href = 'clientarea.php?action=productdetails&id=' + serviceid;
                }, 3000);
            } else {
				smoke.alert(result.msg, {ok:"close"});
            }
        }
    });
}

// Check linode status

function checkLinodeStatus() {
    jQuery('#serverDetailSec').html('<div style="width: 100%;text-align: center;font-size: 25px;"><i class="fa-li fa fa-spinner fa-spin"></i></div>');
    setInterval(function () {
        jQuery.ajax({
            type: 'post',
            url: '',
            data: {'customaction': 'status'},
            success: function (response) {
                jQuery('#serverDetailSec').html(response);
            }
        });
    }, 50000);
}

// Update linode

function updateLinode(obj) {
    jQuery(obj).html('Update&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
  //  jQuery('#custon_tab_container').addClass('container-blur');
    jQuery.ajax({
        type: 'post',
        url: '',
        data: jQuery('#updatelinode').serialize(),
        success: function (response) {
            jQuery('#loaderabckground, #serverloader').css('display', 'none');
            jQuery(obj).html('Update');
            jQuery('#custon_tab_container').removeClass('container-blur');
            jQuery('#updatestatus').removeClass('powerstatus');
            jQuery('#updatestatus').html('');
            var result = jQuery.parseJSON(response);
            if (result.status == 'success') {
                jQuery('#updatestatus').addClass('powerstatus');
                jQuery('#updatestatus').html(result.msg);
                setTimeout(function () {
                    jQuery('#updatestatus').removeClass('powerstatus');
                    jQuery('#updatestatus').html('');
                    jQuery("#linode_detail").trigger('click');
                }, 3000);
            } else {
				smoke.alert(result.msg, {ok:"close"});
            }
        }
    });
}

// Update Disk

function updateDisk(obj, serviceid, id) {
    jQuery(obj).html('Update&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
   // jQuery('#custon_tab_container').addClass('container-blur');
    jQuery.ajax({
        type: 'post',
        url: '',
        data: jQuery('#updatedisk_' + id).serialize(),
        success: function (response) {
            jQuery('#loaderabckground, #serverloader').css('display', 'none');
            jQuery(obj).html('Update');
            jQuery('#custon_tab_container').removeClass('container-blur');
            jQuery('#updatestatus').removeClass('powerstatus');
            jQuery('#updatestatus').html('');
            var result = jQuery.parseJSON(response);
            if (result.status == 'success') {
                jQuery('#updatestatus').addClass('powerstatus');
                jQuery('#updatestatus').html(result.msg);
                setTimeout(function () {
                    jQuery('#updatestatus').removeClass('powerstatus');
                    jQuery('#updatestatus').html('');
                    window.location.href = 'clientarea.php?action=productdetails&id=' + serviceid;
                }, 3000);
            } else {
                jQuery('#updatestatus').addClass('powerstatus');
                jQuery('#updatestatus').html(result.msg);
                setTimeout(function () {
                    jQuery('#updatestatus').removeClass('powerstatus');
                    jQuery('#updatestatus').html('');
                    //window.location.href = 'clientarea.php?action=productdetails&id='+serviceid;
                }, 3000);
            }
        }
    });
}

// Resize disk

function resizeDisk(obj, serviceid, linodeid, diskid, id, totalhd, configid, language) {
    if (typeof language === "undefined" || language === null)
        language = '';

    var size = jQuery('#size_' + id).val();

    jQuery(obj).html('Resize&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
    jQuery('#custon_tab_container').addClass('container-blur');

    jQuery.ajax({
        type: 'post',
        url: '',
        data: {'customaction': 'resizedisk', 'linodeid': linodeid, 'diskid': diskid, 'size': size, 'totalhd': totalhd, 'configid': configid, 'serviceid': serviceid, 'language': language},
        success: function (response) {
            jQuery('#loaderabckground, #serverloader').css('display', 'none');
            jQuery(obj).html('Resize');
            jQuery('#custon_tab_container').removeClass('container-blur');
            jQuery('#updatestatus').removeClass('powerstatus');
            jQuery('#updatestatus').html('');
            var result = jQuery.parseJSON(response);
            if (result.status == 'success') {
                jQuery('#updatestatus').addClass('powerstatus');
                jQuery('#updatestatus').html(result.msg);
                setTimeout(function () {
                    jQuery('#updatestatus').removeClass('powerstatus');
                    jQuery('#updatestatus').html('');
                    window.location.href = 'clientarea.php?action=productdetails&id=' + serviceid;
                }, 3000);
            } else {
                jQuery('#updatestatus').addClass('powerstatus');
                jQuery('#updatestatus').html(result.msg);
                setTimeout(function () {
                    jQuery('#updatestatus').removeClass('powerstatus');
                    jQuery('#updatestatus').html('');
                    //window.location.href = 'clientarea.php?action=productdetails&id='+serviceid;
                }, 3000);
            }
        }
    });
}

// Get template

function rebuildOs(obj, serviceid, alerttext) {
    smoke.confirm(alerttext, function (e) {
        if (e) {
            jQuery(obj).html('Rebuild OS&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
            jQuery('#custon_tab_container').addClass('container-blur');
            jQuery(obj).attr('disabled', true);

            jQuery.ajax({
                type: 'post',
                url: '',
                data: 'temlabel=' + jQuery('#ostemplate').find("option:selected").text() + '&serviceid=' + serviceid + '&' + jQuery('#rebuildosform').serialize(),
                success: function (response) {
                    var result = jQuery.parseJSON(response);
                    if (result.status == 'success') {
						smoke.signal("Successfully Reboot OS But now under processing Please wait.....", function(e){
							setInterval(function () {
								checkstatus();
							}, 10000);	
						}, {
							duration: 3000,
							classname: "custom-class"
						});

                    } else {
						smoke.alert(result.msg, {ok:"close"});
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
function restoreBackup(obj,backupID){
	 smoke.confirm("Are you sure want to Restore Backup ??", function (e) {
		 if(e){
			 jQuery(obj).html('<i class="fa-li fa fa-spinner fa-spin"></i>');
			  jQuery.ajax({
					type:'post',
					url:'',
					data : {'customaction' : 'restoreBackup','backupID':backupID},
					success:function(response){
						var result = jQuery.parseJSON(response);
						if (result.status == 'success') {
							smoke.signal("Successfully Restore Backup But now under processing Please wait.....", function(e){
								setInterval(function () {
									checkstatus();
								}, 10000);	
							}, {
								duration: 3000,
								classname: "custom-class"
							});
						} else {
							smoke.alert(result.msg, {ok:"close"});
						}
					}
			  });
		 }
	 },{
		ok: 'Yes',
        cancel: 'Not',
        classname: "custom-class",
        reverseButtons: true
	 });
}

function getServerIcon(obj){
	jQuery.ajax({
		type:'post',
		url:'',
		data:{'customaction': 'getservericon','templatenmae':obj},
		success:function(response){
			$("#server_icon").html(response);
		}
	});
}

function checkstatus(){
	 jQuery.ajax({
        type: "post",
        url: "",
        data: "customaction=checkstatus",
        success: function (response) {
			var result = jQuery.parseJSON(response);
            if(result.status == 'success'){
				window.location.reload();
			}else{
				smoke.alert(result.msg, {ok:"close"});
			}
        }
    });
}


//Get job list

function getJobList() {
    jQuery("#clientlogbody").html('<tr><td colspan="100%"><div><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div></td></tr>');
    jQuery.ajax({
        type: "post",
        url: "",
        data: "customaction=joblist",
        success: function (response) {
            jQuery("#clientlogbody").html(response);
        }
    });
}


//Get ip list
function getipList(linodeid, serviceid, language,rdnsDiv) {
    if (typeof language === "undefined" || language === null)
        language = '';

    jQuery("#dnsiplist").html('<tr role="row" class="odd"><td colspan="6" style="text-align:center;">Loading....</td></tr>');
    jQuery.ajax({
        type: "post",
        url: "",
        data: "customaction=iplist&linodeid=" + linodeid + "&serviceid=" + serviceid + "&language=" + language + "&rdnsDiv=" + rdnsDiv,
        success: function (response) {
            jQuery("#dnsiplist").html(response);
            jQuery('#loaderabckground, #serverloader').css('display', 'none');
            jQuery('#reversStatus').css('display', 'block');
        }
    });
}

//Add ip private and public
function addserverips(obj, iptype, linodeid, serviceid, language) {
    if (typeof language === "undefined" || language === null)
        language = '';

    if (iptype == 'private')
        var btnText = 'Add Private IP';
    else
        var btnText = 'Add Public IP';

    jQuery(obj).html(btnText + '&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
    jQuery('#custon_tab_container').addClass('container-blur');
    jQuery('#reversStatus').html('');
    jQuery('#reversStatus').css('display', 'none');
    jQuery.ajax({
        type: "post",
        url: "",
        data: "customaction=addip&linodeid=" + linodeid + "&serviceid=" + serviceid + "&iptype=" + iptype + '&language=' + language,
        success: function (response) {
            jQuery(obj).html(btnText);
            jQuery('#custon_tab_container').removeClass('container-blur');
            var result = jQuery.parseJSON(response);
            jQuery("#reversStatus").html(result.msg);
            if (result.status == 'success') {
                getipList(linodeid, serviceid, language);
            } else{ 
				//jQuery('#reversStatus').css('display', 'block');
				smoke.alert(result.msg, {ok:"close"});
            }
        }
    });
}

function reverseHostname(obj, ipid, linodeid, serviceid,ipaddress,language) {
	
    if (typeof language === "undefined" || language === null)
        language = '';

    var inptval = jQuery('#dnsiplist').find('.reverseinput2').attr('id');
    if (!inptval || inptval === "undefined")
    {
        jQuery('#ipid' + ipid).attr('class', 'reverseinput2');
        jQuery('#save' + ipid).html('Save');
        jQuery('#ipid' + ipid).removeAttr('readonly');
    } else if (inptval == 'ipid' + ipid)
    {
        var hostname = $('#ipid' + ipid).val();
        jQuery(obj).html('Save&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
        jQuery('#custon_tab_container').addClass('container-blur');
        jQuery.ajax({
            type: "post",
            url: "",
            data: "customaction=reversehost&ipaddressid=" + ipaddress + "&serviceid=" + serviceid + "&hostname=" + hostname + '&language=' + language,
            success: function (response) {
                var result = jQuery.parseJSON(response);
				if (result.status == 'success') {
					smoke.signal("Successfully RDNS update But now under processing Please wait.....", function(e){
						window.location.reload();
					}, {
						duration: 3000,
						classname: "custom-class"
					});
				} else {
					smoke.alert(result.msg, {ok:"close"});
				}
            }
        });
    } else
    {
        return false;
    }
}

// Get admin side server deatil 

function getServerDetail() {
    jQuery('#getServerDetail').addClass('fa-spin');
    jQuery('#getServerDetail').css('pointer-events', 'none');
    jQuery.ajax({
        url: '',
        type: "POST",
        data: 'getserverDetail=detail',
        success: function (response) {
            jQuery('#ajaxresponse').html(response);
            jQuery('#ajaxresponse1').html(response);
            jQuery('.serverlist').html(jQuery('#ajaxresponse .serverlist').html());
            jQuery('.serverlist1').html(jQuery('#ajaxresponse1 .serverlist1').html());
            jQuery('#ajaxresponse').html('');
            jQuery('#ajaxresponse1').html('');
        }
    });
}

// GEt Linode Usage

function getLinodeUsage() {

    jQuery('#diskusageSec').html('<div style="width: 100%;text-align: center;font-size: 25px;"><i class="fa-li fa fa-spinner fa-spin"></i></div>');
    jQuery.ajax({
        url: '',
        type: "POST",
        data: 'customaction=getusage',
        success: function (response) {
            jQuery('#diskusageSec').html(response);
        }
    });
}