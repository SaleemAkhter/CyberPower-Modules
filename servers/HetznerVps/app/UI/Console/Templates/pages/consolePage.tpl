{**********************************************************************
* ModuleFramework product developed. (2017-10-10)
*
*
*  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
*  CONTACT                        ->       contact@modulesgarden.com
*
*
* This software is furnished under a license and may be used and copied
* only  in  accordance  with  the  terms  of such  license and with the
* inclusion of the above copyright notice.  This software  or any other
* copies thereof may not be provided or otherwise made available to any
* other person.  No title to and  ownership of the  software is  hereby
* transferred.
*
*
**********************************************************************}


{**
* @author Mateusz Pawłowski <mateusz.pa@moduelsagrden.com>
*}

{if !$rawObject->isError()}

{/if}

{literal}
<div class="lu-row">
    <div class="lu-col-md-12">
        <div class="lu-widget">
            {/literal}{if $rawObject->getRawTitle() || $rawObject->getTitle()}{literal}
                <div class="lu-widget__header">
                <div class="lu-widget__top lu-top">
                <div class="lu-top__title">
            {/literal}{if $rawObject->isError()}
                {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}{if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
            {else}{literal}
                <div name="noVNC_status_bar" class="noVNC_status_bar_top">
                    <div id="noVNC_status">Loading</div>
                </div>
            {/literal}{/if}{literal}
                </div>
                <div class="lu-top__toolbar">
            {/literal}{if !$rawObject->isError()}{literal}
                    <div id="noVNC_buttons">
                        <input type=button value="Send Ctrl+Alt+Del"id="sendCtrlAltDelButton" class="noVNC_shown lu-btn lu-btn--danger no-vnc-btn" style="color: #fff !important;">
                        <span id="noVNC_power_buttons" class="noVNC_hidden">
                                    <input type=button value="Shutdown" class="lu-btn lu-btn--danger no-vnc-btn"
                                           id="machineShutdownButton">
                                    <input type=button value="Reboot" class="lu-btn lu-btn--danger no-vnc-btn"
                                           id="machineRebootButton">
                                    <input type=button value="Reset" class="lu-btn lu-btn--danger no-vnc-btn"
                                           id="machineResetButton">
                                </span>
                    </div>
                {/literal}{/if}{literal}
                </div>
                </div>
                </div>
            {/literal}{/if}{literal}
            <div class="lu-widget__body">
                {/literal}{if $rawObject->isError()}{literal}
                    <div class="col-md-12" style="padding-top:15px;">
                    <div class="lu-alert lu-alert--danger">
                    <div class="lu-alert__body">{/literal}{$rawObject->getError()}{literal}</div>
                    </div>
                    </div>
                {/literal}{else}{literal}

                    <div id="noVNCConsolePlacecholder">
                    </div>
                    <div id="noVNC_status_bar" name="noVNC_status_bar" {/literal}{if !$rawObject->getUrl()} style="display: none!important" {/if}{literal}>
                    <div id="noVNC_left_dummy_elem"></div>
                    </div>

                {/literal}{/if}{literal}
            </div>
        </div>
    </div>
</div>
{/literal}

<script type="application/javascript">
    var noVncUrl = '{$rawObject->getUrl()}';
    var noVncPassword = '{$rawObject->getPassword()}';
</script>

{literal}
<script type="module" crossorigin="anonymous">
    window._noVNC_has_module_support = true;
    window.MGConsoleRuning = false;

    import * as WebUtil from '{/literal}{$rawObject->getAssetsUrl()}{literal}/app/webutil.js';
    import RFB from '{/literal}{$rawObject->getAssetsUrl()}{literal}/core/rfb.js';

    var rfb;
    var desktopName;

    function updateDesktopName(e) {
        desktopName = e.detail.name;
    }
    function credentials(e) {
        var html;

        console.log('adawd');
        var form = document.createElement('form');
        form.innerHTML = '<label></label>';
        form.innerHTML += '<input type=password size=10 id="password_input">';
        form.onsubmit = setPassword;

        // bypass status() because it sets text content
        document.getElementsByName('noVNC_status_bar').forEach(
            function(currentValue) {
                currentValue.setAttribute("class", "noVNC_status_warn");
            });
        document.getElementById('noVNC_status').innerHTML = '';
        document.getElementById('noVNC_status').appendChild(form);
        document.getElementById('noVNC_status').querySelector('label').textContent = 'Password Required: ';
    }
    function setPassword() {
        rfb.sendCredentials({ password: document.getElementById('password_input').value });
        return false;
    }
    function sendCtrlAltDel() {
        rfb.sendCtrlAltDel();
        return false;
    }
    function machineShutdown() {
        rfb.machineShutdown();
        return false;
    }
    function machineReboot() {
        rfb.machineReboot();
        return false;
    }
    function machineReset() {
        rfb.machineReset();
        return false;
    }
    function status(text, level) {
        switch (level) {
            case 'normal':
            case 'warn':
            case 'error':
                break;
            default:
                level = "warn";
        }

        document.getElementsByName('noVNC_status_bar').forEach(
            function(currentValue) {
                currentValue.className = "noVNC_status_" + level;
            });
        document.getElementById('noVNC_status').textContent = text;
    }

    function connected(e) {
        document.getElementById('sendCtrlAltDelButton').disabled = false;
        if (WebUtil.getConfigVar('encrypt',
            (window.location.protocol === "https:"))) {
            status("Connected (encrypted) to " + desktopName, "normal");
        } else {
            status("Connected (unencrypted) to " + desktopName, "normal");
        }
    }

    function disconnected(e) {
        document.getElementById('sendCtrlAltDelButton').disabled = true;
        updatePowerButtons();
        if (e.detail.clean) {
            status("Disconnected", "normal");
        } else {
            status("Something went wrong, connection is closed", "error");
        }
    }

    function updatePowerButtons() {
        var powerbuttons;
        powerbuttons = document.getElementById('noVNC_power_buttons');

        console.log(rfb.capabilities, 'xxxxx');
        if (rfb.capabilities.power) {
            powerbuttons.className= "noVNC_shown";
        } else {
            powerbuttons.className = "noVNC_hidden";
        }
    }

    document.getElementById('sendCtrlAltDelButton').onclick = sendCtrlAltDel;
    document.getElementById('machineShutdownButton').onclick = machineShutdown;
    document.getElementById('machineRebootButton').onclick = machineReboot;
    document.getElementById('machineResetButton').onclick = machineReset;

    document.title = WebUtil.getConfigVar('title', 'noVNC');
    // By default, use the host and port of server that served this file

    var port = WebUtil.getConfigVar('port', '');

    // if port == 80 (or 443) then it won't be present and should be
    // set manually
    if (!port) {
        if (window.location.protocol.substring(0,5) == 'https') {
            port = 443;
        }
        else if (window.location.protocol.substring(0,4) == 'http') {
            port = 80;
        }
    }

    var path = WebUtil.getConfigVar('path', '');

    // If a token variable is passed in, set the parameter in a cookie.
    // This is used by nova-novncproxy.
    var token = WebUtil.getConfigVar('token', null);
    if (token) {
        // if token is already present in the path we should use it
        path = WebUtil.injectParamIfMissing(path, "token", token);

        WebUtil.createCookie('token', token, 1)
    }


    $(function () {
        status("Connecting", "normal");

        if ((!noVncUrl) || (!port)) {
            status('Must specify host and port in URL', 'error');
        }

        rfb = new RFB(document.getElementById('noVNCConsolePlacecholder'), noVncUrl,
        {credentials: { password: noVncPassword } });
    rfb.addEventListener("connect", connected);
    rfb.addEventListener("disconnect", disconnected);
    rfb.addEventListener("capabilities", function () {
        updatePowerButtons();
    });
    rfb.addEventListener("credentialsrequired", credentials);
    rfb.addEventListener("desktopname", updateDesktopName);
    rfb.addEventListener('securityfailure', function(){
        console.log('adawd');
    });
    rfb.scaleViewport = WebUtil.getConfigVar('scale', false);
    rfb.resizeSession = WebUtil.getConfigVar('resize', false);
    localStorage.removeItem('firstLoad');
    });


</script>


{/literal}
<link rel="stylesheet" href="{$rawObject->getAssetsUrl()}/app/styles/lite.css">
