{$headerhtml}
{$menu}
{literal}
    <style>
        .modeloader{
            display: none;
        }
        .changeipmode{
            float: left;
            width: 110px;
            margin-right: 10px;
        }
    </style>

    <script type="text/javascript">
        /* Add IP Validations */
        function validate(v) {
            if (v === 0) {
                $("#form-addip").find("input[name=ip]").css('border', '1px solid #ff0000');
                return false;
            }
        }
        function novalidate() {
            $("#form-addip").find("input[name=ip]").css('border', '1px solid #cccccc');
        }

        $(document).ready(function () {
            var bootstrap_enabled = (typeof $().modal == 'function');
            if (bootstrap_enabled === false) {
                $.getScript("{/literal}{$systemURL}{literal}/assets/js/bootstrap.min.js", function () {
                    console.log("bootstrap loaded");
                });
            }
            /* Delete IP */
            $('#deleteipmodal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var firewallid = button.data('whatever');
                var modal = $(this);
                modal.find('#firewallipid').val(firewallid);
            });
            /* Update Firewall IP Notes */
            $('#ipnotesmodal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var firewallid = button.data('whatever');
                var notes = button.data('notes');
                var modal = $(this);
                modal.find("#firewallipnotesid").val(firewallid);
                modal.find("#firewallipnotes").text(notes);
            });
            /* change ip mode */
            $(".changeipmode").change(function () {
                $(this).parent().find(".modeload").removeClass('modeloader');
                $(this).parent().submit();
            });
        });</script>
    {/literal}
<div class="cfcontent">
    <h3 class="cfcontentmargin">{$wgs_lang.firewall_settings}</h3>
