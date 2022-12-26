
<link href="modules/servers/vmware/css/style.css" rel="stylesheet">
<link href="modules/servers/vmware/css/custom.css" rel="stylesheet"/>
<script type="text/javascript" language="javascript" src="modules/servers/vmware/js/smoke/smoke.js"></script>

<script type="text/javascript" language="javascript" src="modules/servers/vmware/js/smoke/smoke.min.js"></script>

<script src="modules/servers/vmware/js/highcharts.js"></script>

<link rel="stylesheet" href="modules/servers/vmware/css/smoke.css"/>
<div id="loaderabckground" style="display: none;"></div>
<div id="serverloader" style="display: none;"><img src="modules/servers/vmware/images/loading.gif"></div>

 
{if $vmData['config']['guestFullName']|lower|strstr:"centos"}
   {assign var="osImageName" value="centos.png"}
{elseif $vmData['config']['guestFullName']|lower|strstr:"ubuntu"}
   {assign var="osImageName" value="ubuntu.png"}
{elseif $vmData['config']['guestFullName']|lower|strstr:"debian"}
   {assign var="osImageName" value="debain.png"}
{elseif $vmData['config']['guestFullName']|lower|strstr:"windows"}
   {assign var="osImageName" value="window.png"}
{elseif $vmData['config']['guestFullName']|lower|strstr:"other"}
   {assign var="osImageName" value="otheros.png"}

{/if}
<div id="wrapper">
    <section class="manage_content">
        <!-- maincontent starts -->
        <div class="row">
            <!-- row starts -->
            <div class="clearfix"></div>

            <div class="col-lg-12 col-md-12 col-sm-12">
                <!-- col-12 starts -->

                <div id="custon_tab_container"></div>

                {if $consoleError}
                    <div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close"
                            title="close">×</a>{$consoleError}</div>
                {/if}


<div class="server-satus-running">
    <div class="server-satus-title">
        <div id="serverlabel">
            <h3>{$vmName}</h3>
        </div>
        <h5 style="text-transform: capitalize;">
            {if $vmData.runtime.powerState eq 'poweredOff'}
                {$language.vm_powered_off}
            {elseif $vmData.runtime.powerState eq 'poweredOn'} {$language.vm_powered_on}
            {else} {$vmData.runtime.powerState}
            {/if}
        </h5>
    </div>
   <div class="server-satus-inner-box">
        <div class="server-satus-cantos" id="server_icon"><img src="modules/servers/vmware/images/server/{$osImageName}" alt="server icon" title="{$vmData['config']['guestFullName']}"></div>
 
        <div class="button-box">
            {if !$tabAccess.power}
                <button href="#" onclick="managePowerTab(this, 0 ,'{$language.vm_powered}')" class="btn-style btn-color-1 power_content2"><i class="fa fa-power-off" aria-hidden="true"></i> {$language.vm_powered}</button>

            {/if}
            {if !$tabAccess.pause}
                {if $vmData.runtime.powerState eq 'suspended'}
                    <button href="#"   onclick="managePowerTab(this, 1, '{$language.vm_unpaused}')" class="btn-style btn-color-3"><i class="fa fa-pause-circle" aria-hidden="true"></i>{$language.vm_unpaused}</button>
                {else}
                <button href="#"   onclick="managePowerTab(this, 1, '{$language.vm_paused}')" class="btn-style btn-color-2 power_content2"><i class="fa fa-pause-circle" aria-hidden="true"></i>{$language.vm_paused}</button>
                {/if}

            {/if}
            {if !$tabAccess.softreboot}
                <button href="#"  onclick="managePowerTab(this, 2, '{$language.vm_soft_reboot}')" class="btn-style btn-color-1 power_content2 warning"><i class="fa fa-power-off" aria-hidden="true"></i>  {$language.vm_soft_reboot}</button>
            {/if}
            {if !$tabAccess.hardreboot}
                <button href="#" onclick="managePowerTab(this, 3, '{$language.vm_hard_reboot}')" class="btn-style btn-color-1 power_content2"><i class="fa fa-power-off" aria-hidden="true"></i> {$language.vm_hard_reboot}</button>
            {/if}
            {if $esxi neq 1}

                {if !$tabAccess.reinstall}

                    <button href="#" class="btn-style btn-color-3" onclick="managePowerTab(this, 4, '{$language.vm_reinstall}')"><i class="fas fa-hdd" aria-hidden="true"></i>{$language.vm_reinstall}</button>

                {/if}

            {/if}
            {* {if $vm_guest_os eq "Windows"} *}
                <button   onclick="managePowerTab(this, 5,'{$language.vm_reset_password}')" class="btn-style btn-color-1 power_content2 warning"><i class="fa fa-key" aria-hidden="true"></i>   {$language.vm_reset_password}</button>
            {* {/if} *}
            <form target="_blank" action="{$console_link}" method="post" style="display:none;" id="console_form">
                <input type="hidden" name="console_token" value="{$console_token}">
                <input type="submit" name="console" value="Console">
            </form>
            <button  onclick="jQuery('#console_form').submit(); return false;"  class="btn-style btn-color-console power_content2"><i class="fa fa-solid fa-terminal" aria-hidden="true"></i>{$language.vm_console}</button>
        </div>
   </div>
