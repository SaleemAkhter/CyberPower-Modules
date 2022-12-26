{$headerhtml}
{$menu}
<div class="cfcontent">
    <h3 class="cfcontentmargin">{$wgs_lang.cf_speed_manage_speed}</h3>
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
                {if $minify.editable}
                    <form name="managespeedminifyform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="speed">
                        <input type="hidden" name="speedaction" value="autominify">
                        <span>{$wgs_lang.cf_speed_auto_minify}</span>
                        <div>{$wgs_lang.cf_speed_auto_minify_text}</div>
                        <div><input type="checkbox" name="autominifyjs" value="on" {if $minify.value.js eq "on"} checked {/if}> {$wgs_lang.cf_speed_js}</div>
                        <div><input type="checkbox" name="autominifycss" value="on" {if $minify.value.css eq "on"} checked {/if}> {$wgs_lang.cf_speed_css}</div>
                        <div><input type="checkbox" name="autominifyhtml" value="on" {if $minify.value.html eq "on"} checked {/if}> {$wgs_lang.cf_speed_html}</div>
                        <div class="btn btn-primary" onclick="managespeedminifyform.submit();">{$wgs_lang.cf_speed_save_changes}</div>
                    </form>
                {/if}
            </td>
            <td>
                {if $rocket_loader.editable}
                    <form name="managespeedrocketform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="speed">
                        <input type="hidden" name="speedaction" value="rocketloader">
                        <span>{$wgs_lang.cf_speed_rocket_loader}</span>
                        <div>{$wgs_lang.cf_speed_rocket_loader_text}</div>
                        <div>
                            <select name="rocketloader" class="input-sm">
                                <option value="on" {if $rocket_loader.value eq "on"} selected {/if}>{$wgs_lang.cf_speed_on}</option>
                                <option value="off" {if $rocket_loader.value eq "off"} selected {/if}>{$wgs_lang.cf_speed_off}</option>
                                <option value="manual" {if $rocket_loader.value eq "manual"} selected {/if}>{$wgs_lang.cf_speed_manual}</option>
                            </select>
                        </div>
                        <div class="btn btn-primary" onclick="managespeedrocketform.submit();">{$wgs_lang.cf_speed_save_changes}</div>
                    </form>
                {/if}
            </td>
        </tr>

        <tr>
            <td colspan="2"></td>
        </tr>

        <tr>
            <td>
                {if $mobile_redirect.editable}
                    <form name="managespeedmobileredirectform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="speed">
                        <input type="hidden" name="speedaction" value="mobileredirect">
                        <span>{$wgs_lang.cf_speed_mobile_redirect}</span>
                        <div>{$wgs_lang.cf_speed_mobile_redirect_text}</div>
                        <div><input type="text" name="mobileredirectsubdomain" value="{$mobile_redirect.value.mobile_subdomain}" placeholder="{$wgs_lang.cf_speed_enter_subdomain}" class="input-sm"></div>
                        <div>
                            <select name="mobileredirectstripuri" class="input-sm">
                                <option value="false" {if $mobile_redirect.value.strip_uri eq ""} selected {/if}>{$wgs_lang.cf_speed_keep_path}</option>
                                <option value="true" {if $mobile_redirect.value.strip_uri eq "1"} selected {/if}>{$wgs_lang.cf_speed_drop_path}</option>
                            </select>
                        </div>
                        <div>
                            <select name="mobileredirectmode" class="input-sm">
                                <option {if $mobile_redirect.value.status eq "on"} selected {/if} value="on">{$wgs_lang.cf_speed_on}</option>
                                <option {if $mobile_redirect.value.status eq "off"} selected {/if} value="off">{$wgs_lang.cf_speed_off}</option>
                            </select>
                        </div>
                        <div class="btn btn-primary" onclick="managespeedmobileredirectform.submit();">{$wgs_lang.cf_speed_save_changes}</div>
                    </form>
                {/if}
            </td>
            <td>
                {if $ip_geolocation.editable}
                    <form name="managespeedipgeolocationform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="speed">
                        <input type="hidden" name="speedaction" value="ipgeolocation">
                        <span>{$wgs_lang.cf_speed_ip_geolocation}</span>
                        <div>{$wgs_lang.cf_speed_ip_geolocation_text}</div>
                        <div>
                            <select name="ipgeolocation" class="input-sm">
                                <option value="on" {if $ip_geolocation.value eq "on"} selected {/if}>{$wgs_lang.cf_speed_on}</option>
                                <option value="off" {if $ip_geolocation.value eq "off"} selected {/if}>{$wgs_lang.cf_speed_off}</option>
                            </select>
                        </div>
                        <div class="btn btn-primary" onclick="managespeedipgeolocationform.submit();">{$wgs_lang.cf_speed_save_changes}</div>
                    </form>
                {/if}
            </td>
        </tr>
        {if $polish.editable && $mirage.editable} {* Pro Account *}
                <tr>
                    <td colspan="2"></td>
                </tr>

                <tr>
                    <td>
                        {if $polish.editable} {* Pro Account *}
                                <form name="managespeedpolishform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                                    <input type="hidden" name="modop" value="custom">
                                    <input type="hidden" name="a" value="ManageCf">
                                    <input type="hidden" name="cf_action" value="manageWebsite">
                                    <input type="hidden" name="website" value="{$domain}">
                                    <input type="hidden" name="cfaction" value="speed">
                                    <input type="hidden" name="speedaction" value="polish">
                                    <span>{$wgs_lang.cf_speed_polish}</span>
                                    <div>{$wgs_lang.cf_speed_polish_text}</div>
                                    <div>
                                        <select name="polish" class="input-sm">
                                            <option value="off" {if $polish.value eq "off"} selected {/if}>{$wgs_lang.cf_speed_off}</option>
                                            <option value="lossless" {if $polish.value eq "lossless"} selected {/if}>{$wgs_lang.cf_speed_lossless}</option>
                                            <option value="lossy" {if $polish.value eq "lossy"} selected {/if}>{$wgs_lang.cf_speed_lossy}</option>
                                        </select>
                                    </div>
                                    <div class="btn btn-primary" onclick="managespeedpolishform.submit();">{$wgs_lang.cf_speed_save_changes}</div>
                                </form>
                            {/if}
                        </td>
                        <td>
                            {if $mirage.editable} {* Pro Account *}
                                    <form name="managespeedmirageform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                                        <input type="hidden" name="modop" value="custom">
                                        <input type="hidden" name="a" value="ManageCf">
                                        <input type="hidden" name="cf_action" value="manageWebsite">
                                        <input type="hidden" name="website" value="{$domain}">
                                        <input type="hidden" name="cfaction" value="speed">
                                        <input type="hidden" name="speedaction" value="mirage">
                                        <span>{$wgs_lang.cf_speed_mirage}</span>
                                        <div>{$wgs_lang.cf_speed_mirage_text}</div>
                                        <div>
                                            <select name="mirage" class="input-sm">
                                                <option value="on" {if $mirage.value eq "on"} selected {/if}>{$wgs_lang.cf_speed_on}</option>
                                                <option value="off" {if $mirage.value eq "off"} selected {/if}>{$wgs_lang.cf_speed_off}</option>
                                            </select>
                                        </div>
                                        <div class="btn btn-primary" onclick="managespeedmirageform.submit();">{$wgs_lang.cf_speed_save_changes}</div>
                                    </form>
                                {/if}
                            </td>
                        </tr>
                    {/if}
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td>
                            {if $max_upload.editable}
                                <form name="managespeedmaxuploadform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                                    <input type="hidden" name="modop" value="custom">
                                    <input type="hidden" name="a" value="ManageCf">
                                    <input type="hidden" name="cf_action" value="manageWebsite">
                                    <input type="hidden" name="website" value="{$domain}">
                                    <input type="hidden" name="cfaction" value="speed">
                                    <input type="hidden" name="speedaction" value="maxupload">
                                    <span>{$wgs_lang.cf_speed_max_upload_size}</span>
                                    <div>{$wgs_lang.cf_speed_max_upload_size_text}</div>
                                    <div>
                                        <select name="maxupload" class="input-sm">
                                            {foreach from=$uploadsizes key=s  item=size}
                                                <option value="{$s}">{$size}</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                            <div class="btn btn-primary" onclick="managespeedmaxuploadform.submit();">{$wgs_lang.cf_speed_save_changes}</div>
                                </form>
                            {/if}
                        </td>
                    </tr>
                </table>
            </div>
            {$cffooter}