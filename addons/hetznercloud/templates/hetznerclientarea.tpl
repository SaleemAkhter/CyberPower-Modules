{literal}
    <script type="text/javascript">
        var bandwidthUsageTotal = {/literal}{$server_outgoing_traffic + $server_ingoing_traffic}{literal};
        var bandwidthUsageTotal_in_TB = bandwidthUsageTotal / (1024 * 1024);
        var totalbandwidth = {/literal}{$included_traffic}{literal};
        var rootpath = "{/literal}{$WEB_ROOT}{literal}";
    var langVar = {/literal}
    {if $langVarJson}{$langVarJson}
    {else}""
    {/if}
    {literal};
    var FLPprice = {/literal}
    {if $FLPprice}{$FLPprice}
    {else}""
    {/if}
    {literal};
    var currencyID = {/literal}
    {if $clientsdetails['currency']}{$clientsdetails['currency']}
    {else}""
    {/if}
    {literal};
    </script>
{/literal}
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.2.5/js/dataTables.rowReorder.min.js">
</script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js">
</script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.1.3/circle-progress.min.js"></script>

<link rel="stylesheet" href="{$WEB_ROOT}/modules/servers/hetznercloud/templates/css/style.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.5/css/rowReorder.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">

<script type="text/javascript" src="{$WEB_ROOT}/modules/servers/hetznercloud/js/highcharts.js"></script>
<div id="overlaydiv" class="text-center">
    <!-- <img id="loading-image"  src="{$WEB_ROOT}/modules/servers/hetznercloud/templates/images/ajaxloader.gif" alt=""> -->