</div>

<div class="server-details-wrapper">
    <div class="server-details-container m-r3">
       <div class="wandwidth-title">
        <h3>{$language.vm_deail}</h3>
       </div>
       <div class="server-details-inner">
          <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_name}</span></li>
            <li class="server-version">{$vmName}</li>
          </ul>
          <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span> {$language.vm_power_state}:</span></li>
            <li class="server-version"><div id="ipv4">{if $vmData.runtime.powerState eq 'poweredOff'}
                {$language.vm_powered_off}
            {elseif $vmData.runtime.powerState eq 'poweredOn'} {$language.vm_powered_on}
            {else} {$vmData.runtime.powerState}
            {/if} &nbsp;</div>&nbsp;&nbsp;
            <div class="custom_switch">
                <span {if $vmData.runtime.powerState eq 'poweredOn'}class="poweredOn"
                    {/if} id="poweredOn" onclick="powerOn(this, 'poweredOn');">ON</span>

                <span {if $vmData.runtime.powerState eq 'poweredOff'}class="poweredOff"
                    {/if} id="poweredOff"
                    onclick="powerOff(this, 'poweredOff', '{$language.vm_power_off_alert_msg}');">OFF</span>
            </div>
            {* <a href="javascript:void(0);" onclick="copyToClipboard('#ipv4');"><i class="fa fa-files-o" aria-hidden="true"></i></a> *}
            </li>
          </ul>
          {if $vmData.guest.ipAddress}
          <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span> {$language.vm_guest_ip_address}:</span></li>
            <li class="server-version"><div id="ipv6">{$vmData.guest.ipAddress}</div>&nbsp;&nbsp;
                <a href="javascript:void(0);" onclick="copyToClipboard('#ipv6');"><i class="fa fa-files-o" aria-hidden="true"></i></a>
            </li>
          </ul>
          {/if}
          <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>    {$language.vm_guest_dedecated_os} </span></li>
            <li class="server-version">{$vmData.config.guestFullName}</li>
          </ul>
          {if $vmData.guest.hostName}
          <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_guest_host_name}</span></li>
            <li class="server-version">{$vmData.guest.hostName}</li>
          </ul>
          {/if}

          <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_tools_status}</span></li>
            <li class="server-version">
                {if $vmData.guest.toolsStatus eq 'toolsNotInstalled'}
                {$language.vm_toolsNotInstalled} {else} {$vmData.guest.toolsStatus}
                {/if}</li>
          </ul>
           
          <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_tools_version}</span></li>
            <li class="server-version"><div id="sshkey"> {if $vmData.guest.toolsVersionStatus eq 'guestToolsNotInstalled'}
                    {$language.vm_guesttoolsNotInstalled} {else}
                    {$vmData.guest.toolsVersionStatus} {/if}
                </div>&nbsp;&nbsp;
                <a href="javascript:void(0);" onclick="copyToClipboard('#sshkey');"><i class="fa fa-files-o" aria-hidden="true"></i></a>
            </li>
          </ul>
          {if $recentTask}
          <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_recent_task}</span></li>
            <li class="server-version"><div id="lashssh">{$recentTask} </div>&nbsp;&nbsp;
                <a href="javascript:void(0);" onclick="copyToClipboard('#lashssh');"><i class="fa fa-files-o" aria-hidden="true"></i></a>
            </li>
          </ul>
          {/if}
         <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_memory_size}</span></li>
            <li class="server-version memory">{$memory_stat} 
                <div class="percentagediv">
                    <div class="percentage_inr {$bar_class}"
                        style="width:{if $percentage_mem gt '100'}100{else}{$percentage_mem}{/if}%">
                    </div>
                </div>
            </li>
          </ul>
          <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_number_cpu}</span></li>
            <li class="server-version">{$vmData.config.numCpu}</li>
          </ul>
          {if $updateTime}
          <ul class="m-b-0">
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_system_uptime}</span></li>
            <li class="server-version">{$updateTime}</li>
          </ul>
          {/if}

       </div>
    </div>
