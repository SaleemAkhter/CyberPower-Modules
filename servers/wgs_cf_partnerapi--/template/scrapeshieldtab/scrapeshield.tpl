{$headerhtml}
{$menu}
<div class="cfcontent">
    <h3 class="cfcontentmargin">{$wgs_lang.cf_scrape_shield_settings}</h3>
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
                {if $email_obfuscation.editable}
                    <form name="scrapeshielemaildsettingsform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="scrapeshield">
                        <input type="hidden" name="scrapeshieldaction" value="emailaddressobfuscation">
                        <span>{$wgs_lang.cf_scrape_shield_email_add_obf}</span>
                        <div>{$wgs_lang.cf_scrape_shield_email_add_obf_text}</div>
                        <div class="chk-scrape">
                            <input type="radio" name="emailaddressobfuscation" value="on" {if $email_obfuscation.value eq "on"} checked {/if}> {$wgs_lang.cf_scrape_shield_on}
                            <input type="radio" name="emailaddressobfuscation" value="off" {if $email_obfuscation.value eq "off"} checked {/if}> {$wgs_lang.cf_scrape_shield_off}
                        </div>
                        <div class="btn btn-primary" onclick="scrapeshielemaildsettingsform.submit();">{$wgs_lang.cf_scrape_shield_save_changes}</div>
                    </form>
                {/if}
            </td>
            <td>
                {if $server_side_exclude.editable}
                    <form name="scrapeshieldserversettingsform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="scrapeshield">
                        <input type="hidden" name="scrapeshieldaction" value="serversideexclude">
                        <span>{$wgs_lang.cf_scrape_shield_server_side_exc}</span>
                        <div>{$wgs_lang.cf_scrape_shield_server_side_exc_text}</div>
                        <div>
                            <input type="radio" name="serversideexclude" value="on" {if $server_side_exclude.value eq "on"} checked {/if}> {$wgs_lang.cf_scrape_shield_on}
                            <input type="radio" name="serversideexclude" value="off" {if $server_side_exclude.value eq "off"} checked {/if}> {$wgs_lang.cf_scrape_shield_off}
                        </div>
                        <div class="btn btn-primary" onclick="scrapeshieldserversettingsform.submit();">{$wgs_lang.cf_scrape_shield_save_changes}</div>
                    </form>
                {/if}
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>
                {if $hotlink_protection.editable}
                    <form name="scrapeshieldhotlinksettingsform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="scrapeshield">
                        <input type="hidden" name="scrapeshieldaction" value="hotlinkprotection">
                        <span>{$wgs_lang.cf_scrape_shield_hotlink_protect}</span>
                        <div>{$wgs_lang.cf_scrape_shield_hotlink_protect_text}</div>
                        <div>
                            <input type="radio" name="hotlinkprotection" value="on" {if $hotlink_protection.value eq "on"} checked {/if}> {$wgs_lang.cf_scrape_shield_on}
                            <input type="radio" name="hotlinkprotection" value="off" {if $hotlink_protection.value eq "off"} checked {/if}> {$wgs_lang.cf_scrape_shield_off}
                        </div>
                        <div class="btn btn-primary" onclick="scrapeshieldhotlinksettingsform.submit();">{$wgs_lang.cf_scrape_shield_save_changes}</div>
                    </form>
                {/if}
            </td>
        </tr>
    </table>
</div>