</div>
<div id="ajaxresult"></div>
<div class="server-satus-wrapper">
    <div class="server-satus-running">
        <div class="server-satus-title">
            <h3>{$server_name}</h3>
            <h5 style="text-transform: uppercase;" id="server_status">{$server_status}</h5>
        </div>
        <div class="server-satus-inner-box">
            <div class="server-satus-cantos text-center">
                <div><span id="imageloader"><i class="fa" style="font-size: 50px"></i></span></div>
                <img />
                <p>{$server_image_name}</p>
            </div>

            <div class="server-satus-space-right">
                <ul class="server-top-ul">
                    <li><object class="ser-icon1"
                            data="{$WEB_ROOT}/modules/servers/hetznercloud/templates/images/server/icon-1.svg"
                            type="image/svg+xml"></object><span><b>{$server_type}</b></span></li>
                    <li><object data="{$WEB_ROOT}/modules/servers/hetznercloud/templates/images/server/icon-2.svg"
                            type="image/svg+xml"></object><span>vCPU:<b>{$server_cores}</b></span></li>
                    <li><object data="{$WEB_ROOT}/modules/servers/hetznercloud/templates/images/server/icon-3.svg"
                            type="image/svg+xml"></object><span>RAM:<b>{$server_memory} GB</b></span></li>
                    <li><object data="{$WEB_ROOT}/modules/servers/hetznercloud/templates/images/server/icon-4.svg"
                            type="image/svg+xml"></object><span>{$_language.disklocal}<b>{$server_disk} GB </b> + <b
                                id="vol_size"></b><b>GB</b></span></li>
                </ul>
                <ul class="server-bottom-ul">
                    <li><i class="fa fa-cloud-upload"
                            aria-hidden="true"></i><span>{$server_outgoing_traffic}</span><sub>mbit</sub></li>
                    <li><i class="fa fa-cloud-download"
                            aria-hidden="true"></i><span>{$server_ingoing_traffic}</span><sub>mbit</sub></li>
                </ul>

            </div>
            <div class="button-box">
                <button id="reboot_btn" href="#" class="btn-style btn-color-1 action-btn"
                    style="display: {if $server_status eq 'off'}none{else}inline-block{/if}" data-toggle="modal"
                    data-target="#rebootModal"><i class="fa fa-power-off"
                        aria-hidden="true"></i>{$_language.reboot}</button>
                <button id="shutdown_btn" href="#" class="btn-style btn-color-2 action-btn"
                    style="display: {if $server_status eq 'off'}none{else}inline-block{/if}" data-toggle="modal"
                    data-target="#shutdownModal"><i class="fa fa-power-off"
                        aria-hidden="true"></i>{$_language.shutdown}</button>
                <button id="reset_btn" href="#" class="btn-style btn-color-3 action-btn"
                    style="display: {if $server_status eq 'off'}none{else}inline-block{/if}" data-toggle="modal"
                    data-target="#resetModal"><i class="fa fa-plug" aria-hidden="true"></i>{$_language.reset}</button>
                <button id="pass_reset_btn" href="#" class="btn-style btn-color-4 action-btn"
                    style="display: {if $server_status eq 'off'}none{else}inline-block{/if}" data-toggle="modal"
                    data-target="#pass_resetModal"><i class="fa fa-key"
                        aria-hidden="true"></i>{$_language.resetRootPasswd}</button>
                <button id="poweron_btn" class="btn-style btn-color-3 action-btn"
                    {if $server_status eq 'running'}disabled{/if}
                    style="display: {if $server_status eq 'running' || $server_status neq 'off'}none{else}inline-block{/if}"
                    onclick="apicall('poweron');"><i class="fa fa-play" style="font-size:15px"></i>
                    {$_language.start}</button>
                <button id="console_btn" class="btn-style btn-color-4 action-btn"
                    onclick="window.open('/modules/servers/hetznercloud/console.php?query={$serviceid}', '{$server_name}', 'width=800,height=800')"><i
                        class="fa fa-desktop" style="font-size:15px"></i> {$_language.console}</button>
            </div>
        </div>
    </div>
    <div class="bandwidth-snapshop-wrapper">
        <div class="band-left">
            {if $bandUsageSection neq 1}
                <div class="wandwidth-container">
                    <div class="wandwidth-title">
                        <h3>{$_language.bandwidthUsage}</h3>
                    </div>
                    <div class="wandwidth-container-inner">
                        <div class="circle" id="circle-a">
                            <strong></strong>
                        </div>
                        <h4><img src="{$WEB_ROOT}/modules/servers/hetznercloud/templates/images/server/mazerment.png"
                                alt="mazerment" /><span id="">{$usageSize}</span>/ {$included_traffic} TB</h4>
                        <p>Last Updated {$lastupdate_date}</p>
                    </div>
            </div>{/if}
            <div class="snapshot-container-right">
                <div class="wandwidth-title">
                    <h3>{$_language.snapshots}</h3>
                    <button href="#" class="snapshot-btn" {if $snapshotAddon_selected eq 'no'}disabled
                        title="{$_language.addonNotInpackage}" {else} 
                        {/if} data-toggle="modal"
                        data-target="#serverSnapshot">{$_language.takesnapshot}</button>
                </div>
                <div class="snapshot-container-inner">
                    {if $snapshotAddon_selected eq 'no'}<div>
                            <p>{$_language.snapshotNotSubscribeDesc}</p>
                    </div>{else}<div>
                            <p>{$_language.snapshotSubscribeDesc}</p>
                    </div>{/if}
                    <img src="{$WEB_ROOT}/modules/servers/hetznercloud/templates/images/server/space-img.png"
                        alt="server icon" />
                    <!-- <h4>Snapshots cost</h4>
                        <h3>€0.012/GB</h3>
                        <p>(incl. 20 % VAT).</p> -->
                </div>
            </div>
        </div>
        <div class="band-right">
            <div class="server-details-container">
                <div class="wandwidth-title">
                    <h3>{$_language.serverNetDetail}</h3>
                </div>
                <div class="server-details-inner">
                    <ul>
                        <li class="server-name"><i class="fa fa-check"
                                aria-hidden="true"></i><span>{$_language.servername}</span></li>
                        <li class="server-version">{$server_name}</li>
                    </ul>
                    <ul>
                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span> IPv4:</span></li>
                        <li class="server-version">{$server_ipv4}</li>
                    </ul>
                    <ul>
                        <li class="server-name"><i class="fa fa-check" aria-hidden="true"></i><span> IPv6:</span></li>
                        <li class="server-version">{$server_ipv6}</li>
                    </ul>
                    <ul>
                        <li class="server-name"><i class="fa fa-check"
                                aria-hidden="true"></i><span>{$_language.operatingSystem}</span></li>
                        <li class="server-version">{$server_image_name}</li>
                    </ul>
                    <ul>
                        <li class="server-name"><i class="fa fa-check"
                                aria-hidden="true"></i><span>{$_language.datacenter}</span></li>
                        <li class="server-version"> {$server_datacenter}</li>
                    </ul>
                    <ul>
                        <li class="server-name"><i class="fa fa-check"
                                aria-hidden="true"></i><span>{$_language.city}</span></li>
                        <li class="server-version"> {$server_location_city}</li>
                    </ul>
                    <ul class="m-b-0">
                        <li class="server-name"><i class="fa fa-check"
                                aria-hidden="true"></i><span>{$_language.country}</span></li>
                        <li class="server-version">
                            {if $server_location_country eq "DE"}Germany{else}$server_location_country{/if}</li>
                    </ul>
                </div>
            </div>
            {if $rescueSection neq 1}

                <div class="rescue-container-right">
                    <div class="wandwidth-title">
                        <h3>{$_language.rescue}</h3>
                    </div>
                    <div class="rescue-container-inner rescue-bg">
                        <p>{$_language.rescueContainerDesc} .</p>
                        <button id="enable_rescue" class="enable-btn" data-toggle="modal" data-target="#rescueModal"
                            style="display: {if $rescue_enabled eq 1 }none{else}inline-block{/if}">{$_language.enableRescue}</button>
                        <button id="disable_rescue" class="btn btn-danger float-left" data-toggle="modal"
                            data-target="#disablerescueModal"
                            style="display: {if $rescue_enabled neq 1}none{else}inline-block{/if}">{$_language.disableRescue}</button>
                    </div>
                </div>
            {/if}
        </div>

    </div>
    <div class="bk-snp-wrapper">
        <div class="server-satus-title">
            <h3>{$_language.backupandSnapshot}</h3>
            <ul class="right-btn-bk">
                <li><a id="backupbtn">{$_language.backupbtnText}<i id="backup_tableloader" class="fa"></i></a></li>
                <li><a id="snapshotbtn" class="bk-sn-active">{$_language.snapshotbtnText}<i id="snapshot_tableloader"
                            class="fa"></i></a></li>
            </ul>
        </div>
        <div class="snapshot-table server-satus-title">
            <div id="backup_tab" style="display: none">
                <table id="backup_table" class="table table-hover " style="width:100%">
                    <thead>
                        <tr>
                            <th>{$_language.snapAndBackupDescription}</th>
                            <th>{$_language.snapAndBackupCreated}</th>
                            <th>{$_language.snapAndBackupDiskSize}</th>
                            <th>{$_language.snapAndBackupStatus}</th>
                            <th>{$_language.tableAction}</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>

                </table>
            </div>
            <div id="snapshot_tab">
                <table id="snapshot_table" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>{$_language.snapAndBackupDescription}</th>
                            <th>{$_language.snapAndBackupCreated}</th>
                            <th>{$_language.snapAndBackupDiskSize}</th>
                            <th>{$_language.snapAndBackupStatus}</th>
                            <th>{$_language.tableAction}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <div class="bk-snp-wrapper">
        <div class="server-satus-title">
            <div id="floatingIP_table_section">
                <div>
                    <h3 class="float-left">{$_language.floatingIP}</h3><br>
                    {if $number_of_floatingIP > 0}
                        <div class="py-3">
                            <!--  <div id="flpstatus"class="float-left"> <b>    {$_language.totalFloatingIP}</b> {$number_of_floatingIP} &nbsp <b>{$_language.usedFloatingIP}</b> {$number_of_floatingIP_used}
                    </div> -->
                            <button href="#" class="btn btn-color-2 float-right text-light" style="display: inline-block"
                                data-toggle="modal" data-target="#addfloatingIPModal"><i class="fa fa-plus "
                                    aria-hidden="true"></i>{$_language.addfloatingIP}</button>
                        </div>
                    {/if}
                </div>
                <div class="text-center"><i id="FloatingIP_tableloader" class="fa" style="font-size: 50px"></i></div>
                {if $number_of_floatingIP > 0}
                    <div>
                        <table id="floating_ipTable" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>IP</th>
                                    <th>{$_language.snapAndBackupDescription}</th>
                                    <th>{$_language.reverseDNS}</th>
                                    <th>{$_language.homeLocation}</th>
                                    <th>{$_language.tableAction}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                {else}
                    <div>
                        <p>{$_language.noFloatingIPBuyed}</p>
                    </div>
                {/if}
            </div>
        </div>
    </div>
    {if $rebuildSection neq 1}

        <div class="server-details-wrapper">

            <div class="rescue-container-right">
                <div class="wandwidth-title">
                    <h3>{$_language.rebuild}</h3>
                </div>
                <div class="rescue-container-inner">
                    <p>{$_language.rebuildContainerText}</p>
                    <div><span id="rebuildloader"><i class="fa" style="font-size: 50px"></i></span></div>
                    <form action="" id="rebuild_form">
                        <select class="oprator-select" id="os_images" name="os_image_selected">
                            <option value="0">{$_language.noneSelected}</option>
                        </select>
                        <input type="hidden" name="customaction" value="rebuild">
                        <input id="submitBtn" type="button" name="submit" value="REBUILD" class="rebuild-btn"
                            data-toggle="modal" data-target="#rebuildWarningModal" disabled>
                    </form>
                </div>
            </div>
        </div>
    {/if}
    <div class="server-details-wrapper">

        <div class="rescue-container-right">
            <div class="wandwidth-title">
                <h3>{$_language.isoimages}</h3>
                <ul class="right-btn-bk">
                    <li><a id="unmount" data-toggle="modal" data-target="#unmountModal"
                            class="bk-sn-active {if !$iso}disabled{/if}">{$_language.unmountbtn}</a>
                    </li>
                </ul>
            </div>
            <div class="rescue-container-inner">
                <p>{$_language.isoContainerText}</p>
                <div><span id="isoloader"><i class="fa" style="font-size: 50px"></i></span></div>
                <form action="" id="mount_form">
                    <select class="oprator-select" id="iso" name="iso">
                        <option value="0">{$_language.noneSelected}</option>
                    </select>
                    <input type="hidden" name="customaction" value="mount">
                    <input id="submitBtn" type="button" name="submit" value="MOUNT" class="mount-btn"
                        onclick="mountIso(this);" disabled>
                    <div id="mountmsg"></div>
                </form>
            </div>
        </div>
    </div>
    {if $graphSection neq 1}
        <div class="graphs-wrapper">
            <div class="server-satus-title">
                <h3>{$_language.graphtext}</h3>
                <select class="graph-select">
                    <option value="live">{$_language.graphlivetext}</option>
                    <option value="12hr">{$_language.graph12hrtext}</option>
                    <option value="24hr">{$_language.graph24hrtext}</option>
                    <option value="week">{$_language.graph1weektext}</option>
                    <option value="month">{$_language.graph30daystext}</option>
                </select>
            </div>
            <div class="graphs-inner-box text-center">
                <span id="graphloader"><i class="fa" style="font-size: 50px"></i></span>
                <div id="CPU" class="grph_section active-graph">
                </div>
                <div id="Throughput" class="grph_section">
                </div>
                <div id="IOPS" class="grph_section">
                </div>
                <div id="Traffic" class="grph_section">
                </div>
                <div id="PPS" class="grph_section">
                </div>
            </div>

        </div>
    {/if}