</div>

{if $netWorkDetail}
<div class="logs-wrapper" style="display:;">
    <div class="wandwidth-title">
        <h3>{$language.vm_defaultnw}</h3>
    </div>  
    <div class="server-details-inner">
        <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_viewnetworkip}</span></li>
            <li class="server-version">{$netWorkDetail.ip}</li>
        </ul>
        <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_viewnetworkdns}</span></li>
            <li class="server-version">{$netWorkDetail.dns}</li>
        </ul>
        <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_viewnetworkgateway}</span></li>
            <li class="server-version">{$netWorkDetail.gateway}</li>
        </ul>
        <ul>
            <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_viewnetworknetmask}</span></li>
            <li class="server-version">{$netWorkDetail.netmask}</li>
        </ul>
        {if $netWorkDetail.macaddress}
            <ul>
                <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span>{$language.vm_viewnetworknetmacaddress}</span></li>
                <li class="server-version">{$netWorkDetail.macaddress}</li>
            </ul>
        {/if}
    </div>
</div>
{/if}

{if $esxi neq 1}
<div class="logs-wrapper m_us usage-graph">
    <div class="wandwidth-title">
        <h3>{$language.vm_data_usage}</h3>
    </div>
    {* <select class="graph-select" onchange="graph_select(event);" id="graph_select">
        <option value="24hour">Last 24 Hours</option>
        <option value="30days">Last 30 Days</option>
    </select> *}
    <div class="clearfix"></div>
    {if $esxi neq 1}

        <div id="vmTabContent1" class="vmtabs power_sec">

            <div class="row">

                <div class="col-md-12">

                    <div class="tabbable">

                        <ul id="inner_tab1" class="nav nav-tabs power_tab graphtabs">

                            <li class="active" sid="{$serviceid}"
                                onclick="vmware_viewgraph(this, '{$serviceid}', '0', 'graph');">

                                <a class="active" href="javascript:void(0);">

                                    {$language.vm_daily_usage}

                                </a>

                            </li>

                            <li sid="{$serviceid}">

                                <a class="active" href="javascript:void(0);"
                                    onclick="vmware_viewgraph(this, '{$serviceid}', '1', 'graph');">
                                    {$language.vm_monthly_usage}
                                </a>
                            </li>
                            <li>
                                <a class="active" href="javascript:void(0);"
                                    onclick="vmware_viewgraph(this, '{$serviceid}', '2', 'disk');">
                                    {$language.vm_disks}
                                </a>
                            </li>

                        </ul>

                        <div id="vmTabContent10" class="tab_content1 manage_tab_content m_power"
                            style="display:block">
                        </div>

                        <div id="vmTabContent11" class="tab_content1 manage_tab_content m_power">
                        </div>

                        <div id="vmTabContent12" class="tab_content1 manage_tab_content m_power"
                            action="disk">

                        </div>
                    </div>
                </div>
            </div>
        </div>

    {/if}
</div>  
{/if}

{if !$tabAccess.mount || !$tabAccess.upgradevmtool}

