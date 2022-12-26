<script  src="{$WEB_ROOT}/modules/servers/awslightsail/js/function.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript" src="{$WEB_ROOT}/modules/servers/awslightsail/js/smoke/smoke.js"></script>
<script type="text/javascript" language="javascript" src="{$WEB_ROOT}/modules/servers/awslightsail/js/smoke/smoke.min.js"></script>
<link rel="stylesheet" href="{$WEB_ROOT}/modules/servers/awslightsail/css/smoke.css">
<link rel="stylesheet" href="{$WEB_ROOT}/modules/servers/awslightsail/css/style.css">
<link rel="stylesheet" href="{$WEB_ROOT}/modules/servers/awslightsail/css/style-new.css">
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
{*<link rel="stylesheet" href="{$WEB_ROOT}modules/servers/awslightsail/css/jQuery-plugin-progressbar.css">*}
<script src="https://code.highcharts.com/highcharts.js"></script>
 
{literal}
    <script>
        jQuery(document).ready(function () {
            var graphtype = jQuery('#graph_type option:selected').text();
            var graphtime = jQuery('#graph_select option:selected').val();
            graph_select(graphtime, graphtype);
            getsnapshotList();
            getHostory();
            //	getaccessdetail();

        });
    </script>
{/literal}
 
<div id="loaderabckground" style="display: none;"></div>
<div id="serverloader" style="display: none;"><img src="{$WEB_ROOT}/modules/servers/awslightsail/images/loader-metric.gif"></div>
<div id="wrapper" class="{$template}">
    <section class="main-section-aws "><!-- maincontent starts -->
        <div class="row"><!-- row starts -->
            <div class="clearfix"></div>
            <div class="col-lg-12"><!-- col-12 starts -->
                <div id="custon_tab_container"></div>
                <div class="server-satus-wrapper">
                    <div class="server-satus-running">
                        <div class="server-satus-title">
                            <div id="serverlabel"><h3>{$name}</h3>
                            </div>
                            <h5 style="text-transform: capitalize;">{$status}</h5>
                        </div>

                        <div class="server-satus-inner-box">
                            <div class="reboot_success"></div>
                            <div class="reboot_error"></div>
                            <div class="server-satus-cantos" id="server_icon">
                                <!--<i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i>-->
                                <img src="{$WEB_ROOT}/modules/servers/awslightsail/images/{$osImageName}" style="width: 120px;height: 120px;" alt="server icon"/>
                                <!--<p>templatedetail.label</p>-->
                            </div>
                            <div class="server-satus-space-right">
                                <ul class="server-top-ul">					
                                    <li><i class="fa fa-database" aria-hidden="true"></i><span>Transfer:<b>{$transfer}</b></span></li>							
                                    <li><i class="fas fa-ethernet" aria-hidden="true"></i><span>vCPUl:<b>{$vCPU}</b></span></li>
                                    <li><i class="fas fa-sd-card" aria-hidden="true"></i><span>RAM:<b>{$ramsize}</b></span></li>
                                    <li><i class="far fa-hdd" aria-hidden="true"></i><span>Disk local:<b>{$storagedisk[0]['sizeInGb']}GB</b></span></li>
                                </ul>						 						  
                            </div>						
                            <div class="button-box">

                                <button href="#" class="btn-style btn-color-1" id="reboot"  onclick="reboot(this);"><i class="fa fa-power-off" aria-hidden="true"></i>Reboot</button>
                                {if $status == 'Running'}
                                    <button href="#" class="btn-style btn-color-2" onclick="stop(this);"><i class="fa fa-pause-circle" aria-hidden="true"></i>Power OFF</button>
                                    <button href="#" class="btn-style btn-color-3" onclick="start(this);" style="display:none"><i class="fa fa-play-circle" aria-hidden="true"></i>Power ON</button>
                                {else}
                                <button href="#" class="btn-style btn-color-2" onclick="stop(this);"  style="display:none"><i class="fa fa-pause-circle" aria-hidden="true"></i>Power OFF</button>
                                    <button href="#" class="btn-style btn-color-3" onclick="start(this);"><i class="fa fa-play-circle" aria-hidden="true" ></i>Power ON</button>    
                                {/if}
                                <button href="#" class="btn-style btn-color-1" id="reboot"  onclick="conndetail(this);"><i class="fa fa-info-circle" aria-hidden="true"></i>Connection Details</button>

                            </div>
                        </div>
                    </div>
                    <!-- start here -->
                    <div class="server-details-wrapper">
                        <div class="server-details-container m-r3">
                            <div class="wandwidth-title">
                                <h3>Server &amp; Network Details</h3>
                            </div>
                            <div class="server-details-inner">
                                <ul>
                                    <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>Server Name</span></li>
                                    <li class="server-version">{$name}</li>
                                </ul>
                                <ul>
                                    <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span> Static IP:</span></li>
                                    <li class="server-version"><div id="ipv4">{$publicIp}</div>&nbsp;&nbsp;
                                        <a href="javascript:void(0);" onclick="copyToClipboard('#ipv4');"><i class="fa fa-files-o" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
                                <ul>
                                    <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span> Private IP:</span></li>
                                    <li class="server-version"><div id="ipv6">{$privateIp}</div>&nbsp;&nbsp;
                                        <a href="javascript:void(0);" onclick="copyToClipboard('#ipv6');"><i class="fa fa-files-o" aria-hidden="true"></i></a>
                                    </li>
                                </ul>
                                <ul>
                                    <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>Region</span></li>
                                    <li class="server-version">{$regionname}, {$availablezone}</li>
                                </ul>
                            </div>

                            <div class="server-details-container m-r3 firewall">
                                <div class="wandwidth-title">
                                    <h3>Firewall</h3>
                                </div>

                                <div class="server-details-inner">
                                    <div class="rule_success" ></div>
                                    <div class="rule_error" ></div>
                                    <p id="txt-tg">Create rules to open ports to the internet, or to a specific IP address or range.</p>

                                    <button id="add-ntrule"  onclick="addfirewallrule();">Add rule</button>
                                    <div id="firewallist_spin" style="display:none;"><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div>	


                                    <table class="table table-striped" id="firewal-rl-list">
                                        <thead>
                                            <tr>
                                                <th>Application</th>
                                                <th>Protocol</th>
                                                <th>Port or range / Code</th>
                                                <th>Restricted to</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>						    
                                        <tbody id="firewalldetail"><tr><td colspan="100%"><div><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div></td></tr></tbody>						    
                                    </table>
                                </div>
                            </div>
                            <div class="graphs-wrapper">
                                <div class="server-satus-title">
                                    <h3>Graphs</h3>
                                    <select class="graph-select" onchange="graph_time(this);" id="graph_select">
                                        <option value="1h">Last 1 Hour</option>
                                        <option value="6h">Last 6 Hours</option>
                                        <option value="1d">Last 1 Day</option>
                                        <option value="1w">Last 1 Week</option>
                                        <option value="2w">Last 2 Week</option>
                                    </select>
                                </div>
                                <div class="graphs-inner-box">
                                    <select id="graph_type"   onchange="graph_type();">
                                        <option >CPU Overview</option>
                                        <option >CPU burst capacity (minutes)</option>
                                        <option >CPU burst capacity (percentages)</option>
                                        <option >Incoming network traffic</option>
                                        <option >Outgoing network traffic</option>
                                        <option >Status check failures</option>
                                        <option >Instance status check failures</option>
                                        <option >System status check failures</option>
                                    </select>
                                    {* <ul class="graphs-tabs">
                                    <li><a href="javascript:void(0);" onclick="cpuOverview(this);" class="active">CPU Overview</a></li>
                                    <li><a href="javascript:void(0);" onclick="cpuBurstCapacityMin(this);">CPU burst capacity (minutes)</a></li>
                                    <li><a href="javascript:void(0);" onclick="cpuBurstCapacityPer(this);">CPU burst capacity (percentages)</a></li>
                                    <li><a href="javascript:void(0);" onclick="incomingNetwork(this);">Incoming network traffic</a></li>  
                                    <li><a href="javascript:void(0);" onclick="outgoingNetwork(this);">Outgoing network traffic</a></li>
                                    <li><a href="javascript:void(0);" onclick="systemCheckFail(this);">Status check failures</a></li> 
                                    <li><a href="javascript:void(0);" onclick="systemCheckInstanceFail(this);">Instance status check failures</a></li>	
                                    <li><a href="javascript:void(0);" onclick="systemCheckSystemFail(this);">System status check failures</a></li>							
                                    </ul>*}
                                    <div class="map">	
                                        <div class="graph_spin" style="display:none;"><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div>	
                                        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
                                        <script src="https://code.highcharts.com/modules/exporting.js"></script>
                                        <script src="https://code.highcharts.com/modules/export-data.js"></script>
                                        <figure class="highcharts-figure"><div id="container1"></div></figure>
                                    </div>
                                </div>
                            </div>

                            <!--  start 50% col --->
                            <div class="bandwidth-snapshop-wrapper row">

                                <!-- disk div start-->
                                <div class="col-md-12" style="display:{$rootPasswordDiv};">
                                    <div class="rescue-container-right wd-100">
                                        <div class="wandwidth-title">
                                            <h3>Disk Storage</h3>
                                        </div>
                                        <div class="rescue-container-inner rescue-bg">
                                            <div class="disk_spin" style="display:none;"><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div>

                                            <ul id="disk-ul">
                                                {foreach from=$storagedisk item=diskdata}
                                                    <li> 
                                                        {*{if !empty({$diskdata['name']})}
                                                        <a href="javascript:void(0);" onclick="detachdisk(this,'{$diskdata['name']}');"  id="disk-detached">Detach</a>
                                                        {/if} *}
                                                        <h5 align="left"><strong>Disk Name:&nbsp;&nbsp;&nbsp;</strong><span>{if empty({$diskdata['name']})} System Disk {else} {$diskdata['name']}{/if}</span></h5> 
                                                        <h5 align="left"><strong>Disk Path:&nbsp;&nbsp;&nbsp;</strong><span>{$diskdata['path']}</span></h5>
                                                        <h5 align="left"><strong>Disk Storage:&nbsp;&nbsp;&nbsp;</strong> <span> {$diskdata['sizeInGb']} </span> GB, block storage disk</h5>

                                                    </li>
                                                {/foreach}
                                            </ul>  
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <!--  end 50% col --->

                            <!-- snapshot detail -->

                            <div class= "snapshots-wrapper">
                                <div class="snapshot_success" ></div>
                                <div class="snapshot_error" ></div>
                                <div class="wandwidth-title">
                                    <h3>Backups snapshot detail</h3>
                                    <ul class="nav nav-tabs power_tab ip_button">
                                        <li style="display: inline-flex;"><input type="text" class="form-control" name="snapshotlabel" id="snapshotlabel" value="" style="height: 33px;width: 133px;margin-right: 5px;">
                                    <i class="fa-li fa fa-spinner fa-spin" id="take-snp-ldr" style="display:none"></i>        
                                            <a href="javascript:void(0);" onclick="addSnapshot(this);" style="height: 33px; ;">Take Snapshot</a> </li>
                                    </ul>
                                </div>	
                                <div id="snapshotloader"></div>
                                <div class="table_section">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr role="row">
                                                    <th>Name</th>
                                                    <th>Created Date</th>
                                                    <th>Disks</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>					
                                            <tbody id="snapshotdetail"><tr><td colspan="100%"><div><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div></td></tr></tbody>  
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- ip detaial -->
                            <div class= "ip-wrapper">
                                <div class="wandwidth-title">
                                    <h3>IP List</h3>

                                </div>	
                                <div class="table_section">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr role="row">
                                                    <th>IP Address</th>
                                                    <th>Type</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dnsiplist"><tr><td>{$privateIp}</td><td>Private</td></tr><tr><td>{$publicIp}</td><td>Public</td></tr></tbody>     
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class= "logs-wrapper" style="">
                                <div class="wandwidth-title">
                                    <h3>Server activity log</h3>
                                </div>	
                                <div class="table_section">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr role="row">
                                                    <th>Action</th>
                                                    <th>Date</th>
                                                    <th>STATUS</th>
                                                </tr>
                                            </thead>
                                            <tbody id="showHistory"><tr><td colspan="100%"><div><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div></td></tr></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- col-12 ends--> 
                    </div>
                    <!-- row  ends --> 
                </div>
            </div>
        </div>

    </section>
    <!-- maincontent ends --> 