</div>
<div class="cfinternal">
    {if $error}
        <div class="cfa_error">
            <span></span>
            {$error}
        </div>
    {/if}
    {if $actionsucess}
        <div class="cfa_success">
            <span></span>
            {$actionsucess}
        </div>
    {/if}

    <table class="cfcontenttabletype2">
        <tr>
            <td>
                {if $security_level.editable}
                    <form name="securitylevelsettingsform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="firewall">
                        <input type="hidden" name="firewallaction" value="securitylevel">
                        <span>{$wgs_lang.cf_firewall_security_level}</span>
                        <div>{$wgs_lang.cf_firewall_security_level_challenge}</div>
                        <div>
                            <select name="securitylevel" class="input-sm">
                                {foreach from=$securitylevelvalues key=levelvalue item=level}
                                    <option value="{$levelvalue}" {if $security_level.value eq $levelvalue} selected {/if}>{$level}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="btn btn-primary" onclick="securitylevelsettingsform.submit();">{$wgs_lang.cf_firewall_save_changes}</div>
                    </form>
                {/if}
            </td>
            <td>
                {if $challenge_ttl.editable}
                    <form name="challengettlsettingsform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="firewall">
                        <input type="hidden" name="firewallaction" value="challengettl">
                        <span>{$wgs_lang.cf_firewall_challenge_time}</span>
                        <div>{$wgs_lang.cf_firewall_completing_challenge_time}</div>
                        <div>
                            <select name="challengettl" class="input-sm">
                                {foreach from=$challengettlvalues key=ttlvalue item=ttl}
                                    <option value="{$ttlvalue}" {if $challenge_ttl.value eq $ttlvalue} selected {/if}>{$ttl}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="btn btn-primary" onclick="challengettlsettingsform.submit();">{$wgs_lang.cf_firewall_save_changes}</div>
                    </form>
                {/if}
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>
                {if $browser_check.editable}
                    <form name="browsercheckform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="firewall">
                        <input type="hidden" name="firewallaction" value="browsercheck">
                        <span>{$wgs_lang.cf_firewall_browser_check}</span>
                        <div>{$wgs_lang.cf_firewall_browser_check_text}</div>
                        <div>
                            <select name="browsercheck" class="input-sm">
                                <option value="on" {if $browser_check.value eq "on"} selected {/if}>{$wgs_lang.cf_firewall_on}</option>
                                <option value="off" {if $browser_check.value eq "off"} selected {/if}>{$wgs_lang.cf_firewall_off}</option>
                            </select>
                        </div>
                        <div class="btn btn-primary" onclick="browsercheckform.submit();">{$wgs_lang.cf_firewall_save_changes}</div>
                    </form>
                {/if}
            </td>
            <td>
                {if $waf.editable}
                    <form name="wafform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="firewall">
                        <input type="hidden" name="firewallaction" value="waf">
                        <span>{$wgs_lang.cf_firewall_web_app_firewall}</span>
                        <div>{$wgs_lang.cf_firewall_web_app_firewall_text}</div>
                        <div>
                            <select name="waf" class="input-sm">
                                <option value="on" {if $waf.value eq "on"} selected {/if}>{$wgs_lang.cf_firewall_on}</option>
                                <option value="off" {if $waf.value eq "off"} selected {/if}>{$wgs_lang.cf_firewall_off}</option>
                            </select>
                        </div>
                        <div class="btn btn-primary" onclick="wafform.submit();">{$wgs_lang.cf_firewall_save_changes}</div>
                    </form>
                {/if}
            </td>
        </tr>
        {if $advanced_ddos.editable}
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>
                    {if $advanced_ddos.editable}
                        <form name="advancedddosform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                            <input type="hidden" name="modop" value="custom">
                            <input type="hidden" name="a" value="ManageCf">
                            <input type="hidden" name="cf_action" value="manageWebsite">
                            <input type="hidden" name="website" value="{$domain}">
                            <input type="hidden" name="cfaction" value="firewall">
                            <input type="hidden" name="firewallaction" value="advanced_ddos">
                            <span>{$wgs_lang.cf_firewall_advance_ddos}</span>
                            <div>{$wgs_lang.cf_firewall_advance_ddos_text}</div>
                            <div>
                                <select name="advanced_ddos" class="input-sm">
                                    <option value="on" {if $advanced_ddos.value eq "on"} selected {/if}>{$wgs_lang.cf_firewall_on}</option>
                                    <option value="off" {if $advanced_ddos.value eq "off"} selected {/if}>{$wgs_lang.cf_firewall_off}</option>
                                </select>
                            </div>
                            <div class="btn btn-primary" onclick="advancedddosform.submit();">{$wgs_lang.cf_firewall_save_changes}</div>
                        </form>
                    {/if}
                </td>
                <td></td>
            </tr>
        {/if}
    </table>
    <div class="cfcontent">
        <h2 class="cfcontentmargin">{$wgs_lang.cf_firewall_ip_firewall}</h2>
    </div>
    <div class="cfshowdnsrecord">
        <input type="button" class="btn btn-primary" data-toggle="modal" data-target="#addnewipmodal" value="{$wgs_lang.cf_firewall_ip_firewall_add_new_ip_add}">
    </div>
    <table class="cfcontenttable">
        <tr>
            <th>{$wgs_lang.cf_firewall_ip_firewall_type}</th>
            <th>{$wgs_lang.cf_firewall_ip_firewall_value}</th>
            <th>{$wgs_lang.cf_firewall_ip_firewall_applies_to}</th>
            <th>{$wgs_lang.cf_firewall_ip_firewall_mode}</th>
            <th>{$wgs_lang.cf_firewall_ip_firewall_notes}</th>
            <th></th>
        </tr>

        {foreach from=$firewallipslist.result item=ip}
            <tr>
                <td>{$ip.configuration.target|@strtoupper}</td>
                <td>{$ip.configuration.value}</td>
                <td>{$ip.group.name}</td>
                <td>
                    <form method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="firewall">
                        <input type="hidden" name="firewallaction" value="firewallipmode">
                        <input type="hidden" name="modeid" value="{$ip.id}">
                        <select name="mode" class="changeipmode form-control input-sm">
                            {foreach from=$ip.allowed_modes item=allowedmode}
                                <option value="{$allowedmode}" {if $allowedmode eq $ip.mode} selected {/if}>{$allowedmode}</option>
                            {/foreach}
                        </select> <img class="modeloader modeload" src="{$moduleURL}images/load.gif" alt="Loading...">
                    </form>
                </td>
                <td style="padding-left: 20px;"><span class="notes" data-toggle="modal" data-target="#ipnotesmodal" data-whatever="{$ip.id}" data-notes="{$ip.notes}"></span></td>
                <td><span data-toggle="modal" data-target="#deleteipmodal" data-whatever="{$ip.id}" class="delete"></span></td>
            </tr>
        {foreachelse}
            <tr><td colspan="100%">{$wgs_lang.cf_firewall_ip_firewall_no_records}</td></tr>
            {/foreach}
    </table>