<div class="logs-wrapper  m_tool">
    <div class="wandwidth-title">
        <h3>{$language.vm_tools}</h3>
    </div>
    {if !$tabAccess.mount || !$tabAccess.upgradevmtool}
    <div id="vmTabContent3" class="vm_ttostab power_sec">
        <div class="row">
            <div class="col-md-12">
                <div class="tabbable" id="tabs-577515">

                    <ul id="inner_tab3" class="nav nav-tabs power_tab">

                        {if !$tabAccess.mount}

                            <li onclick="toolTab(this, 0)" class="tool_content3 active">

                                <a class="active" href="javascript:void(0);">

                                    {$language.vm_mount}

                                </a>

                            </li>

                        {/if}

                        {if !$tabAccess.upgradevmtool}

                            <li onclick="toolTab(this, 1)" class="tool_content3">

                                <a class="active" href="javascript:void(0);">

                                    {$language.vm_upgrade}

                                </a>

                            </li>

                        {/if}

                    </ul>

                    <div id="toolresponseMessage"></div>

                    {if !$tabAccess.mount}

                        <div id="vmTabContent30" class="tab_content3 manage_tab_content m_power"
                            style="display:block">

                            <div class="tab-pane active ">

                                <p>{$language.vm_mount_text}</p>

                                <div id="mountButtonDiv">

                                    <a href="javascript:void(0);"
                                        onclick="mount_button_action(this, '{if $vmData.runtime.toolsInstallerMounted eq 'true'}{$language.vm_unmount}{else}{$language.vm_mount}{/if}', '{$vmData.runtime.toolsInstallerMounted}', '{$system_url}');"
                                        class="reb_btn">

                                        {if $vmData.runtime.toolsInstallerMounted eq 'true'}{$language.vm_unmount}{else}{$language.vm_mount}{/if}

                                    </a>

                                </div>

                            </div>

                        </div>

                    {/if}

                    {if !$tabAccess.upgradevmtool}

                        <div id="vmTabContent31" class="tab_content3 manage_tab_content m_power"
                            style="display:none">

                            <div class="tab-pane active ">

                                <p>{$language.vm_upgrade_text}</p>

                                <div id="upgradeButtonDiv">

                                    <a href="javascript:void(0);"
                                        onclick="upgrade_button_action(this, '{$language.vm_upgrade}', '{$language.vm_upgrade_alert_msg}');"
                                        class="reb_btn">

                                        {$language.vm_upgrade}

                                    </a>

                                </div>

                            </div>

                        </div>

                    {/if}

                </div>

            </div>

        </div>

    </div>

{/if}
</div>

{/if}

