
//Reboot Server
function reboot(obj) {
    
    smoke.confirm('Do you want to Reboot Server', function (e) {
        if (e) {

            jQuery(obj).html('Reboot&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
          
            jQuery.ajax({
                type: 'post',
                url: '',
                data: {ajaxcall : true,activity:'reboot'},
				dataType: 'html',
                success: function (response) {
                    var result = jQuery.parseJSON(response);					
						if (result.status == 'success') {
							
							jQuery('.reboot_success').fadeIn().addClass("alert alert-success").html("<strong>" + result.msg + "</strong>");							
								setTimeout(function () {
									jQuery('.reboot_success').fadeOut("slow");
								}, 5000);	
								jQuery(obj).html('<i class="fa fa-power-off" aria-hidden="true"></i>&nbsp; Reboot');
								
						} else {
							jQuery('.reboot_error').fadeIn().addClass("alert alert-danger").html(result.msg);
						}
                }
            });
        } else {

        }
    }, {
        ok: 'Yes',
        cancel: 'No',
        classname: "custom-class",
        reverseButtons: true
    });
}

// Shutdown Server

function stop(obj) {

    smoke.confirm('Do you want to Stop Server', function (e) {
        if (e) {
            jQuery(obj).html('ShutDown&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');         
            jQuery.ajax({
                type: 'post',
                url: '',
                data: {ajaxcall : true,activity:'stop'},
				dataType: 'html',
                success: function (response) {
                    var result = jQuery.parseJSON(response);
                if (result.status == 'success') {
											
								setTimeout(function () {
									jQuery('.reboot_success').fadeIn().addClass("alert alert-success").html("<strong>" + result.msg + ".it will take a few time to get into effect!</strong>");	
									jQuery(obj).hide();
									jQuery(obj).next().show();
									jQuery(obj).next().html('<i class="fa fa-play-circle" aria-hidden="true"></i>Power ON');
									setTimeout(function () {
										jQuery('.reboot_success').fadeOut("slow");
										}, 5000);
								}, 5000);	
							

							//	jQuery(obj).html('<i class="fa fa-play-circle" aria-hidden="true"></i>&nbsp;Power ON');					
				} else {
					jQuery('.reboot_error').fadeIn().addClass("alert alert-danger").html(result.msg);
				}
                    
                }
            });
        } else {

        }
    }, {
        ok: 'Yes',
        cancel: 'No',
        classname: "custom-class",
        reverseButtons: true
    });
}

// power on Server

function start(obj) {
	smoke.confirm('Do you want to start Server', function (e) {
	    if (e) {
	              
	    jQuery(obj).html('PowerOn&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');
	    jQuery.ajax({
	        type: 'post',
	        url: '',
	        data: {ajaxcall : true, activity:'Start'},
			dataType: 'html',
	        success: function (response) {
	            var result = jQuery.parseJSON(response);
	            if (result.status == 'success') {
									setTimeout(function () {
										jQuery('.reboot_success').fadeIn().addClass("alert alert-success").html("<strong>" + result.msg + ".it will take a few time to get into effect!</strong>");							
										jQuery(obj).hide();
										jQuery(obj).prev().show();
										jQuery(obj).prev().html('<i class="fa fa-pause-circle" aria-hidden="true"></i>Power OFF');
										setTimeout(function () {
											jQuery('.reboot_success').fadeOut("slow");
											location.reload();
										}, 7000);
									}, 5000);	
									
									//jQuery(obj).html('<i class="fa fa-play-circle" aria-hidden="true">&nbsp;Power OFF');
					} else {
						jQuery('.reboot_error').fadeIn().addClass("alert alert-danger").html(result.msg);
	            }
	        }
	    });

		}
  	}, {
	    ok: 'Yes',
	    cancel: 'No',
	    classname: "custom-class",
	    reverseButtons: true
	});
}



function graph_time(that){
	var graphtime = $(that).val();
	var graphtype = jQuery('#graph_type option:selected').text();
	graph_select(graphtime,graphtype);
}
function graph_type(){
	var graphtype = jQuery('#graph_type option:selected').text();
	var graphtime = jQuery('#graph_select option:selected').val();
	graph_select(graphtime,graphtype);
}
function graph_select(graphtime,graphtype){
 	var graph_range = graphtime;
 	var active_graphtype  = graphtype;

	if(active_graphtype == 'CPU Overview'){
		var activity = 'cpuoverview';
	}else if(active_graphtype == 'CPU burst capacity (minutes)'){
		var activity = 'cpuurstcapacity';
		var metricType = 'cpuBurstCapacityMin';
	}else if(active_graphtype == 'CPU burst capacity (percentages)'){
		var activity = 'cpuurstcapacity';
		var metricType = 'cpuBurstCapacityPer';
	}else if(active_graphtype == 'Incoming network traffic'){
		var activity = 'NetworkIn';
	}else if(active_graphtype == 'Outgoing network traffic'){
		var activity = 'NetworkOut';
	}else if(active_graphtype == 'Status check failures'){
		var activity = 'statusCheck';
		var metricType = 'StatusCheckFailed';
	}else if(active_graphtype == 'Instance status check failures'){
		var activity = 'statusCheck';
		var metricType = 'StatusCheckFailed_Instance';
	}else if(active_graphtype == 'System status check failures'){
		var activity = 'statusCheck';
		var metricType = 'StatusCheckFailed_System';
	}else{
		var activity = 'cpuoverview'; 
	}

	jQuery("#container1").empty();
	jQuery(".graph_spin").show();
	jQuery.ajax({
			  type: 'post',
			  url: '',
			  data: {ajaxcall : true, activity:activity,time_period:graph_range, metricType: metricType},
			  dataType: 'html',
		 success: function (response) {
			jQuery(".graph_spin").hide();
			jQuery('#container1').html(response);
		}
	});
}

function detachdisk(that,diskname){
	 jQuery('#detachModal').modal('show');
     jQuery("#stop-instance").click(function(){
     	stop_instance();
     });

     jQuery('#detach-disk').click(function(){
     	jQuery(".disk_spin-modal").show();
 	 	jQuery.ajax({
				type: 'post',
				url: '',
				data: {ajaxcall : true,activity:'deletedisk',diskname:diskname},
		 success: function (response) {	
		 	var response = JSON.parse(response);
		 	jQuery(".disk_spin-modal").hide();
		 	if(response =='Success'){
		 		var response = 'Successfully detachdisk';
		 		jQuery('#delresponse').html('<div class="alert alert-success">'+response+'</div>');
		 		  setTimeout(function () {
                     location.reload(); 
                 },1000);
		 		 
		 	}else{
		 		var response = response.msg;
		 		jQuery('#delresponse').html('<div class="alert alert-danger">'+response+'</div>'); 
		 	}
			
		}
		});
 	});

/*	   if (confirm('Are you want to  ?')) {
        $(this).prev('span.text').remove();
    }
	//jQuery(".disk_spin").show();
	  jQuery('#delresponse').html('');
	jQuery.ajax({
			  type: 'post',
			  url: '',
			  data: {ajaxcall : true,activity:'deletedisk',diskname:diskname},
		 success: function (response) {	
		 	var response = JSON.parse(response);
		 	alert(response);
		 	if(empty(response)){
		 		response = 'Successfully detachdisk';
		 	}
			 jQuery(".disk_spin").hide();
			jQuery('#delresponse').html(response); 
		}
	}); */
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

function addSnapshot(obj){
    
	var label = jQuery("#snapshotlabel").val();
	if(label !== null && label !== ''){
		jQuery("#snapshotlabel").prop('disabled', true);
		//jQuery(obj).html('Take Snapshot&nbsp;<i class="fa-li fa fa-spinner fa-spin"></i>');		
		jQuery("#take-snp-ldr").show();		
		jQuery.ajax({
			type:'post',
			url:'',
			data:{ajaxcall : true, activity :'snapshot_name', snapshot_name: label},
		success: function(response){
			var result = jQuery.parseJSON(response);
				jQuery("#take-snp-ldr").hide();		
			 if (result.status == 'success') {
			 	getsnapshotList();
				 jQuery('.snapshot_success').fadeIn().addClass("alert alert-success").html("<strong>" + result.msg + "</strong>");
                    setTimeout(function () {
                    jQuery('.snapshot_success').fadeOut("slow");
                  }, 3000);
				 
				//jQuery(obj).html('Take Snapshot');
				jQuery('#snapshotlabel').val('');
				jQuery("#snapshotlabel").prop('disabled', false);
				 
			}else{		
				//jQuery(obj).html('Take Snapshot');			
				jQuery('.snapshot_error').fadeIn().addClass("alert alert-danger").html("<strong>" + result.msg + "</strong>");
				
			}
		}
		}); 
	}	
}

function getsnapshotList(){
    jQuery.ajax({
        type:'post',
        url:'',
        data:{ajaxcall : true, activity :'get_snapshot'},
        success:function(response){
            jQuery("#snapshotdetail").html(response);
        }
    });
}
function deleteSnapshot(obj){		
	smoke.confirm('Do you want to delete instance snapshot', function (e) {

		if (e) {
			
			jQuery("#snapshotdetail").html('<tr><td colspan="100%"><div><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div></td></tr>');		
			jQuery.ajax({
				type:'post',
				url:'',
				data:{ajaxcall : true, activity:'deletesnapshot',snapshotName : obj},
				success: function(response){
					var result = jQuery.parseJSON(response);
						if (result.status == 'success') {
							getsnapshotList();
							jQuery('.snapshot_success').fadeIn().addClass("alert alert-success").html("<strong>" + result.msg + "</strong>");
								setTimeout(function () {
									jQuery('.snapshot_success').fadeOut("slow");
								}, 3000);						
						}else{			
							jQuery('.snapshot_error').fadeIn().addClass("alert alert-danger").html("<strong>" + result.msg + "</strong>");
								setTimeout(function () {
									jQuery('.snapshot_error').fadeOut("slow");
								}, 4000);
							}
				}
			}); 
			
		}
	},{

        ok: 'Yes',

        cancel: 'No',

        classname: "custom-class",

        reverseButtons: true

    });
}
function creatInstanceFrmSshot(obj){
	smoke.confirm('Do you want to restore instance snapshot? It will delete your existing disk data!', function (e) {

	if (e) {
		$('#snapshotloader').html('<i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i><div class="alert alert-warning" style="width:auto" >Snapshot restoring, This may take a while!</div>');
	$("#snapshotdetail").html('');	
		jQuery.ajax({
			type:'post',
			url:'',
			data:{ajaxcall : true, activity : 'createInstanceFrmSshot', snapshotName : obj},
		success: function(response){
			var result = jQuery.parseJSON(response);
			 if (result.status == 'success') {
				 getsnapshotList();
							jQuery('.snapshot_success').fadeIn().addClass("alert alert-success").html("<strong>" + result.msg + "</strong>");
								setTimeout(function () {
									jQuery('.snapshot_success').fadeOut("slow");
									 location.reload();
								}, 3000);
			}else{			
				jQuery('.snapshot_error').fadeIn().addClass("alert alert-danger").html("<strong>" + result.msg + "</strong>");
					jQuery("#snapshotdetail").html(''); 
			}
		}
		}); 
	
		}
  	}, {
	    ok: 'Yes',
	    cancel: 'No',
	    classname: "custom-class",
	    reverseButtons: true
	});
}
function getHostory(){
    jQuery.ajax({
        type:'post',
        url:'',
        data:{ajaxcall : true, activity :'history'},
		dataType: 'html',
        success:function(response){
            jQuery("#showHistory").html(response);
        }
    });
}

function updatefirewallrule(toport,fromport,updtprotocol){	
	jQuery('#updtruledata').html('Loading...<i class="fa-li fa fa-spinner fa-spin"></i>');
	jQuery("#updtruledata").prop('disabled', true);
	var formdata = jQuery("#updtrule-firewal").serialize();
		jQuery.ajax({
		url: "",
		type: "POST",
		data: "ajaxcall=true&activity=firewalruleupdate&"+formdata+"&deltoport="+toport+"&delfromport="+fromport+"&delprotocol="+updtprotocol,
		success: function(response)
		{
			var result = JSON.parse(response);
			if(result.status == 'success'){
				jQuery('#updaterulemodal').modal('hide');
				jQuery("#updtruledata").prop('disabled', false);
				jQuery('#updtruledata').html("Update");
				jQuery('.rule_success').fadeIn().addClass("alert alert-success").html("<strong>" + result.msg + "</strong>");
				firewallist();	
                    setTimeout(function () {
						jQuery('.rule_success').fadeOut("slow");
					}, 3000);
			}else{
				jQuery('#updaterulemodal').modal('hide');
				jQuery('.rule_error').fadeIn().addClass("alert alert-danger").html(result.msg);
			} 
		}
    });
}
 
function addfirewallrule(){
  jQuery("#addrulemodal").modal('show');
  jQuery('#rule-firewal').trigger("reset");
  jQuery('#ip-restrict').html('');
  jQuery("#add_rule").prop('disabled', false);
  jQuery('#add_rule').html("Create");
}

function insertfirewallrule(){	
	jQuery('#add_rule').html('Loading....<i class="fa-li fa fa-spinner fa-spin"></i>');
	jQuery("#add_rule").prop('disabled', true);
	var formdata = jQuery("#rule-firewal").serialize();	
	$.ajax({
	url: "",
	type: "POST",
	data: "ajaxcall=true&activity=firewalrule&"+formdata,
	success: function(response)
	{   
		var result = JSON.parse(response);		
		if(result.status == 'success'){			
			jQuery('#addrulemodal').modal('hide');
			jQuery("#add_rule").prop('disabled', false);
			jQuery('#add_rule').html("Create");
			
			jQuery('.rule_success').fadeIn().addClass("alert alert-success").html("<strong>" + result.msg + "</strong>");
			firewallist();	
                setTimeout(function () {
					jQuery('.rule_success').fadeOut("slow");
				}, 3000);		
		}else{
			jQuery('#addrulemodal').modal('hide');
			jQuery('.rule_error').fadeIn().addClass("alert alert-danger").html(result.msg);			
		}
	}
});
}


/* Delete Rule*/
jQuery(document).on('click','#del-rule',function(e) {
	 
	var protocol = jQuery(this).attr('protocol');
	var toport = jQuery(this).attr('toport');
	var fromport = jQuery(this).attr('fromport');
	//if (confirm('Are you want to delete this rule ?')) {
	smoke.confirm('Do you want to delete this rule ?', function (e) {

	if (e) {
		jQuery(this).prop('disabled', true);
	
		jQuery("#firewalldetail").html('<tr><td colspan="100%"><div><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div></td></tr>');
		
		jQuery.ajax({
			url: "",
			type: "POST",
			data: "ajaxcall=true&activity=delfirewalrule&protocol="+protocol+"&toport="+toport+"&fromport="+fromport,
			success: function (response) {
				var result = jQuery.parseJSON(response);										
				if(result.status == 'success'){
					firewallist();	
						
					jQuery('.rule_success').fadeIn().addClass("alert alert-success").html("<strong>" + result.msg + "</strong>");
					setTimeout(function () {
							jQuery('.rule_success').fadeOut("slow");
						}, 5000);
													
				}else{
					jQuery(this).addClass('fa fa-trash');
					jQuery('.rule_error').fadeIn().addClass("alert alert-danger").html(result.msg);
					setTimeout(function () {
							jQuery('.rule_success').fadeOut("slow");
						}, 5000);	
				}
			}
		}); 
	//}
		}
  	}, {
	    ok: 'Yes',
	    cancel: 'No',
	    classname: "custom-class",
	    reverseButtons: true
	});
		 
});
function close_modal(){
	jQuery('.modal-body').find("select").val('');
	
}
function firewallist(){
    jQuery.ajax({
        type:'post',
        url:'',
        data:{ajaxcall : true, activity :'getfirewall'},
        success:function(response){
            jQuery("#firewalldetail").html(response);
        }
    });
}



function conndetail(obj){
	jQuery("#connectiondetailmodal").modal('show');
}

function getaccessdetail(){
	/*alert('aaa');
		jQuery.ajax({
			url: "",
			type: "POST",
			data: "ajaxcall=true&activity=getaccessdetailserver",
			success: function (response) {	
			alert(response);

			}
		}); */
}
function copytextFunction(that,obj) {
  jQuery('.copytxtbtn').text('copy');
  /* Get the text field */
  var copyText = document.getElementById(obj);

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

  /* Copy the text inside the text field */
  document.execCommand("copy");
  jQuery(that).text('copied');


}

jQuery(document).ready(function() {  

	 firewallist();

    jQuery('#delresponse').html('');
    jQuery('.snapshot_error').html('');

    jQuery('#restrictToIpAddressCheckbox').change(function() {
        if(this.checked) {
           jQuery('#ip-restrict').append('<div id="resticip-div"><p>Specify an IP address allowed to connect to your instance. You can specify a range of IP addresses using a dash, or using CIDR notation</p><label>Source IP address (192.0.2.0) or range (192.0.2.0-192.0.2.255 or 192.0.2.0/24)</label><div class="row"> <div class="form-group col-8 col-md-6 "><input name="ip[]" id="add-ip" class="rst-ip form-control" value="" ></div><div class="col-4 col-md-6 remove-ipbtn"> </div></div></div><div class="add-ipdiv col-md-12"><a id="addanotherip">+ Add another</a></div>');
        }else{
        	jQuery('#resticip-div').remove();
        	jQuery('.add-ipdiv').remove();
        }
               
    });

    jQuery('#restrictToIpAddressCheckboxupdate').change(function() {
        if(this.checked) {
           jQuery('#ip-restrict-update').append('<div id="resticip-update-div"><p>Specify an IP address allowed to connect to your instance. You can specify a range of IP addresses using a dash, or using CIDR notation</p><label>Source IP address (192.0.2.0) or range (192.0.2.0-192.0.2.255 or 192.0.2.0/24)</label><div class="row"> <div class="form-group col-8 col-md-6 "><input name="ip[]" id="add-ip" class="rst-ip form-control" value="" ></div><div class="col-4 col-md-6 remove-ipbtn-updt"> </div></div></div><div class="add-ipdiv-updt col-md-12"><a id="addanotherip-updt">+ Add another</a></div>');
        }else{
        	jQuery('#resticip-update-div').remove();
        	jQuery('.add-ipdiv-updt').remove();
        }
               
    });

   jQuery(document).on('click','#addanotherip',function(e) {
  		jQuery('#resticip-div').append('<div class="row"><div class="form-group col-8 col-md-6"><input name="ip[]" id="add-ip" class="rst-ip form-control" value="" ></div><div class="col-4 col-md-6 remove-ipbtn"><i class="fa fa-times" aria-hidden="true" style="color: #DD6B10;"></i></div></div>');	 
	}); 
   jQuery(document).on('click','#addanotherip-updt',function(e) {
  		jQuery('#resticip-update-div').append('<div class="row"><div class="form-group col-8 col-md-6"><input name="ip[]" id="add-ip" class="rst-ip form-control" value="" ></div><div class="col-4 col-md-6  remove-ipbtn-updt"><i class="fa fa-times" aria-hidden="true" style="color: #DD6B10;"></i></div></div>');	 
	});
 	jQuery(document).on('click','.remove-ipbtn-updt',function(e) {
   		jQuery(this).parent().remove();
	});
	jQuery(document).on('click','.remove-ipbtn',function(e) {
   		jQuery(this).parent().remove();
	});

	jQuery(document).on('click','#edit-rule',function(e) {
		jQuery('#updt-port-firewal').parent().show();
	 	jQuery("#updaterulemodal").modal('show');
	 	jQuery("#updtrule-firewal").trigger("reset");
	 	jQuery("#resticip-update-div").remove();
	 	jQuery(".add-ipdiv-updt").remove();
	 	jQuery(".addanotherip-updt").remove();

	 	jQuery(".extrafield").remove();
	 	var toport 	 = '';
	 	var fromport 	 = '';
	 	var protocol = '';
	 	var protocol = jQuery(this).attr('protocol');
   		var toport 	 = jQuery(this).attr('toport');
   		var fromport 	 = jQuery(this).attr('fromport');
   		var appname  = jQuery(this).attr('appname');
   		var resticips  = jQuery(this).attr('resticips');
   		
         //console.log(resticipsArr);
        jQuery('#restrictToIpAddressCheckboxupdate').prop( "checked", false);
        if(resticips != ''){
        	var resticipsArr = resticips.split(',');
        	var existips = "";
        	$.each(resticipsArr, function(key, value) {
	         	//alert(value);
	         	//console.log(value);
	         		existips += '<div class="row"> <div class="form-group col-md-6 "><input name="ip[]" id="add-ip" class="rst-ip form-control" value="'+value.replace("/32", "")+'"></div><div class="col-md-6  remove-ipbtn-updt"><i class="fa fa-times" aria-hidden="true" style="color: #DD6B10;"></i></div></div>';
	         });
        	
         	jQuery('#restrictToIpAddressCheckboxupdate').prop( "checked", true);
         	jQuery('#ip-restrict-update').append('<div id="resticip-update-div"><p>Specify an IP address allowed to connect to your instance. You can specify a range of IP addresses using a dash, or using CIDR notation</p><label>Source IP address (192.0.2.0) or range (192.0.2.0-192.0.2.255 or 192.0.2.0/24)</label>'+existips+'</div><div><a id="addanotherip-updt" class="addanotherip-updt">+ Add another</a></div>');
         }
   		jQuery("#updt-application-firewal").val(appname);
   		jQuery("#protocol-firewal").parent().removeClass('col-md-3');
        jQuery("#protocol-firewal").parent().addClass('col-md-4');
   		jQuery("#updt-protocol-firewal").addClass('firewall-pointer-event');
		jQuery("#updt-port-firewal").addClass('firewall-pointer-event'); 
   		if(appname == 'Ping (ICMP)'){
   			jQuery('#updt-port-firewal').parent().hide();
   			jQuery("#updt-protocol-firewal").val('ICMP');
   		}else if(appname == 'Custom ICMP'){
   			jQuery('#updt-port-firewal').parent().hide();
   			jQuery("#updt-protocol-firewal").parent().removeClass('col-md-4');
   			jQuery("#updt-protocol-firewal").parent().addClass('col-md-3');
   			jQuery("#updt-port-firewal").parent().parent().append('<div class="form-group col-md-2 extrafield"><label for="type">Type</label><input name="type" id="type-firewal"  value="" class="form-control"></div><div class="form-group col-md-2 extrafield"><label for="code">Code</label><input name="code" id="code-firewal"  value="" class="form-control"></div>');
   			jQuery("#updt-protocol-firewal").val('ICMP');
   			jQuery("#type-firewal").val(fromport);
   			jQuery("#code-firewal").val(toport);
   			
        
   		}else if(appname == 'All ICMP'){
   			jQuery("#updt-protocol-firewal").val('ICMP');
   			jQuery('#updt-port-firewal').parent().hide();
   		}else{
   			jQuery("#updt-protocol-firewal").val(protocol.toUpperCase());
   		}
   
   		jQuery('#updt-port-firewal').val(fromport);
   		if(fromport == '' && toport== ''){
   			var port = 'null';
   		}
   		jQuery('#updtruledata').attr("onclick","updatefirewallrule("+toport+","+fromport+",'"+protocol+"')");

	});
 

	jQuery(document).on('change','#updt-application-firewal',function(){
        var  rule = $('option:selected', this).text();         
        jQuery("#updt-port-firewal").parent().show();
        jQuery("#type-firewal").parent().remove();
        jQuery("#code-firewal").parent().remove();
        jQuery(".extrafield").remove();
         
        jQuery("#updt-protocol-firewal").addClass('firewall-pointer-event');
		jQuery("#updt-port-firewal").addClass('firewall-pointer-event'); 

        jQuery("#updt-protocol-firewal").addClass('col-md-2');
			if(rule == 'Custom'){
				jQuery("#updt-protocol-firewal").removeClass('firewall-pointer-event');
				jQuery("#updt-port-firewal").removeClass('firewall-pointer-event');
				jQuery("#updt-protocol-firewal").html('<option value="TCP">TCP</option><option value="UDP">UDP</option>');
				jQuery("#updt-port-firewal").val('');
			}
			if(rule == 'All TCP'){
				jQuery("#updt-protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#updt-port-firewal").val('0 - 65535');
			}
			if(rule == 'All UDP'){

				jQuery("#updt-protocol-firewal").html('<option value="UDP">UDP</option>');
				jQuery("#updt-port-firewal").val('0 - 65535');
			}
			if(rule == 'All protocols'){
				jQuery("#updt-protocol-firewal").html('<option value="ALL">ALL</option>');
				jQuery("#updt-port-firewal").val('0 - 65535');
			}
			if(rule == 'SSH'){
				jQuery("#updt-protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#updt-port-firewal").val('22');
			}
			if(rule == 'RDP'){
				jQuery("#updt-protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#updt-port-firewal").val('3389');
			}
			if(rule == 'HTTP'){
				jQuery("#updt-protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#updt-port-firewal").val('80');
			}
			if(rule == 'HTTPS'){
				jQuery("#updt-protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#updt-port-firewal").val('443');
			}
			if(rule == 'MySQL/Aurora'){
				jQuery("#updt-protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#updt-port-firewal").val('3306');
			}
			if(rule == 'Oracle-RDS'){
				jQuery("#updt-protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#updt-port-firewal").val('1521');
			}
			if(rule == 'PostgreSQL'){
				jQuery("#updt-protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#updt-port-firewal").val('5432');
			}
			if(rule == 'DNS (TCP)'){
				jQuery("#updt-protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#updt-port-firewal").val('53');
			}
			if(rule == 'DNS (UDP)'){
				jQuery("#updt-protocol-firewal").html('<option value="UDP">UDP</option>');
				jQuery("#updt-port-firewal").val('53');
			}
			if(rule == 'Ping (ICMP)'){
				jQuery("#updt-protocol-firewal").html('<option value="ICMP">ICMP</option>');
				jQuery("#updt-port-firewal").parent().hide();
			}
			if(rule == 'Custom ICMP'){
				jQuery("#updt-protocol-firewal").html('<option value="ICMP">ICMP</option>');
				jQuery("#updt-port-firewal").parent().hide();
				jQuery("#updt-port-firewal").parent().parent().append('<div class="form-group col-md-2 extrafield"><label for="type">Type</label><input name="type" id="type-firewal"  value="" class="form-control"></div><div class="form-group col-md-2 extrafield"><label for="code">Code</label><input name="code" id="code-firewal"  value="" class="form-control"></div>')

				//jQuery("#port-firewal").after().append('<input type="text" id="fname" name="fname">');
			}	
			if(rule == 'All ICMP'){
				 
				jQuery("#updt-protocol-firewal").html('<option value="ICMP">ICMP</option>');
				jQuery("#updt-port-firewal").parent().hide();
			}
      });

    jQuery(document).on('change','#application-firewal',function(){
        var  rule = jQuery('option:selected', this).text();
        jQuery("#port-firewal").parent().show();
        jQuery("#type-firewal").parent().remove();
        jQuery("#code-firewal").parent().remove();
        jQuery("#protocol-firewal").parent().removeClass('col-md-3');
        jQuery("#protocol-firewal").parent().addClass('col-md-4');

			if(rule == 'Custom'){
				jQuery("#protocol-firewal").removeClass('firewall-pointer-event');
				jQuery("#port-firewal").removeClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="TCP">TCP</option><option value="UDP">UDP</option>');
				jQuery("#port-firewal").val('');
			}
			if(rule == 'All TCP'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#port-firewal").val('0 - 65535');
			}
			if(rule == 'All UDP'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="UDP">UDP</option>');
				jQuery("#port-firewal").val('0 - 65535');
			}
			if(rule == 'All protocols'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="ALL">ALL</option>');
				jQuery("#port-firewal").val('0 - 65535');
			}
			if(rule == 'SSH'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#port-firewal").val('22');
			}
			if(rule == 'RDP'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#port-firewal").val('3389');
			}
			if(rule == 'HTTP'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#port-firewal").val('80');
			}
			if(rule == 'HTTPS'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#port-firewal").val('443');
			}
			if(rule == 'MySQL/Aurora'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#port-firewal").val('3306');
			}
			if(rule == 'Oracle-RDS'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#port-firewal").val('1521');
			}
			if(rule == 'PostgreSQL'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#port-firewal").val('5432');
			}
			if(rule == 'DNS (TCP)'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="TCP">TCP</option>');
				jQuery("#port-firewal").val('53');
			}
			if(rule == 'DNS (UDP)'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="UDP">UDP</option>');
				jQuery("#port-firewal").val('53');
			}
			if(rule == 'Ping (ICMP)'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="ICMP">ICMP</option>');
				jQuery("#port-firewal").val('8');
				jQuery("#port-firewal").parent().hide();
			}
			if(rule == 'Custom ICMP'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="ICMP">ICMP</option>');
				//jQuery("#port-firewal")
				jQuery("#port-firewal").parent().hide();
				jQuery("#protocol-firewal").parent().removeClass('col-md-4');
				jQuery("#protocol-firewal").parent().addClass('col-md-3');
				jQuery("#protocol-firewal").parent().parent().append('<div class="form-group col-md-2"><label for="type">Type</label><input name="type" id="type-firewal"  value="" class="form-control"></div><div class="form-group col-md-2"><label for="code">Code</label><input name="code" id="code-firewal"  value="" class="form-control"></div>')

				//jQuery("#port-firewal").after().append('<input type="text" id="fname" name="fname">');
			}	
			if(rule == 'All ICMP'){
				jQuery("#protocol-firewal").addClass('firewall-pointer-event');
				jQuery("#port-firewal").addClass('firewall-pointer-event');
				jQuery("#protocol-firewal").html('<option value="ICMP">ICMP</option>');
				jQuery("#port-firewal").val('-1');
				jQuery("#port-firewal").parent().hide();
			}
      });

});



