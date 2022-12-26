<link href="modules/servers/vmware/clientareaapp/css/style.css" rel="stylesheet">
<script src="modules/servers/vmware/clientareaapp/js/script.js"></script>

<div id="appresponse"></div>
<div class="table-responsive manage_tab_content ">
    <div class="tab_header"><h2>{$appoutput.lang.app_manageips}</h2></div>
    <table class="table datatable manageip_table">
        <thead>
            <tr>
                <th>{$appoutput.lang.app_ips}</th>
                <th>{$appoutput.lang.app_firewall}</th>
                <th>{$appoutput.lang.app_country}</th>
                <th>{$appoutput.lang.app_reverse}</th>
                <th>{$appoutput.lang.app_actions}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$appoutput.output.ipArr key=num item=ip}
                <tr id="ip_{$num}">
                    <td>{$ip.ip}</td>
                    <td id="firewall_{$num}"><script>getFirewall('{$num}', '{$app.app_name}', '{$ip.ip}');</script></td>
                    <td>{$appoutput.output.ovhIpDetail.country}</td>

                    <td id="reverse_{$num}" msg="{$appoutput.lang.app_delete_reverse}"><script>getReverse('{$num}', '{$app.app_name}', '{$ip.ip}');</script></td>
                    <td>
                        <div class="dropdown-cont">
                            <a data-target="#" class="dropdown-action" onclick="toggleMenuBar(this, '{$num}')">
                                <img src="modules/servers/vmware/clientareaapp/img/settingsicon.png">
                            </a>
                            <ul class="dropdown-action-menu" id="ipaction_{$num}">
                                <li id="addreverse_{$num}"><a href="javascript:void(0);" onclick="openIpModal(this, '{$ip.ip}', '{$num}', '{$app.app_name}');">{$appoutput.lang.app_edit_reverse}</a></li>
                                <li id="addfirewall_{$num}" style="display: none;"><a href="javascript:void(0);" onclick="createFireWall(this, '{$num}', '{$app.app_name}', '{$ip.ip}', '{$appoutput.lang.app_firewall_created_success}');">{$appoutput.lang.app_create_firewall}</a></li>
                                <li id="enablefirewall_{$num}" style="display: none;"><a href="javascript:void(0);" onclick="enableFireWall(this, '{$num}', '{$app.app_name}', '{$ip.ip}', '{$appoutput.lang.app_firewall_enabled_success}');">{$appoutput.lang.app_enable_firewall}</a></li>
                                <li id="disablefirewall_{$num}" style="display: none;"><a href="javascript:void(0);" onclick="disableFireWall(this, '{$num}', '{$app.app_name}', '{$ip.ip}', '{$appoutput.lang.app_firewall_disabled_success}');">{$appoutput.lang.app_disable_firewall}</a></li>
                                <li id="removefirewall_{$num}" style="display: none;"><a href="javascript:void(0);" onclick="removeFireWall(this, '{$num}', '{$app.app_name}', '{$ip.ip}', '{$appoutput.lang.app_firewall_removed_success}');">{$appoutput.lang.app_remove_firewall}</a></li>
                                <li id="configurefirewall_{$num}" style="display: none;"><a href="javascript:void(0);" onclick="configureFireWall(this, '{$num}', '{$app.app_name}', '{$ip.ip}', '{$appoutput.lang.app_firewall_disabled_success}');">{$appoutput.lang.app_configure_firewall_rule}</a></li>
                                <li id="getfirewallrule_{$num}" style="display: none;"><a href="javascript:void(0);" onclick="getFireWallRule(this, '{$num}', '{$app.app_name}', '{$ip.ip}');">{$appoutput.lang.app_firewall_rule_list}</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            {foreachelse}
                <tr><td colspan="100%" style="text-align: center !important;">Not Found!</td></tr>
            {/foreach}
        </tbody>
    </table>
</div>
<div id="manageipModal" class="modal fade" role="dialog">

    <span id="app_added_success" style="display:none">{$appoutput.lang.app_added_success}</span>
    <span id="app_reverse_req" style="display:none">{$appoutput.lang.app_reverse_req}</span>
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{$appoutput.lang.app_edit_reverse_heading}<span>Modal Header</span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="ipsts">
                            <div class="alert alert-warning alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</button>
                                {$appoutput.lang.app_edit_reverse_alert}  
                            </div>
                        </div>
                        <p></p>
                        <div class="manage_tab_content" id="ipmanagebody">
                            {*                            <div class="tab_row"><strong>IP</strong><span><input type="text" class="form-control" readonly="" disabled="" id="ip"></span></div>          *}
                            <div class="tab_row"><strong>{$appoutput.lang.app_ip_reverse}</strong><span><input type="text" class="form-control" readonly="" disabled="" id="ipreverse"></span></div>          
                            <div class="tab_row"><strong>{$appoutput.lang.app_reverse}</strong><span><input type="text" class="form-control" id="reverse"></span></div>          
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$appoutput.lang.app_close}</button>
                <button type="button" class="btn btn-success" onclick="addReverse();" id="addReverse">{$appoutput.lang.app_add_reverse}</button>
            </div>
        </div>

    </div>
