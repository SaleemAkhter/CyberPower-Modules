

<div class="panel panel-primary">
    <div class="panel-body">{$MGLANG->T('The integration described below is required to display login with social media buttons in the selected client area pages.')}</div>
</div>

<div class="panel-group">
    <div class="panel panel-primary" >
{*    <div class="panel-heading text-center">*}
{*        <h3 class="panel-title">{$MGLANG->T('Login Pages')}</h3>*}
{*    </div>*}
    <div class="panel panel-primary center-block" style="margin: 10px;">
        <div class="panel-heading">
            <h3 class="panel-title">{$MGLANG->T('Login Page (\'Six\'-based theme only)')}</h3>
        </div>
        <div class="panel-body">
            <div class="control-group">
                {$MGLANG->T('In order to enable social buttons on login page, open the file')}
            </div>
            <pre>your_whmcs/templates/six/login.tpl</pre>
            <div class="control-group">
               {$MGLANG->T('Find the following lines')}
            </div>
                {if $SMLWHMCS80}
        <pre><xmp>        <div align="center">
                    <input id="login" type="submit" class="btn btn-primary{literal}{$captcha->getButtonClass($captchaForm)}{/literal}" value={literal}"{$LANG.loginbutton}"{/literal} /> <a href={literal}"{routePath('password-reset-begin')}"{/literal} class="btn btn-default">{literal}{$LANG.forgotpw}{/literal}</a>
                </div>
            </form></xmp></pre>
                {elseif $SMLWHMCS73}
        <pre><xmp>     <div align="center">
                            <input id="login" type="submit" class="btn btn-primary" value={literal}"{$LANG.loginbutton}"{/literal} /> <a href="pwreset.php" class="btn btn-default">{literal}{$LANG.forgotpw}{/literal}</a>
                        </div>
                    </form></xmp></pre>
                {else}
        <pre><xmp>        <div align="center">
                            <input id="login" type="submit" class="btn btn-primary" value={literal}"{$LANG.loginbutton}"{/literal} /> <a href="pwreset.php" class="btn btn-default">{literal}{$LANG.forgotpw}{/literal}</a>
                        </div>
                    </form>
        </div></xmp></pre>
                {/if}
            <div class="control-group">
                {$MGLANG->T('Add this code Before the last div element')}
            </div>
            <pre>{literal}{$social_media_login_integration}{/literal}</pre>
        </div>
    </div>

    <div class="panel panel-primary center-block" style="margin: 10px;">
        <div class="panel-heading">
            <h3 class="panel-title">{$MGLANG->T('Login Page (\'Twenty-One\'-based theme only)')}</h3>
        </div>
        <div class="panel-body">
            <div class="control-group">
                {$MGLANG->T('In order to enable social buttons on login page, open the file')}
            </div>
            <pre>your_whmcs/templates/twenty-one/login.tpl</pre>
            <div class="control-group">
                {$MGLANG->T('Find the following lines')}
            </div>
            <pre><xmp>        <div class="card-footer px-md-5">
                    <small>{literal}{lang key='userLogin.notRegistered'}{/literal}</small>
                    <a href="{literal}{$WEB_ROOT}{/literal}/register.php" class="small font-weight-bold">{literal}{lang key='userLogin.createAccount'}{/literal}</a>
                </div>
            </div>
        </form></xmp></pre>
            <div class="control-group">
                {$MGLANG->T('Add this code Before the last div element')}
            </div>
            <pre>{literal}{$social_media_login_integration}{/literal}</pre>
        </div>
    </div>
    </div>
</div>

{if !isset($blockPopUp)}
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">{$MGLANG->T('Login Popup')}</h3>
        </div>
        <div class="panel-body">
            <div class="control-group">
                {$MGLANG->T('In order to enable social buttons on login popup(header menu), open the file')}        
            </div>
            <pre>your_whmcs/templates/{$template}/header.tpl</pre>
            <div class="control-group">
               {$MGLANG->T('Find the following line')}:         
            </div>
            <pre>{literal}{if $condlinks.allowClientRegistration}{/literal}</pre>
            <div class="control-group">
                {$MGLANG->T('Add this code above that line:')}
            </div>
            <pre>{literal}{$social_media_login_integration_mini}{/literal}</pre>
        </div>
    </div>    
{/if}

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">{$MGLANG->T('Checkout Page')}</h3>
    </div>
    <div class="panel-body">
        <div class="control-group">
            {$MGLANG->T('In order to enable social buttons on checkout page, open the file')}      
        </div>
        <pre>your_whmcs/templates/orderforms/{$orderFormTemplate}</pre>
        <div class="control-group">
           {$MGLANG->T('Find the following lines')}         
        </div>
        <pre><xmp>{$integrationCodePlace}</xmp></pre>
        <div class="control-group">
            {$MGLANG->T('Add this code above those lines:')}
        </div>
        <pre>{literal}{$social_media_login_integration}{/literal}</pre>
    </div>
</div>