</div>
<!--------- reboot modal -->
<div class="modal fade custom_modal" id="rebootModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.rebootModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.rebootModaltext}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    onclick="apicall('reboot');">{$_language.reboot}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>

    </div>
</div>
<!------   Unmount modal -->
<div class="modal fade custom_modal" id="unmountModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.unmountModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.unmountModaltext}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    onclick="unMountIso(this);">{$_language.unmountbtn}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>
    </div>
</div>
<!------   shutdown modal -->
<div class="modal fade custom_modal" id="shutdownModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.shutdownModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.shutdownModaltext}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    onclick="apicall('shutdown');">SHUTDOWN</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>
    </div>
</div>
<!-- ------RESET MODAL   -->
<div class="modal fade custom_modal" id="resetModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.resetModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.resetModaltext}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    onclick="apicall('reset');">RESET</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>
    </div>
</div>
<!-- ------PASSWORD RESET MODAL   -->
<div class="modal fade custom_modal" id="pass_resetModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.resetpassModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.resetpassModaltext}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    onclick="apicall('reset_password');">{$_language.resetpassModalHeading}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade custom_modal" id="rootPasswordRest" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.resetpassSuccessModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.resetpassSuccessModaltext}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" data-dismiss="modal" aria-hidden="true">{$_language.closeText}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade custom_modal" id="serverSnapshot" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.serverSnapshotModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.serverSnapshotModalText}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    onclick="apicall('create_image');">{$_language.createSnapshot}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade custom_modal" id="rescueModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">ENABLE RESCUE</h4>
            </div>
            <div class="modal-body text-center">
                <p>Please choose a rescue system and optionally add an ssh key.</p>
                <form action="">
                    <div class="form-group">
                        <select class="form-control" name="rescue_os" id="rescue_os">
                            <option value="linux64">linux64</option>
                            <option value="linux32">linux32</option>
                            <option value="freebsd64">freebsd64</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" name="ssh-key" placeholder="SSH KEY"
                            style=" margin-bottom: 15px;">
                        <button type="submit" class="btn btn-danger pt-1" data-dismiss="modal"
                            onclick="apicall('enable_rescue');">{$_language.enableRescue}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade custom_modal" id="disablerescueModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.disableRescue}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.disableRescueModaltext}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    onclick="apicall('disable_rescue');">{$_language.disableRescue}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>
    </div>