</div>
<div id="configFirewallModal" class="modal fade" role="dialog">
    <span id="app_priority_req" style="display:none">{$appoutput.lang.app_priority_req}</span>
    <span id="app_action_req" style="display:none">{$appoutput.lang.app_action_req}</span>
    <span id="app_protocol_req" style="display:none">{$appoutput.app_protocol_req}</span>
    <span id="app_firewall_rule_fregment_error" style="display:none">{$appoutput.lang.app_firewall_rule_fregment_error}</span>
    <span id="app_firewall_rule_added_success" style="display:none">{$appoutput.lang.app_firewall_rule_added_success}</span>
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{$appoutput.lang.app_firewall_add_rule}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="configfirewallsts">
                        </div>
                        <p></p>
                        <div class="text-left" id="configfirewallbody">
                            <form class="form-horizontal" id="configureFirewallForm">
                                <input type="hidden" name="ip" value="" id="firewallip">
                                <div class="form-group">
                                    <label class="control-label required" for="#priority">{$appoutput.lang.app_priority}</label>
                                    <select class="form-control" id="priority" name="priority">
                                        {for $priority=0 to 19}
                                            <option value="{$priority}">{$priority}</option>
                                        {/for}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label required">{$appoutput.lang.app_action}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="firewallaction" id="deny" value="deny">
                                        <label class="form-check-label" for="deny">
                                            {$appoutput.lang.app_refuse}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="firewallaction" id="permit" value="permit">
                                        <label class="form-check-label" for="permit">
                                            {$appoutput.lang.app_to_allow}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label required" for="#protocol">{$appoutput.lang.app_protocol}</label>
                                    <select class="form-control" id="protocol" name="protocol" onchange="manageFields(this);">
                                        <option value="ah">AH</option>
                                        <option value="esp">ESP</option>
                                        <option value="gre">GRE</option>
                                        <option value="icmp">ICMP</option>
                                        <option value="ipv4">IPv4</option>
                                        <option value="tcp">TCP</option>
                                        <option value="udp">UDP</option>
                                    </select>
                                </div>
                                <div class="form-group required">
                                    <label class="control-label" for="sourceip">{$appoutput.lang.app_sourceip}</label>
                                    <input type="text" name="sourceip" id="sourceip" class="form-control">
                                </div>
                                <div class="form-group protocole" style="display: none;">
                                    <label class="control-label" for="sourceport">{$appoutput.lang.app_source_port}</label>
                                    <input type="text" name="sourceport" id="sourceport" class="form-control">
                                </div>
                                <div class="form-group protocole" style="display: none;">
                                    <label class="control-label" for="destinationport">{$appoutput.lang.app_destination_port}</label>
                                    <input type="text" name="destinationport" id="destinationport" class="form-control">
                                </div>
                                <div class="form-group flags" style="display: none;">
                                    <label class="control-label">{$appoutput.lang.app_tcp_options}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fragments" id="fragments">
                                        <label class="form-check-label" for="fragments">
                                            {$appoutput.lang.app_fragments}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group flags" style="display: none;">
                                    <label class="control-label">{$appoutput.lang.app_flags}</label>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tcpoption" id="NONE" value="NONE">
                                        <label class="form-check-label" for="NONE">
                                            {$appoutput.lang.app_no}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tcpoption" id="established" value="established">
                                        <label class="form-check-label" for="established">
                                            {$appoutput.lang.app_established}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tcpoption" id="syn" value="syn">
                                        <label class="form-check-label" for="syn">
                                            SYN
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$appoutput.lang.app_close}</button>
                <button type="button" class="btn btn-success" onclick="configureFirewallOnIp();" id="configfirwal">{$appoutput.lang.app_firewall_add_rule}</button>
            </div>
        </div>

    </div>
</div>

<div id="firewallRuleModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div id="firewallrulests"></div>
                        <div id="firewallrulebody"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{$appoutput.lang.app_close}</button
            </div>
        </div>

    </div>
</div>