{if !$tabAccess.snapshot}
<div class="logs-wrapper  snapshot_div">
    <div class="wandwidth-title">
        <h3>{$language.vm_snapshot}</h3>
    </div>
    {if !$tabAccess.snapshot}

    <div id="vmTabContent4" class="snapshot_inner power_sec">

        <div class="row">

            <div class="col-md-12">

                <div class="tabbable" id="tabs-577515">

                    <ul id="inner_tab4" class="nav nav-tabs power_tab">

                        <li class="active">

                            <a class="active" href="javascript:void(0);">

                                {$language.vm_create_snapshot}

                            </a>

                        </li>

                        <li>

                            <a class="active" href="javascript:void(0);">

                                {$language.vm_snapshot_list}

                            </a>

                        </li>

                    </ul>

                    <div id="snapshotresponseMessage"></div>

                    <div id="vmTabContent40" class="tab_content4 manage_tab_content m_power"
                        style="display:block">

                        <div class="tab-pane active ">

                            <form id="createsnapshotForm" onsubmit="return false;">

                                <input type="hidden" name="vmname" value="{$vmName}">

                                <input type="hidden" name="ajaxaction" value="createsnapshot">

                                <div class="row cstn-row">

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label class="control-label text-left"> {$language.vm_snapshot_name}</label>
                                            <input type="text" name="snapshot_name" id="snapshot_name" value=""
                                                class="form-control">

                                        </div>

                                    </div>



                                    <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label text-left"> {$language.vm_snapshot_name_desc}</label>

                                            <input type="text" name="snapshot_desc" id="snapshot_desc" value=""
                                                class="form-control">

                                        </div>
                                      </div>
                                  </div>
                           </form>

                            <div id="snapshotButtonDiv">

                                <a href="javascript:void(0);"
                                    onclick="create_snap_shot_button_action(this, '{$language.vm_create_snapshot}');"
                                    class="reb_btn">

                                    {$language.vm_create_snapshot}

                                </a>

                            </div>

                        </div>

                    </div>



                    {*              Snap shot list start here          *}

                    <div id="vmTabContent41" class="tab_content4 manage_tab_content m_power"
                        style="display:none" action="list" name="{$vmName}" loading="{$language.vm_loding}">

                        <div class="tab-pane active ">

                            {if !$tabAccess.revertsnapshot || !$tabAccess.removesnapshot}

                                <div class="snapshotListButtonDiv">

                                    {if !$tabAccess.revertsnapshot}

                                        <a href="javascript:void(0);"
                                            onclick="revert_from_latest_snap_shot_button_action(this, '{$vmName}', '{$language.vm_revert_from_last}', '{$language.vm_revert_from_latest_alert_msg}');"
                                            class="reb_btn green-btn btn_disabled" style="margin: 0;">

                                            {$language.vm_revert_from_last}

                                        </a>

                                    {/if}

                                    {if !$tabAccess.removesnapshot}

                                        <a href="javascript:void(0);"
                                            onclick="remove_all_snap_shot_button_action(this, '{$vmName}', '{$language.vm_remove_all_sp}', '{$language.vm_remove_all_sp_alert_msg}', '{$language.vm_loding}');"
                                            class="reb_btn cst-red-btn btn_disabled" style="margin: 0;">

                                            {$language.vm_remove_all_sp}

                                        </a>

                                    {/if}

                                </div>

                            {/if}

                            <div class="tab-content">

                                <div class="table-container clearfix">

                                    <form onsubmit="return false;" id="removeAllForm">

                                        <table class="table table-list dataTable no-footer dtr-inline"
                                            role="grid">

                                            <thead>

                                                <tr role="row">

                                                    {if !$tabAccess.removesnapshot}<th><input type="checkbox"
                                                            id="checkedall"></th>{/if}

                                                    <th>{$language.vm_snapshot_created_time}</th>

                                                    <th>{$language.vm_snapshot_name}</th>

                                                    <th>{$language.vm_snapshot_name_desc}</th>

                                                    <th>{$language.vm_action}</th>

                                                </tr>

                                            </thead>

                                            <tbody id="snapShotList" name="'{$vmName}'">

                                                <tr role="row" class="odd">
                                                    <td colspan="100%" style="text-align:center;">
                                                        {$language.vm_loding}</td>
                                                </tr>

                                            </tbody>

                                        </table>

                                    </form>

                                    {if !$tabAccess.removesnapshot}

                                        <div class="snapshotListButtonDiv">

                                            <label class="remove-chk"><input type="checkbox" name="child" id="removechild">
                                                {$language.vm_remove_child}</label>

                                            <a href="javascript:void(0);"
                                                onclick="remove_multiple_snap_shot_button_action(this, '{$vmName}', '{$language.vm_remove_multiple_sp}', '{$language.vm_remove_all_snapshot_alert_msg}', '{$language.vm_loding}');"
                                                class="reb_btn cst-red-btn btn_disabled" style="margin: 0;">

                                                {$language.vm_remove_multiple_sp}

                                            </a>

                                        </div>

                                    {/if}

                                </div>

                            </div>

                            {*                        <div id="snapShotList" style="text-align:center;">Loading...</div>*}

                        </div>
                    </div>

                    {*         Snap shot list end here       *}

                </div>
            </div>
        </div>
    </div>
{/if}

</div>
{/if}