<!-- add firewal rule Modal -->
<div  id="addrulemodal" class="modal fade in" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>Add Rule</strong></h5>
                <button type="button" onclick="close_modal();" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
                <div class="rule_spin-modal" style="display:none;"><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div>
                <div id="delresponse"   ></div> 
                <p>Specify a port and protocol to open. Specify a port range using a dash, such as 0 - 65535.</p>
                <form method="post" id="rule-firewal">
                    <div class="row">
                    <div class="form-group col-md-5">
                        <label for="application">Application</label>
                        <select name="" id="application-firewal" class="form-control">
                            <option value="Custom">Custom</option>
                            <option value="">All TCP</option>
                            <option value="">All UDP</option>
                            <option value="">All protocols</option>
                            <option value="">SSH</option>
                            <option value="">RDP</option>
                            <option value="">HTTP</option>
                            <option value="">HTTPS</option>
                            <option value="">MySQL/Aurora</option>
                            <option value="">Oracle-RDS</option>
                            <option value="">PostgreSQL</option>
                            <option value="">DNS (TCP)</option>
                            <option value="">DNS (UDP)</option>
                            <option value="">Ping (ICMP)</option>
                            <option value="">Custom ICMP</option>
                            <option value="">All ICMP</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 protocol-div">
                        <label for="protocol">Protocol</label>
                        <select name="protocol" id="protocol-firewal"  class="form-control">
                            <option value="TCP">TCP</option>
                            <option value="UDP">UDP</option>

                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="port">Port</label>
                        <input name="port" id="port-firewal"  value="" class="form-control">

                    </div>
                    </div>
                    <div class="fr-ip-checkbox">
                    <input class="" id="restrictToIpAddressCheckbox" type="checkbox"> Restrict to IP address
                    </div>
                    <div id="ip-restrict"class="col-md-12">
                    </div> 
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id='add_rule' onclick="insertfirewallrule();">Create</button>
            </div>
        </div>
    </div>