</div>
<!-- Rebuild warning modal -->
<div class="modal fade custom_modal" id="rebuildWarningModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.rebuildModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.rebuildModalText}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    id="submit">{$_language.rebuild}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>
    </div>
</div>

<!-- Backup table Rebuild warning modal -->

<div class="modal fade custom_modal" id="backuptable_rebuildWarningModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.rebuildModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.rebuildModalText}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    id="backuptable_submit">{$_language.rebuild}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>
    </div>
</div>
<!-- convert backup to snapshot -->
<div class="modal fade custom_modal" id="backuptable_backupToSnapshot" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.convertTosnapModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.convertTosnapModaltext}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    id="backupToSnapshot_submit">{$_language.convertTosnapModalHeading}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>
    </div>
</div>
<!-- delete backup or snapshot -->
<div class="modal fade custom_modal" id="delete_backupOrSnapshot" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.deleteBkpSnapModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.deleteBkpSnapModaltext}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    id="delete_backupOrSnapshot_submit">{$_language.deleteText}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>
    </div>
</div>
<!-- enable protection for snapshot -->
<div class="modal fade custom_modal" id="protection_Snapshot" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.enableProModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.enableProModalText}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    id="protection_Snapshot_submit">{$_language.enableProModalHeading}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>
    </div>