</div>

<div class="modal fade" id="addnewipmodal" tabindex="-1" role="dialog" aria-labelledby="addnewIPModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" id="form-addip" action="clientarea.php?action=productdetails&id={$smarty.get.id}"> 
                <input type="hidden" name="modop" value="custom">
                <input type="hidden" name="a" value="ManageCf">
                <input type="hidden" name="cf_action" value="manageWebsite">
                <input type="hidden" name="website" value="{$domain}">
                <input type="hidden" name="cfaction" value="firewall">
                <input type="hidden" name="firewallaction" value="addfirewallip">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="addnewIPModalLabel">{$wgs_lang.cf_firewall_ip_firewall_add_ip}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ip-address" class="control-label">{$wgs_lang.cf_firewall_ip_firewall_ip}</label>
                        <input type="text" name="ip" class="form-control" id="ip-address" required>
                    </div>

                    <div class="form-group">
                        <label for="ip-address-mode" class="control-label">{$wgs_lang.cf_firewall_ip_firewall_mode}</label>
                        <select name="mode" class="form-control input-sm" id="ip-address-mode">
                            <option value="block">{$wgs_lang.cf_firewall_ip_firewall_block}</option>
                            <option value="challenge">{$wgs_lang.cf_firewall_ip_firewall_challenge}</option>
                            <option value="whitelist">{$wgs_lang.cf_firewall_ip_firewall_whitelist}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="notes-text" class="control-label">{$wgs_lang.cf_firewall_ip_firewall_notes}</label>
                        <textarea name="notes" class="form-control" id="notes-text"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="novalidate()">{$wgs_lang.cf_firewall_cancel}</button>
                    <button type="submit" class="btn btn-primary" onclick="return validate(ip.value.length)">{$wgs_lang.cf_firewall_continue}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="ipnotesmodal" tabindex="-1" role="dialog" aria-labelledby="IPnotesModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}"> 
                <input type="hidden" name="modop" value="custom">
                <input type="hidden" name="a" value="ManageCf">
                <input type="hidden" name="cf_action" value="manageWebsite">
                <input type="hidden" name="website" value="{$domain}">
                <input type="hidden" name="cfaction" value="firewall">
                <input type="hidden" name="firewallaction" value="firewallipnotes">
                <input type="hidden" name="firewallipnotesid" id="firewallipnotesid">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="IPnotesModalLabel">{$wgs_lang.cf_firewall_ip_firewall_ip_firewall_notes}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="firewallipnotes" class="control-label">{$wgs_lang.cf_firewall_ip_firewall_notes}</label>
                        <textarea name="firewallipnotes" id="firewallipnotes" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{$wgs_lang.cf_firewall_cancel}</button>
                    <button type="submit" class="btn btn-primary">{$wgs_lang.cf_firewall_continue}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteipmodal" tabindex="-1" role="dialog" aria-labelledby="deleteIPModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}"> 
                <input type="hidden" name="modop" value="custom">
                <input type="hidden" name="a" value="ManageCf">
                <input type="hidden" name="cf_action" value="manageWebsite">
                <input type="hidden" name="website" value="{$domain}">
                <input type="hidden" name="cfaction" value="firewall">
                <input type="hidden" name="firewallaction" value="firewallipdelete">
                <input type="hidden" name="firewallipid" id="firewallipid">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="deleteIPModalLabel">{$wgs_lang.cf_firewall_ip_firewall_delete_confirm}</h4>
                </div>
                <div class="modal-body">
                    {$wgs_lang.cf_firewall_ip_firewall_delete_confirm_text}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{$wgs_lang.cf_firewall_cancel}</button>
                    <button type="submit" class="btn btn-primary">{$wgs_lang.cf_firewall_continue}</button>
                </div>
            </form>
        </div>
    </div>
</div>
{$cffooter}