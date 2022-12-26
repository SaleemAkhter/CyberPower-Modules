{$headerhtml}
{$menu}
<div class="cfcontent">
    <h3 class="cfcontentmargin">{$wgs_lang.cf_caching_settings}</h3>
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
                {if $cache_level.editable}
                    <form name="cachinglevelsettingsform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="caching">
                        <input type="hidden" name="cachingaction" value="cachelevel">
                        <span class="emboss">{$wgs_lang.cf_caching_level}</span>
                        <div>{$wgs_lang.cf_caching_level_text}</div>
                        <div class="cache-chk"><input type="radio" name="cachelevel" value="basic" {if $cache_level.value eq "basic"} checked {/if}><span class="customRadio"></span> {$wgs_lang.cf_caching_basic}</div>
                        <div class="cache-chk-sm"><input type="radio" name="cachelevel" value="simplified" {if $cache_level.value eq "simplified"} checked {/if}><span class="customRadio"></span> {$wgs_lang.cf_caching_simple}</div>
                        <div class="cache-chk"><input type="radio" name="cachelevel" value="aggressive" {if $cache_level.value eq "aggressive"} checked {/if}><span class="customRadio"></span> {$wgs_lang.cf_caching_aggressive}</div>
                        <div class="btn btn-primary" onclick="cachinglevelsettingsform.submit();">{$wgs_lang.cf_caching_save_changes}</div>
                    </form>
                {/if}
            </td>
            <td>
                {if $browser_cache_ttl.editable}
                    <form name="cachingttlsettingsform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="caching">
                        <input type="hidden" name="cachingaction" value="browsercachettl">
                        <span>{$wgs_lang.cf_caching_browser_cache}</span>
                        <div>{$wgs_lang.cf_caching_browser_cache_text}</div>
                        <div>
                            <select name="browsercachettl" class="input-sm">
                                {foreach from=$TTLSettingValues key=ttlvalue item=TTLSettingValue}
                                    <option value="{$ttlvalue}" {if $browser_cache_ttl.value eq $ttlvalue} selected {/if}>{$TTLSettingValue}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div style="margin-top: 50px !important;" class="btn btn-primary" onclick="cachingttlsettingsform.submit();">{$wgs_lang.cf_caching_save_changes}</div>
                    </form>
                {/if}
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>
                <form name="cachingpurgesinglesettingsform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                    <input type="hidden" name="modop" value="custom">
                    <input type="hidden" name="a" value="ManageCf">
                    <input type="hidden" name="cf_action" value="manageWebsite">
                    <input type="hidden" name="website" value="{$domain}">
                    <input type="hidden" name="cfaction" value="caching">
                    <input type="hidden" name="cachingaction" value="purgesingle">
                    <span>{$wgs_lang.cf_caching_purge_cache}</span>
                    <div>{$wgs_lang.cf_caching_purge_cache_text}</div>
                    <div>
                        <textarea name="singleurls" class="cftextarea"></textarea>
                    </div>
                    <div>{$wgs_lang.cf_caching_purge_multiple}</div>
                    <div class="btn btn-primary" onclick="cachingpurgesinglesettingsform.submit();">{$wgs_lang.cf_caching_purge_individual}</div>
                </form>
            </td>
            <td>
                <form name="cachingpurgeallsettingsform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                    <input type="hidden" name="modop" value="custom">
                    <input type="hidden" name="a" value="ManageCf">
                    <input type="hidden" name="cf_action" value="manageWebsite">
                    <input type="hidden" name="website" value="{$domain}">
                    <input type="hidden" name="cfaction" value="caching">
                    <input type="hidden" name="cachingaction" value="purgeall">
                    <span>{$wgs_lang.cf_caching_purge_cache_all}</span>
                    <div>{$wgs_lang.cf_caching_purge_cache_all_text}</div>
                    <div class="cfyellowinfo">
                        {$wgs_lang.cf_caching_purge_full_cache_text}
                    </div>
                    <div style="margin-top: 65px !important;" class="btn btn-primary" onclick="cachingpurgeallsettingsform.submit();">{$wgs_lang.cf_caching_purge_all_files}</div>
                </form>
            </td>
        </tr>
        <tr>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td>
                {if $always_online.editable}
                    <form name="cachingonlinesettingsform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="caching">
                        <input type="hidden" name="cachingaction" value="alwaysonline">
                        <span>{$wgs_lang.cf_caching_always_online}</span>
                        <div>{$wgs_lang.cf_caching_always_online_text}</div>
                        <div><input type="radio" name="alwaysonline" value="on" {if $always_online.value eq "on"} checked {/if}><span class="customRadio"></span>  {$wgs_lang.cf_caching_on} <input type="radio" name="alwaysonline" value="off" {if $always_online.value eq "off"} checked {/if}><span class="customRadio"></span>  {$wgs_lang.cf_caching_off}</div>
                        <div class="btn btn-primary" onclick="cachingonlinesettingsform.submit();">{$wgs_lang.cf_caching_save_changes}</div>
                    </form>
                {/if}
            </td>
            <td>
                {if $development_mode.editable}
                    <form name="cachingdevelopmentsettingsform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="caching">
                        <input type="hidden" name="cachingaction" value="developmentmode">
                        <span>{$wgs_lang.cf_caching_dev_mode}</span>
                        <div>{$wgs_lang.cf_caching_dev_mode_text}</div>
                        <div><input type="radio" name="developmentmode" value="on" {if $development_mode.value eq "on"} checked {/if}><span class="customRadio"></span> {$wgs_lang.cf_caching_on} <input type="radio" name="developmentmode" value="off" {if $development_mode.value eq "off"} checked {/if}><span class="customRadio"></span>  {$wgs_lang.cf_caching_off}</div>
                        <div style="" class="btn btn-primary" onclick="cachingdevelopmentsettingsform.submit();">{$wgs_lang.cf_caching_save_changes}</div>
                    </form>
                {/if}
            </td>
        </tr>
    </table>

</div>

    {$cffooter}