{if !$tabAccess.migrate}
<div class="logs-wrapper  snapshot_div">
    <div class="wandwidth-title">
        <h3>{$language.vm_migrate}</h3>
    </div>

    {if !$tabAccess.migrate}

        <div id="vmTabContent5" class="migrate_inner power_sec">
            <div class="row">
                <div class="col-md-12">
                    <div class="tabbable" id="tabs-577515">
                        <div id="migrateresponseMessage"></div>

                        {if !$tabAccess.mount}
                            <div id="vmTabContent30" class="tab_content3 manage_tab_content m_power"
                                style="display:block">

                                <div class="tab-pane active ">
                                    <p>{$language.vm_migrate_text}</p>
                                    <div id="" class="manage_tab_content m_power">
                                        <div class="tab-pane active ">
                                            <form id="migrateVmWare" onsubmit="return false;">
                                                <input type="hidden" name="ajaxaction" value="migrate">

                                                <input type="hidden" name="pid" value="{$pid}">
                                                <input type="hidden" name="api_server" value="{$api_server}">
                                                <input type="hidden" name="r_pool" value="{$resource_pool_id}">

                                                <input type="hidden" name="datacenter" value="{$datacenter}">

                                                <input type="hidden" name="existhost" value="{$vm_host}">

                                                 <div class="row cstn-row">

                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label text-left"> {$language.vm_datacenter}</label>
                                                          
                                                            <select class="form-control" name="vm_dc" id="vm_dc"
                                                                onchange="getDcHost(this)">

                                                                {$dcOptions}

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="control-label text-left"> {$language.vm_host}</label>
                                                          
                                                            <select class="form-control" name="vm_host_name"
                                                                id="vm_host_name">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                            <div id="migrateButtonDiv">

                                                <a href="javascript:void(0);"
                                                    onclick="migrate_button_action(this, '{$language.vm_migrate_send_req}', '{$system_url}', '{$language.vm_migrate_alert_msg}');"
                                                    class="reb_btn">

                                                    {$language.vm_migrate_send_req}

                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        {/if}

                    </div>
                </div>
            </div>
        </div>
    {/if}
</div>
{/if}


   
        </div>

        <!-- col-12 ends-->
</div>

<!-- row  ends -->

</section>

<!-- maincontent ends -->

</div>
<!-- Modal -->
  <div class="modal fade" id="managePowertabsModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="power_heading"> </h4>
        </div>
        <div class="modal-body">
          <div id="responseMessage"></div>
{if !$tabAccess.power}

<div id="vmTabContent20" class="tab_content2 manage_tab_content m_power">

    <div class="tab-pane active ">

        <p>{$language.vm_power_text}</p>

        <div id="powerButtonDiv">

            <a href="javascript:void(0);"
                onclick="power_button_action(this, '{if $vmData.runtime.powerState eq 'poweredOn'}{$language.vm_powered_off}{else}{$language.vm_powered_on}{/if}', '{$vmData.runtime.powerState}', '{$language.vm_power_off_alert_msg}');"
                class="reb_btn">

                {if $vmData.runtime.powerState eq 'poweredOn'}{$language.vm_powered_off}{else}{$language.vm_powered_on}{/if}

            </a>

        </div>

    </div>

</div>

{/if}

{if !$tabAccess.pause}

<div id="vmTabContent21" class="tab_content2 manage_tab_content m_power">

    <div class="tab-pane">

        <p>{$language.vm_pause_text}</p>

        <div id="pauseButtonDiv">

            <a href="javascript:void(0);"
                onclick="pause_button_action(this, '{if $vmData.runtime.powerState eq 'poweredOn'}{$language.vm_paused}{else}{$language.vm_unpaused}{/if}', '{$vmData.runtime.powerState}', '{$language.vm_pause_alert_msg}');"
                class="reb_btn">

                {if $vmData.runtime.powerState eq 'poweredOn'}{$language.vm_paused}{else}{$language.vm_unpaused}{/if}

            </a>

        </div>

    </div>

</div>

{/if}

{if !$tabAccess.softreboot}

<div id="vmTabContent22" class="tab_content2 manage_tab_content m_power">

    <div class="tab-pane">

        <p>{$language.vm_soft_reboot_text}</p>

        <div id="softrebootdiv">

            <a href="javascript:void(0);"
                onclick="soft_reboot_button_action(this, '{$language.vm_soft_reboot}', '{$language.vm_soft_reboot_alert_msg}');"
                class="reb_btn  {if $vmData.guest.toolsStatus eq 'toolsNotInstalled'}btn_disabled{/if}">

                {$language.vm_soft_reboot}

            </a>

        </div>

        {if $vmData.guest.toolsStatus eq 'toolsNotInstalled'}<div class="col-sm-12">
                <p style="color: #ff0000;">{$language.vm_soft_reboot_warning}</p>
        </div>{/if}

    </div>

</div>
{/if}

{if !$tabAccess.hardreboot}

<div id="vmTabContent23" class="tab_content2 manage_tab_content m_power">

    <div class="tab-pane">

        <p>{$language.vm_soft_reboot_text}</p>

        <div id="hardrebootdiv">

            <a href="javascript:void(0);"
                onclick="hard_reboot_button_action(this, '{$language.vm_hard_reboot}', '{$language.vm_soft_reboot_alert_msg}');"
                class="reb_btn">

                {$language.vm_hard_reboot}

            </a>

        </div>

    </div>