</div>

<!-- Floating IPs instruction Modal -->
<div class="modal fade custom_modal" id="floating_ip_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.fIpInstrModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.fIpInstrModalbodyText1}</p>
                <h3 class="mt-2 mb-2">{$_language.fIpInstrModalbodyText2}</h3>
                <div class="bg-dark text-light">{$_language.fIpInstrModalbodyText3}</div>
                <div>{$_language.fIpInstrModalbodyText4}</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                    data-dismiss="modal">{$_language.fIpInstrModalfooter}</button>
            </div>
        </div>
    </div>
</div>
<!-- Floating IPs protection Modal -->

<div class="modal fade custom_modal" id="protection_floatingIP" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.enableProModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.fIpProtectModalbodyText1}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    id="protection_floatingIP_submit">{$_language.enableProModalHeading}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>
    </div>
</div>

<!-- Floating IPs Reverse DNS edit Modal -->

<div class="modal fade custom_modal" id="edit_reverseDNS" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.fIpRevDnsModalHeading}</h4>
            </div>
            <div class="modal-body text-center">
                <p>{$_language.fIpRevDnsModalbody}</p>
            </div>
            <div class="form-group mr-3 ml-3">
                <input id="ip_no" class="text-center form-control w-80">
                <input type="text" name="dns_ptr" class="form-control w-80" required="true">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    id="edit_reverseDNS_submit">{$_language.fIpRevDnsModalHeading}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>
    </div>