</div>

<!-- update firewal rule Modal -->
<div class="modal fade" id="updaterulemodal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><strong>Update Rule</strong></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body col-md-12">
                <div class="updaterule_spin-modal" style="display:none;"><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div>
                <div id="updtruleresponse"   ></div>    
                <p>Specify a port and protocol to open. Specify a port range using a dash, such as 0 - 65535.</p>
                <form method="post" id="updtrule-firewal">
                    <div class="row">
                        <div class="form-group col-md-5">
                            <label for="application">Application</label>
                            <select name="" id="updt-application-firewal" class="form-control">
                                <option value="Custom">Custom</option>
                                <option value="All TCP">All TCP</option>
                                <option value="All UDP">All UDP</option>
                                <option value="All protocols">All protocols</option>
                                <option value="SSH">SSH</option>
                                <option value="RDP">RDP</option>
                                <option value="HTTP">HTTP</option>
                                <option value="HTTPs">HTTPS</option>
                                <option value="MySQL/Aurora">MySQL/Aurora</option>
                                <option value="Oracle-RDS">Oracle-RDS</option>
                                <option value="PostgreSQL">PostgreSQL</option>
                                <option value="DNS (TCP)">DNS (TCP)</option>
                                <option value="DNS (UDP)">DNS (UDP)</option>
                                <option value="Ping (ICMP)">Ping (ICMP)</option>
                                <option value="Custom ICMP">Custom ICMP</option>
                                <option value="All ICMP">All ICMP</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 protocol-div">
                            <label for="protocol">Protocol</label>
                            <select name="protocol" id="updt-protocol-firewal" class="form-control">
                                <option value="TCP">TCP</option>
                                <option value="UDP">UDP</option>
                                <option value="ALL">ALL</option>
                                <option value="ICMP">ICMP</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="port">Port</label>
                            <input name="port" id="updt-port-firewal"  value="" class="form-control">
                        </div>
                    </div>
                    <div class="update-ip-checkbox">
                    <input class="" id="restrictToIpAddressCheckboxupdate" type="checkbox"> Restrict to IP address
                    </div>
                    <div id="ip-restrict-update"class="col-md-12">
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="updtruledata">Update</button>
            </div>
        </div>
    </div>
