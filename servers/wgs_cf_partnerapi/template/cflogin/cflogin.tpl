<p>{$breadcrumbnav}</p>
<link rel="stylesheet" type="text/css" href="{$url}css/cf_style.css"/>
<link rel="stylesheet" type="text/css" href="{$url}css/stats/cfstyle.css"/>
<script type="text/javascript" src="{$url}js/cf.js"></script>
{if $zone_error}
    <div class="login_error">{$zone_error}</div>
    <div class="cf_well">
        <div class="cf_textcenter">
            <fieldset class="cf_control-group_login">
                <form method="post" action="" class="_login">
                    <input type="hidden" name="cflogin" value="1">
                    <input class="cf_bigfield" name="cfemail" id="cfemail" required type="hidden" autocomplete="off" autofocus="autofocus" value="{if $emailaddress}{$emailaddress}{/if}" placeholder="{$wgs_lang.cf_youremail}">
                    <input class="cf_bigfield" name="cfpw" id="cfpw" required type="hidden" autocomplete="off" value="{if $cfpassowrd}{$cfpassowrd}{/if}" placeholder="{$wgs_lang.cf_yourpw}">
                    <div class="align_cenetr"><input style="margin-top: 10px;" type="submit" class="cf_btn cf_btn-large cf_btn-primary" value="{$wgs_lang.cf_tryagain}"></div>
                </form>
            </fieldset>
        </div>
    </div>
{else}
    {if $login_error}
        <div class="login_error">{$login_error}</div>
    {/if}
    <div class="login_header">{$wgs_lang.cf_login_header}</div>
    <div class="cf_well">
        <div class="cf_textcenter">
            <fieldset class="cf_control-group_login">
                <form method="post" action="" class="_login">
                    <input type="hidden" name="cflogin" value="1">
                    <label for="cfemail">{$wgs_lang.cf_youremaillabel}</label>
                    <input class="cf_bigfield" name="cfemail" id="cfemail" required type="email" autocomplete="off" autofocus="autofocus" value="{if $emailaddress}{$emailaddress}{/if}" placeholder="{$wgs_lang.cf_youremail}">
                    <br/>
                    <label for="cfpw">{$wgs_lang.cf_yourpwlabel}</label>
                    <input class="cf_bigfield" name="cfpw" id="cfpw" required type="password" autocomplete="off" value="{if $cfpassowrd}{$cfpassowrd}{/if}" placeholder="{$wgs_lang.cf_yourpw}">
                    <div class="align_cenetr"><input style="margin-top: 10px;" type="submit" class="cf_btn cf_btn-large cf_btn-primary" value="{$wgs_lang.cf_continue}"></div>
                </form>
            </fieldset>
        </div>
    </div>
{/if}