</div>
<!-- -----Add Floating IP modal section ------>

<div class="modal fade custom_modal" id="addfloatingIPModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.addfloatingIP}</h4>
            </div>
            <div class="modal-body text-center">
                <p></p>
            </div>
            <div class="form-group mr-3 ml-3">
                <form action="" id="addFloatingIP_form">
                    <label>{$_language.numbOfFLP}</label>
                    <select class="form-control" id="noOFfloatIP" name="no_OF_floatIP">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select><br>
                    <label><span>{$_language.priceperFLP}: <span class="currCode"></span>{$FLPprice}<span
                                class="currCodeSuffix"></span></span></label><br>
                    <label><span>{$_language.total} : <span class="currCode"></span><b
                                id="totalpriceFLP"></b></span><span class="currCodeSuffix"></span></label> <br>
                    <label>{$_language.typeOfFloatIP}</label>

                    <select class="form-control" id="floatIP_type" name="floating_ips_type">
                        <option value="ipv4">ipv4</option>
                        <option value="ipv6">ipv6</option>
                    </select>
                    <input type="hidden" name="customaction" value="addFloatingIP">
                    <input type="hidden" name="currID" value="{$clientsdetails['currency']}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"
                    id="addFloatingIP_submit">{$_language.addfloatingIP}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.cancelText}</button>
            </div>
        </div>
    </div>
</div>
<!-- -----Add Floating IP Error modal section ------>

<div class="modal fade custom_modal" id="addFloatingIPResponseModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">{$_language.addfloatingIP}&nbsp{$_language.error}</h4>
            </div>
            <div class="modal-body text-center">
                <p id="flpAddResponse"></p>
            </div>
            <div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.closeText}</button>
            </div>
        </div>
    </div>
</div>

<!-- -----Assing /Unassign Floating IP modal section ------>

<div class="modal fade custom_modal" id="flpAssignUnassignModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center" id="flpAssignUnassign"></h4>
            </div>
            <div class="modal-body text-center">
                <p id="flpAssignUnassignResponse"></p>
            </div>
            <div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.closeText}</button>
            </div>
        </div>
    </div>
</div>

<!-- -----add Floating IP response modal section ------>

<div class="modal fade custom_modal" id="addFloating_IPSuccess" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center" id="addFloating_IPSuccessHead">
                    {$_language.orderSuccesPlaceModalHead}</h4>
            </div>
            <div class="modal-body text-center">
                <p id="addFloating_IPSuccessResponse">{$_language.orderSuccesPlaceModalBody}</p>
            </div>
            <div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$_language.closeText}</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{$WEB_ROOT}/modules/servers/hetznercloud/js/hetzner_script.js"></script>