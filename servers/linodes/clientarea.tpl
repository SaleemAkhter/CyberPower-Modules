
<script type="text/javascript" language="javascript" src="modules/servers/linodes/js/smoke/smoke.js"></script>
<script type="text/javascript" language="javascript" src="modules/servers/linodes/js/smoke/smoke.min.js"></script>
<link href="modules/servers/linodes/css/style.css" rel="stylesheet">
<link href="modules/servers/linodes/css/style-new.css" rel="stylesheet">
<link rel="stylesheet" href="modules/servers/linodes/css/smoke.css">
<script type="text/javascript" src="modules/servers/linodes/js/jquery.js"></script>
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="modules/servers/linodes/css/jQuery-plugin-progressbar.css">
{* <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> *}
<script src="modules/servers/linodes/js/jQuery-plugin-progressbar.js"></script>

{literal}<script type="text/javascript">checkLinodeStatus();</script>{/literal}
{if $error}
    <div class="alert alert-danger text-center" >
        {$error}
    </div>
{/if}
{literal}
    <script>
        jQuery(document).ready(function () {
			getServerIcon({/literal}'{$templatedetail.label}'{literal});
			$(".progress-bar").loading();
			getConfigList();
			cpu_detail("CPU Usage");
			getipList({/literal}{$linodeId}{literal},{/literal}{$serviceid}{literal},'','{/literal}{$rdnsDiv}{literal}');
			getsnapshotList();
			getJobList();
			
			
        });			

			$(document).on('click', '.toggle-password', function() {
				$(this).toggleClass("fa-eye fa-eye-slash");
				var input = $("#pass_log_id");
				input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
			});
    </script>
<style>
	.clientareaproductdetails .tab-content .product-details{
		display:none;
	}
