{$headerhtml}
{$menu}
<div class="cfcontent">
    <h3 class="cfcontentmargin">{$wgs_lang.cf_crypto_manage}</h3>
</div>
<div class="cfinternal">
    {*{if $ssl.certificate_status eq "not_eligible" or $ssl.certificate_status eq "none"}
    <div class="cfnofeature">{$wgs_lang.cf_crypto_sorry}, <i>{$domain}</i> {$wgs_lang.cf_crypto_ssl_not_enable}</div>
    {/if}*}

    {*    {if $ssl.certificate_status eq "active"}*}


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
                {if $ssl}
                    <form name="cryptosslform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="crypto">
                        <input type="hidden" name="cryptoaction" value="ssl">
                        <span>{$wgs_lang.cf_crypto_ssl_sdpy}</span>
                        <div>{$wgs_lang.cf_crypto_website_protected}</div>
                        <div>
                            <select name="ssl" class="input-sm">
                                {foreach from=$sslvalues key=v item=sslvalue}
                                    <option value="{$v}" {if $ssl.value eq $v} selected {/if}>{$sslvalue}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="btn btn-primary" onclick="cryptosslform.submit();">{$wgs_lang.cf_crypto_save_changes}</div>
                    </form>
                {/if}
            </td>
            <td>
                {if $security_header.editable}
                    <form name="cryptosecurityheaderform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="crypto">
                        <input type="hidden" name="cryptoaction" value="securityheader">
                        <span>{$wgs_lang.cf_crypto_hsts_strict}</span>
                        <div>{$wgs_lang.cf_crypto_enforce_web_security}</div>
                        <div>
                            <font style="margin-right: 65px;">{$wgs_lang.cf_crypto_enable_hsts}</font><input type="checkbox" name="securityheaderenabled" {if $security_header.value.strict_transport_security.enabled}checked{/if}>
                        </div>
                        <div> 
                            <font style="margin-right: 50px;">{$wgs_lang.cf_crypto_max_age_header}</font><select name="securityheadermaxage" class="input-sm">
                                {foreach from=$hstsmaxagevalues key=age item=maxage}
                                    <option value="{$age}" {if $security_header.value.strict_transport_security.max_age eq $age}selected{/if}>{$maxage}</option>
                                {/foreach}
                            </select></div>
                        <div>
                            <font style="margin-right: 25px;">{$wgs_lang.cf_crypto_include_sub_domains}</font><select name="securityheadersubdomains" class="input-sm">
                                <option value="false" {if !$security_header.value.strict_transport_security.include_subdomains}selected{/if}>{$wgs_lang.cf_crypto_false}</option>
                                <option value="true" {if $security_header.value.strict_transport_security.include_subdomains}selected{/if}>{$wgs_lang.cf_crypto_true}</option>
                            </select></div>
                        <div><font style="margin-right: 103px;">{$wgs_lang.cf_crypto_preload}</font><select name="securityheaderpreload" class="input-sm">
                                <option value="false" {if !$security_header.value.strict_transport_security.preload}selected{/if}>{$wgs_lang.cf_crypto_false}</option>
                                <option value="true" {if $security_header.value.strict_transport_security.preload}selected{/if}>{$wgs_lang.cf_crypto_true}</option>
                            </select></div>
                        <div class="btn btn-primary" onclick="cryptosecurityheaderform.submit();">{$wgs_lang.cf_crypto_save_changes}</div>
                    </form>
                {/if}
            </td>
        </tr>
        <tr><td colspan="2"></td></tr>
        <tr>
            <td style="width: 100%">
                {if $ssl}
                    <form name="cryptoclientauthform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="crypto">
                        <input type="hidden" name="cryptoaction" value="tlsclientauth">
                        <span>{$wgs_lang.cf_crypto_auth_origin_pulls}</span>
                        <div>{$wgs_lang.cf_crypto_tls_certificate}</div>
                        <div>
                            <select name="tlsclientauth" class="input-sm">
                                <option value="on" {if $tls_client_auth.value eq 'on'}selected{/if}>{$wgs_lang.cf_crypto_on}</option>
                                <option value="off" {if $tls_client_auth.value eq 'off'}selected{/if}>{$wgs_lang.cf_crypto_off}</option>
                            </select>
                        </div>
                        <div class="btn btn-primary" onclick="cryptoclientauthform.submit();">{$wgs_lang.cf_crypto_save_changes}</div>
                    </form>
                {/if}
            </td>
        </tr>
        <tr><td colspan="2"></td></tr>
        <tr>
            <td colspan="100%" style="width: 100%">
                {if $always_use_https.editable}
                    <form name="cryptoalwayshttpsform" method="post" action="clientarea.php?action=productdetails&id={$smarty.get.id}">
                        <input type="hidden" name="modop" value="custom">
                        <input type="hidden" name="a" value="ManageCf">
                        <input type="hidden" name="cf_action" value="manageWebsite">
                        <input type="hidden" name="website" value="{$domain}">
                        <input type="hidden" name="cfaction" value="crypto">
                        <input type="hidden" name="cryptoaction" value="alwaysusehttps">
                        <span>{$wgs_lang.cf_crypto_always_use_https_header}</span>
                        <div>{$wgs_lang.cf_crypto_always_use_https_desc}</div>
                        <div>
                            <select name="alwaysusehttps" class="input-sm">
                                <option value="on" {if $always_use_https.value eq 'on'}selected{/if}>{$wgs_lang.cf_always_use_https_on}</option>
                                <option value="off" {if $always_use_https.value eq 'off'}selected{/if}>{$wgs_lang.cf_always_use_https_off}</option>
                            </select>
                        </div>
                        <div class="btn btn-primary" onclick="cryptoalwayshttpsform.submit();">{$wgs_lang.cf_always_use_https_save_changes}</div>
                    </form>
                {/if}
            </td>
        </tr>
    </table>
    {*    {/if}*}
</div>
{$cffooter}