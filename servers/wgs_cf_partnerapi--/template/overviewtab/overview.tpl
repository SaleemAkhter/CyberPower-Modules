 
{$headerhtml}
{$menu}
{literal} 
    <style>
        .notextshadow{
            text-shadow: none !important;
        }
    </style>
{/literal}

{literal}
    <script>
        function updateZoneStatus(obj, stsEnableText, stsPauseText) {
            jQuery('#zoneAjaxStatus').html("");
            jQuery('.customloader').show();
            jQuery(obj).css('pointer-events', 'none');
            var data = jQuery('#pauseunpauseform').serialize();
            var zonestatus = jQuery('#zonestatus').val();
            jQuery.ajax({
                url: "clientarea.php?action=productdetails&id={/literal}{$serviceid}{literal}",
                type: 'POST',
                data: "customajax=true&" + data,
                success: function (response) {
                    jQuery('.customloader').hide();
                    jQuery(obj).css('pointer-events', 'auto');
                    var result = jQuery.parseJSON(response);
                    if (result.status == 'success') {
                        if (zonestatus == 'false') {
                            jQuery('#zonestatus').val('true');
                            jQuery('#pausesitedesc').hide();
                            jQuery(obj).text(stsPauseText);
                            jQuery(obj).removeClass('resume').addClass('paused');
                        }
                        else if (zonestatus == 'true') {
                            jQuery('#pausesitedesc').show();
                            jQuery('#zonestatus').val('false');
                            jQuery(obj).text(stsEnableText);
                            jQuery(obj).removeClass('paused').addClass('resume');
                        }
                        jQuery('#zoneAjaxStatus').html('<div class="cfa_success"><span></span>' + result.msg + '</div>');
                    } else {
                        jQuery('#zoneAjaxStatus').html('<div class="cfa_error"><span></span>' + result.msg + '</div>');
                    }
                }
            });
        }
    </script>
{/literal}
<div class="cfcontent">
    <h3 class="cfcontentmargin">{$wgs_lang.cf_overview_settings}</h3>
</div>
{if $security_level eq 'under_attack'}
    <div class="under_attack">
        <span><i class="fas fa-exclamation-triangle"></i></span>
        <h2>{$wgs_lang.cf_security_underattack}</h2>
        <p>{$wgs_lang.cf_security_underattack_desc}</p>
    </div>
{/if}
<div class="cfinternal">
    <div id="zoneAjaxStatus">
        {if $actionsucess}
            <div class="cfa_success">
                <span></span>
                {$actionsucess}
            </div>
        {/if}
        {if $error}
            <div class="cfa_error">
                <span></span>
                {$error}
            </div>
        {/if}
    </div>
    {if $developmentmodesetting.success && $developmentmodesetting.result.value eq "on"}
        <div class="cfyellowinfo"><strong>{$wgs_lang.cf_overview_development_mode_enable}</strong> {$wgs_lang.cf_overview_auto_exp}<br><strong>{$wgs_lang.cf_overview_remain_time}</strong> {$developmentmodesetting.result.time_remaining}</div>
            {/if}
    <div style="width: 50%; float: left;" class="cf-table">
        <table class="cfcontenttable">
            <tr>
                <th>{$wgs_lang.cf_overview_security_level}</th>
                <td>{$security_level|@ucfirst}</td>
            </tr>
            <tr>
                <th>{$wgs_lang.cf_overview_caching_level}</th>
                <td>{$cache_level|@ucfirst}</td>
            </tr>
            {*<tr>
            <th>DNS Records</th>
            <td>4 total</td>
            </tr>*}
            <tr>
                <th>{$wgs_lang.cf_overview_ssl}</th>
                <td>{$ssl|@ucfirst}</td>
            </tr>
            <tr>
                <th>{$wgs_lang.cf_overview_development_mode}</th>
                <td>{$development_mode|@ucfirst}</td>
            </tr>
            <tr>
                <th>{$wgs_lang.cf_plan}</th>
                <td>{$plan|@ucfirst}</td>
            </tr>
        </table>

        {*        {if $status eq 'Paused' OR $status eq 'Active'}*}
        <form id="pauseunpauseform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
            <input type="hidden" name="modop" value="custom">
            <input type="hidden" name="a" value="ManageCf">
            <input type="hidden" name="cf_action" value="manageWebsite">
            <input type="hidden" name="website" value="{$domain}">
            <input type="hidden" name="cfaction" value="overview">
            <input type="hidden" name="pause" id="zonestatus" value="{if $status eq 'Paused'}false{elseif $status eq 'Active'}true{/if}">
            {if $status eq 'Paused'}<p id="pausesitedesc">{$wgs_lang.cf_overview_pausesitedesc}</p>{/if}
            <div class="btn btn-primary {if $status eq 'Paused'}resume{elseif $status eq 'Active'}paused{/if}" onclick="updateZoneStatus(this, '{$wgs_lang.cf_overview_enablesite}', '{$wgs_lang.cf_overview_pausesite}');">{if $status eq 'Paused'}{$wgs_lang.cf_overview_enablesite}{else}{$wgs_lang.cf_overview_pausesite}{/if}</div>
            <img class='customloader' src='{$moduleURL}images/load.gif' alt='Loading...' style="display: none;">
        </form>
        {*        {/if}*}
    </div>

    <div style="width: 50%; float: left;">
        <div class="cfdarkorangeinfo info-1">
            <h3 class="notextshadow">{$wgs_lang.cf_overview_org_reg}</h3>
            {$originalregistrar}
        </div>
        <div class="cfdarkorangeinfo info-2">
            <h3 class="notextshadow">{$wgs_lang.cf_home_org_ns}</h3>
            {foreach from=$originalnameservers item=orignameserver}
                {$orignameserver}<br>
            {/foreach}
        </div>
        <div class="cfdarkyellowinfo info-3">
            <h3 class="notextshadow">{$wgs_lang.cf_home_cf_ns}</h3>
            {foreach from=$cloudflarenameservers item=cfnameserver}
                {$cfnameserver}<br>
            {/foreach}
        </div>
    </div>

</div>
{$cffooter}