</div>

<!-- close Modal Medium -->

<!-- access detail Modal Medium -->
<div class="modal fade" id="connectiondetailmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header">
                <h4 class="modal-title w-100" id="myModalLabel"> Server Connection Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!--Body-->

            <div class="modal-body" id="deatil-modal-body">
                <div class="details-spin-modal" style="display:none;"><i class="fa-li fa fa-spinner fa-spin" style="text-align: center;width: 100%;font-size: 25px;"></i></div>

                <p id="detail-ptag">Below you will find connection details to your  server.</p> 
                <div class="form-group">
                    <label for="ip">IP Address:</label>
                    <input type="text" class="form-control" id="getdeailip" value=" {$instaceaccessdetail['ipAddress']}" readonly>
                    <button class="copytxtbtn"  onclick="copytextFunction(this, 'getdeailip')">Copy</button>
                </div>
                <div class="form-group">
                    <label for="protocol">Protocol:</label>
                    <input type="text" class="form-control" id="getprotocol" value="{$instaceaccessdetail['protocol']}" readonly>
                    <button class="copytxtbtn" onclick="copytextFunction(this, 'getprotocol')">Copy</button>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="getusername" value="{$instaceaccessdetail['username']}" readonly>
                    <button class="copytxtbtn" onclick="copytextFunction(this, 'getusername')">Copy</button>
                </div>
                {if $windowaccesspassword}
                <div class="form-group">
                    <label for="username">Password:</label>
                    <input type="text" class="form-control" id="getusername" value="{$windowaccesspassword}" readonly>
                    <button class="copytxtbtn" onclick="copytextFunction(this, 'getusername')">Copy</button>
                </div>
                {/if}
                <div class="form-group">
                    <label for="sshprivatekey">SSH Private Key</label>
                    <textarea class="form-control rounded-0" id="getsshprivatekey" rows="10" readonly>{$instacekeysdetail['instance_privatekey']}</textarea>
                    <button class="copytxtbtn" onclick="copytextFunction(this, 'getsshprivatekey')">Copy</button>
                </div>
                <div class="form-group">
                    <label for="sshcertkey">SSH Cert Key</label>
                    <textarea class="form-control rounded-0" id="getsshcertkey" rows="10" readonly>{$instacekeysdetail['instance_publickey']}</textarea>
                    <button class="copytxtbtn" onclick="copytextFunction(this, 'getsshcertkey')">Copy</button>
                </div>
            </div>

            <!--Footer-->
            <div class="modal-footer" id="modal-ft">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>
</div>



 