</style>
{/literal}
<div id="loaderabckground" style="display: none;"></div>
<div id="serverloader" style="display: none;"><img src="modules/servers/linodes/images/loading.gif"></div>
<div id="wrapper">
    <section class="manage_content linode-contant"><!-- maincontent starts -->
        <div class="row"><!-- row starts -->

            <div class="clearfix"></div>
            <div class="col-lg-12 col-md-12 col-sm-12"><!-- col-12 starts -->
              <div id="custon_tab_container"></div>
			  <div class="server-satus-wrapper">
			   <div class="server-satus-running">
				   <div class="server-satus-title">
					<div id="serverlabel"><h3>{$linodelist.label|replace:'linode':'server'}</h3>
						<!--<a href="javascript:void(0);" onclick="changehtml(this,'{$linodelist.label|replace:"linode":"server"}');" style="color:#000;"><i class="fa fa-pencil" aria-hidden="true"></i></a>--></div>
					<h5 style="text-transform: capitalize;">{$linodelist.status}</h5>
				   </div>
				   <div class="server-satus-inner-box">
						<div class="server-satus-cantos" id="server_icon">
							<i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i>
							<!--<img src="modules/servers/linodes/images/server/server-icon.png" alt="server icon" title="$templatedetail.label"/>-->
						<!--<p>{$templatedetail.label}</p>-->
						</div>
						<div class="server-satus-space-right">
						  <ul class="server-top-ul">
							<!--<li><object class="ser-icon1"  data="modules/servers/linodes/images/server/icon-1.svg" type="image/svg+xml"></object><span>cx11:<b>123456</b></span></li>-->
							<li><object class="ser-icon1"  data="modules/servers/linodes/images/server/icon-1.svg" type="image/svg+xml"></object><span>Transfer:<b>{$linodelist.specs.transfer}</b></span></li>							
							<li><object  data="modules/servers/linodes/images/server/icon-2.svg" type="image/svg+xml"></object><span>vCPUl:<b>{$linodelist.specs.vcpus}</b></span></li>
							<li><object  data="modules/servers/linodes/images/server/icon-3.svg" type="image/svg+xml"></object><span>RAM:<b>{($linodelist.specs.memory)/1024}GB</b></span></li>
							<li><object  data="modules/servers/linodes/images/server/icon-4.svg" type="image/svg+xml"></object><span>Disk local:<b>{($linodelist.specs.disk)/1024}GB</b></span></li>
						 </ul>
						  <!--<ul class="server-bottom-ul">
							<li><i class="fa fa-cloud-upload" aria-hidden="true"></i><span>0.2</span><sub>mbit</sub></li>
							<li><i class="fa fa-cloud-download" aria-hidden="true"></i><span>0.4</span><sub>mbit</sub></li>
						  </ul>-->
						  
						</div>
						
						<div class="button-box">
						   <button href="#" class="btn-style btn-color-1" onclick="rebootLinode(this, '{$linodeId}', '{$configId}', '{$serviceid}', '{$clientsdetails.language}', '{$language.linoderebootconfirm}');"><i class="fa fa-power-off" aria-hidden="true"></i>Reboot</button>
						   {if $linodelist.status == 'running'}
								<button href="#" class="btn-style btn-color-2" onclick="shutdownLinode(this, '{$language.linodeshutdownconfirm}', '{$language.linodeShutdown}');"><i class="fa fa-pause-circle" aria-hidden="true"></i>Power OFF</button>
						   {else}
								<button href="#" class="btn-style btn-color-3" onclick="powerOnLinode(this, '{$language.linodepoweron}');"><i class="fa fa-play-circle" aria-hidden="true"></i>Power ON</button>
						   {/if}
						   <!--<button href="#" class="btn-style btn-color-3" onclick="bootLinode(this, '{$linodeId}', '{$configId}', '{$serviceid}', '{$clientsdetails.language}');"><i class="fa fa-plug" aria-hidden="true"></i>Boot</button>-->
						   
						 <!--  <button href="#" class="btn-style btn-color-4"><i class="fa fa-key" aria-hidden="true"></i>Reset Root Password</button>-->
						   {if $linodelist.backups.enabled == '1'}
								<button href="#" class="btn-style btn-color-2" onclick = "cancelBackup(this)"><i class="fas fa-hdd" aria-hidden="true"></i>Cancel Backup</button>
							{else}
								<button href="#" class="btn-style btn-color-3" onclick="enableBackup(this,'{$linodeId}','{$serviceid}','{$userid}','{$paymentmethod}')"style= "display:{$backupDiv};" ><i class="fas fa-hdd" aria-hidden="true"></i>Enable Backup</button>
							{/if}
						</div>
				   </div>
			   </div>
			   <div class="server-details-wrapper">
				   <div class="server-details-container m-r3">
				   <div class="wandwidth-title">
					<h3>Server &amp; Network Details</h3>
				   </div>
				   <div class="server-details-inner">
				      <ul>
						<li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>Server Name</span></li>
						<li class="server-version">{$linodelist.label|replace:'linode':'server'}</li>
					  </ul>
					  <ul>
						<li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span> IPv4:</span></li>
						<li class="server-version"><div id="ipv4">{$linodelist.ipv4.0}</div>&nbsp;&nbsp;
							<a href="javascript:void(0);" onclick="copyToClipboard('#ipv4');"><i class="fa fa-files-o" aria-hidden="true"></i></a>
						</li>
					  </ul>
					  <ul>
						<li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span> IPv6:</span></li>
						<li class="server-version"><div id="ipv6">{$linodelist.ipv6}</div>&nbsp;&nbsp;
							<a href="javascript:void(0);" onclick="copyToClipboard('#ipv6');"><i class="fa fa-files-o" aria-hidden="true"></i></a>
						</li>
					  </ul>
					  <ul>
						<li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>Operating System </span></li>
						<li class="server-version">{$templatedetail.label}</li>
					  </ul>
					  <ul>
						<li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>Region</span></li>
						<li class="server-version">{$locationLabel}</li>
					  </ul>
					  <ul>
						<li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>SSH Access</span></li>
						<li class="server-version"><div id="sshkey">ssh root@{$linodelist.ipv4.0}</div>&nbsp;&nbsp;
							<a href="javascript:void(0);" onclick="copyToClipboard('#sshkey');"><i class="fa fa-files-o" aria-hidden="true"></i></a>
						</li>
					  </ul>
					  <ul>
						<li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>Lish via SSH</span></li>
						<li class="server-version"><div id="lashssh">ssh -t {$moduledata.configoption7}@{$lishsshregion} {$linodelist.label}</div>&nbsp;&nbsp;
							<a href="javascript:void(0);" onclick="copyToClipboard('#lashssh');"><i class="fa fa-files-o" aria-hidden="true"></i></a>
						</li>
					  </ul>
					 <!-- <ul>
						<li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>DNS Resolvers (IPv4)</span></li>
						<li class="server-version">Germany</li>
					  </ul>
					  <ul>
						<li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>DNS Resolvers (IPv6)</span></li>
						<li class="server-version">Germany</li>
					  </ul>-->
					  <ul  class="m-b-0">
						<li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>Last Updated</span></li>
						<li class="server-version">{$linodelist.updated}</li>
					  </ul>

				   </div>
				   </div>

			   <div class="graphs-wrapper">
				   <div class="server-satus-title">
					 <h3>Graphs</h3>
					<select class="graph-select" onchange="graph_select(event);" id="graph_select">
						<option value="24hour">Last 24 Hours</option>
						<option value="30days">Last 30 Days</option>
					</select>
				   </div>
				   <div class="graphs-inner-box">
						  <ul class="graphs-tabs">
							<li><a href="javascript:void(0);" onclick="cpu_detail(this);" class="active">CPU Usage</a></li>
							<li><a href="javascript:void(0);" onclick="ipv4_detail(this);">IPv4 Traffic</a></li>
							<li><a href="javascript:void(0);" onclick="ipv6_detail(this);">IPv6 Traffic</a></li>
							<li><a href="javascript:void(0);" onclick="diskio_detail(this);">Disk IO</a></li>                                    
						  </ul>
						 <div class="map">	
							<div class="graph_spin" style="display:none;"><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div>	
							<script src="https://code.highcharts.com/highcharts.js"></script>
							<!--<script src="https://code.highcharts.com/modules/data.js"></script>-->
							<script src="https://code.highcharts.com/modules/accessibility.js"></script>
							<script src="https://code.highcharts.com/modules/exporting.js"></script>
							<script src="https://code.highcharts.com/modules/export-data.js"></script>
							<figure class="highcharts-figure"><div id="container1" style="min-width: 310px; height: 400px; margin: 0 auto"></div></figure>
						<!--	<img src="modules/servers/linodes/images/server/graph-img.png" alt="server icon"/>-->
						 </div>
				   </div>
			   </div>
	<!--  start 50% col --->
		<div class="bandwidth-snapshop-wrapper row">
			<div class="col-md-6">
			   <div class="wandwidth-container wd-100">
				   <div class="wandwidth-title">
						<h3>Disk Storage</h3>
				   </div>
				   <div class="wandwidth-container-inner rescue-bg">
						<div class="progress-circle over50 p{math equation="((y/x) * 100)" x=$linodelist.specs.disk y=$usedDisk}">
						   <span>{math equation="((y/x) * 100)" x=$linodelist.specs.disk y=$usedDisk}%</span>
						   <div class="left-half-clipper">
							  <div class="first50-bar"></div>
							  <div class="value-bar"></div>
						   </div>
						</div>
						<h4><img src="modules/servers/linodes/images/server/mazerment.png" alt="mazerment"/>{$usedDisk} MB/{$linodelist.specs.disk} MB </h4>
				   </div>
				 </div>
			</div>
	<!--  Rescue div start --->
			<div class="col-md-6" style="display:{$rescueDiv};">
			   <div class="snapshot-container-right wd-100">
					<div class="wandwidth-title">
						<h3>Rescue</h3>
						<p style="display:none;">If you suspect that your primary filesystem is corrupt, use the Linode Manager to boot your Linode into Rescue Mode. This is a safe environment for performing many system recovery and disk management tasks.</p>
					</div>
					<div class="snapshot-container-inner rescue-bg">
						<div id="rescueDetail"><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div>
					</div>
				</div>
			</div>
		<!-- change password div start-->
			<div class="col-md-6" style="display:{$rootPasswordDiv};">
				<div class="rescue-container-right wd-100">
				   <div class="wandwidth-title">
						<h3>Reset Root Password</h3>
				   </div>
				   <div class="rescue-container-inner rescue-bg">
						<select name="disklabel" class="form-control" id="disknameid" style="margin-bottom: 17px;">
							{foreach from=$disklist.data key=num item=disk}
								{if $disk.filesystem == "ext4"}
									<option value="{$disk.id}">{$disk.label}</option>
								{/if}
							{/foreach}
						</select>						
						<input id="pass_log_id" type="password" name="rootPSW" value="{$rootpw}" class="form-control">
						<span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password" style="float: right;margin-top: -25px;margin-right: 19px;"></span>
						<button href="#" class="btn-style btn-color-4 enable-btn" onclick="resetrootpassword(this);"><i class="fa fa-key" aria-hidden="true" style="margin-right: 8px;"></i>Reset Root Password</button>
				   </div>
				</div>
			</div>
		<!--  rebuild div start -->
			<div class="col-md-6" style="display:{$rebuildDiv};">
				<div class="rescue-container-right wd-100">
				   <div class="wandwidth-title">
						<h3>REBUILD</h3>
				   </div>
				   <div class="rescue-container-inner rescue-bg">
						<form id="rebuildosform" onsubmit="return false;">
							<input type="hidden" name="linodeid" value="{$linodeId}">
							<input type="hidden" name="customaction" value="rebuild">
							<input type="hidden" name="pid" value="{$moduledata.pid}">
							<input type="hidden" name="datacenter" value="{$moduleParams.customfields.datacenter}">
							<input type="hidden" name="language" value="{$clientsdetails.language}">
							<input type="hidden" name="rootPSW" value="{$rootpw}">
							<select class="form-control" id="ostemplate" name="tempid">
								{foreach from=$ostemplates key=num item=templates}
                                  <option value="{$templates.id}" {if $templatedetail.label eq $templates.template}selected="selected" {/if}>{$templates.template}</option>
								{/foreach}
							</select>
							<button href="#" class="rebuild-btn" onclick="rebuildOs(this, '{$serviceid}', '{$language.linoderebuildconfirm}');">Rebuild</button>
						</form>
					</div>
				</div>
			</div>			   
		</div>		
	<!--  end 50% col --->
			   <!-- snapshot detail -->
			{if $linodelist.backups.enabled == '1'}
			   <div class= "snapshots-wrapper">
				<div class="wandwidth-title">
					<h3>Backups snapshot detail</h3>
					<ul class="nav nav-tabs power_tab ip_button">
                       <li style="display: inline-flex;"><input type="text" class="form-control" name="snapshotlabel" id="snapshotlabel" value="" style="height: 33px;width: 133px;margin-right: 5px;">
						   <a href="javascript:void(0);" onclick="addSnapshot(this, '{$linodeId}');" style="height: 35px;">Take Snapshot</a> </li>
                    </ul>
				</div>	
				 <div class="table_section">
                <div class="table-responsive">
                  <table class="table">
                     <thead>
						<tr role="row">
                          <th>Label</th>
                          <th>{$language.linodeDate}</th>
                          <th>Disks</th>
						  <th>Space Required</th>
						  <th>Action</th>
                       </tr>
                    </thead>					
                    <tbody id="snapshotdetail"><tr><td colspan="100%"><div><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div></td></tr></tbody>  
                  </table>
                </div>
              </div>
			  </div>
			 {/if}
			 <!-- IP List -->
			   <div class= "ip-wrapper" style="display:{$ipListDiv};">
				<div class="wandwidth-title">
					<h3>IP List</h3>
					<ul class="nav nav-tabs power_tab ip_button">
                       <li style="display:{$addPrivateDiv};"> <a href="javascript:void(0);" onclick="addserverips(this, 'private', '{$linodeId}', '{$serviceid}', '{$clientsdetails.language}');">{$language.linodeaddPrivateIP}</a> </li>
                       <li style="display:{$addPublicDiv};"> <a href="javascript:void(0);"  onclick="addserverips(this, 'public', '{$linodeId}', '{$serviceid}', '{$clientsdetails.language}');">{$language.linodaddPublicIP}</a> </li>                                        
                    </ul>
				</div>	
				 <div class="table_section">
                <div class="table-responsive">
                  <table class="table">
                     <thead>
						<tr role="row">
						   <th>{$language.linodeIpAddress}</th>
                           <th>{$language.linodeType}</th>
                           <th style="display:{$rdnsDiv};">{$language.linodeRDNSName}</th>                                                            
                           <th style="display:{$rdnsDiv};">{$language.linodeAction}</th>
                       </tr>
                    </thead>
                    <tbody id="dnsiplist"><tr><td colspan="100%"><div><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div></td></tr></tbody>     
                  </table>
                </div>
              </div>
			  </div>
			  
			   <!-- logs detail -->
			   <div class= "logs-wrapper" style="display:{$serverActivitylogDiv};">
				<div class="wandwidth-title">
					<h3>Server activity log</h3>
				</div>	
				 <div class="table_section">
                <div class="table-responsive">
                  <table class="table">
                     <thead>
						<tr role="row">
                          <th>{$language.linodeId}</th>
                          <th>{$language.linodeAction}</th>
                          <th>{$language.linodeDate}</th>
                          <th>{$language.linodestatus}</th>
                       </tr>
                    </thead>
                    <tbody id="clientlogbody"><tr><td colspan="100%"><div><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div></td></tr></tbody>
                  </table>
                </div>
              </div>
			  </div>			  
		  </div>
		   <!-- end new html of server -->

            </div>
            <!-- col-12 ends--> 

        </div>
        <!-- row  ends --> 

    </section>
    <!-- maincontent ends --> 

</div><!-- {debug} -->