</div>

{/if}

{if $esxi neq 1}

{if !$tabAccess.reinstall}



<div id="vmTabContent24" class="tab_content2 manage_tab_content m_power">
    <div id="vmresponsediv"></div>
    <div class="tab-pane">

        <p>{$language.vm_reinstall_text}</p>

            <div id="" class="manage_tab_content m_power">

            <div class="tab-pane active ">

            <form id="reinstallVmWare" onsubmit="return false;">

                <input type="hidden" name="ajaxaction" value="reinstallvm">

                               
            <div class="row">
                <div class="col-sm-12">
                     <div class="form-group">
                       <label class="control-label">{$language.vm_guest_os}</label>
                      </div>
                       <div class="form-group" class="form-control">

                        <select class="form-control" name="os_name"
                            onchange="getOsversion('{$fromExisting}')">

                            <option value="Windows"
                                {if $vm_guest_os eq 'Windows'}selected="selected"
                                {/if}>Windows</option>

                            <option value="Linux"
                                {if $vm_guest_os eq 'Linux'}selected="selected"
                                {/if}>Linux</option>

                            <option value="Others"
                                {if $vm_guest_os eq 'Others'}selected="selected"
                                {/if}>Others</option>

                        </select>

                       {*  <input type="hidden" name="os_name" value="{$vm_guest_os}" class="form-control" readonly="readonly"> *}

                    </div>

                </div>

                
            </div>
             <div class="row">
                <div class="col-sm-12">
                     <div class="form-group">
                        <label class="control-label">{$language.vm_os_version}</label>
                     </div>
                     <div class="form-group" class="form-control">

                            <select class="form-control" name="os_version"
                                id="os_version">

                                {$vm_guest_os_option}

                            </select>

                        </div>

                </div>

                   
                </div>
                </form>    
            </div>

                        <div id="reinstalldiv">

                            <a href="javascript:void(0);"
                                onclick="reinstall_button_action(this, '{$language.vm_reinstall}', '{$language.vm_reinstall_alert_msg}', '{$system_url}', '{$serviceid}');"
                                class="reb_btn">

                                {$language.vm_reinstall}

                            </a>

                        </div>

                        <div class="col-sm-12 warning-msg">
                            <p style="color: #ff0000;">{$language.vm_reinstall_warning}</p>
                        </div>
                    </div>
                </div>
            </div>
        
    {/if}
{/if}
{* {if $vm_guest_os eq "Windows"} *}

<div id="vmchangepwresponse"></div>
<div id="vmTabContent25" class="tab_content2 manage_tab_content m_power">
    <div class="tab-pane">
        <p>{$language.vm_reset_password_text}</p>
        <div id="" class="manage_tab_content m_power">
            <div class="tab-pane active ">
                <form id="resetvmpwform" onsubmit="return false;" autocomplete="false">
                    <input type="hidden" name="ajaxaction" value="changevmpw">
                    <input type="hidden" name="ostype" value="{$vm_guest_os}">
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group" class="form-control">
                            <label
                                for="vmpw">{$language.vm_reset_password_label}</label>
                            <input type="password" name="vmpw" id="vmpw"
                                autocomplete="false" class="form-control">
                        </div>
                        <div class="form-group" class="form-control">
                            <label
                                for="vmcfvmpwpw">{$language.vm_reset_password_cnfrm_label}</label>
                            <input type="password" name="cfvmpw" id="cfvmpw"
                                autocomplete="false" class="form-control">
                        </div>
                    </div>
                </div>
                </form>
            </div>
            <div id="resetpwdiv">
                <a href="javascript:void(0);"
                    onclick="resetpw_button_action(this, '{$language.vm_reset_password_btn}', '{$language.vm_reset_password_pw_empty}', '{$language.vm_reset_password_cfpw_empty}', '{$language.vm_reset_password_donotmatch}', '{$language.vm_reset_password_preg}');"
                    class="reb_btn">
                    {$language.vm_reset_password_btn}
                </a>
            </div>
        </div>
    </div>
</div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<script src="modules/servers/vmware/js/scripts.js"></script>
