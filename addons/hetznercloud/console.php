<?php

    use WHMCS\Database\Capsule;

    use WHMCS\Createoptions\HetznerApi as HetznerApicall;

    include_once dirname(__DIR__) . '/../../init.php';

    if (file_exists(__DIR__ . '/class.CreateFields.php'))
        include_once __DIR__ . '/class.CreateFields.php';
    global $whmcs;
    
    $serviceid = $whmcs->get_req_var('query');
    $adminOnly = $whmcs->get_req_var('admin');
    if($adminOnly == "true")
        $getParams = Capsule::table('tblhosting')->select('tblproducts.configoption1', 'tblproducts.id')->join('tblproducts', 'tblhosting.packageid', '=', 'tblproducts.id')->where('tblhosting.id', $serviceid)->first();
    else
        $getParams = Capsule::table('tblhosting')->select('tblproducts.configoption1', 'tblproducts.id')->join('tblproducts', 'tblhosting.packageid', '=', 'tblproducts.id')->where('tblhosting.id', $serviceid)->where('tblhosting.userid', $_SESSION['uid'])->first();

    if("" == $getParams->configoption1){
        die('<font color="red">Service not found!</font>');
    }   
    $pid = $getParams->id;

    $HetznerApicall = new HetznerApicall($getParams->configoption1);
    
    $fieldid = $HetznerApicall->getCustomFieldId($pid, "server_id");
    $getServerID = Capsule::table('tblcustomfieldsvalues')->where('fieldid', $fieldid)->where('relid', $serviceid)->first();
    $serverID = $getServerID->value;

    if("" == $serverID){
        die('<font color="red">Server not found!</font>');
    }   

    $getConsoleURL = $HetznerApicall->post("servers/" . $serverID . "/actions/request_console", null);
    logModuleCall("Hetzner", 'Get console access', 'Server ID: ' . $serverID, $getConsoleURL);
    $consoleURL = $getConsoleURL->wss_url;
    $consoleErr = $getConsoleURL->error->message;
    $password = $getConsoleURL->password;
    $getServerDetail = $HetznerApicall->get('servers/' . $serverID);
    $server_name = $getServerDetail->server->name;

    if('' != $consoleErr){
        die('<font color="red">OOPs, Something went wrong! Please contact with support</font>');
    }
    if($adminOnly == "true" && !isset($_SESSION['adminid'])){
        die('<font color="red">Session timed out!</font>');
    }
    elseif(!isset($_SESSION['uid']) && !defined("WHMCS") && !isset($_SESSION['adminid'])){
        die('<font color="red">Session timed out!</font>');
    }else{
        ?>
<!DOCTYPE html>
<html>
<head>

    <title>Server: <?php echo $server_name ; ?></title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <style type="text/css">

        body {
            margin: 0;
            background-color: dimgrey;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        html {
            height: 100%;
        }

        #top_bar {
            background-color: #6e84a3;
            color: white;
            font: bold 12px Helvetica;
            padding: 6px 5px 4px 5px;
            border-bottom: 1px outset;
        }
        #status {
            text-align: center;
        }
        #sendCtrlAltDelButton {
            position: fixed;
            top: 0px;
            right: 0px;
            border: 1px outset;
            padding: 5px 5px 4px 5px;
            cursor: pointer;
        }

        #screen {
            flex: 1; 
            overflow: hidden;
        }

    </style>

    <script src="novnc/vendor/promise.js"></script>

    <script type="module">
        window._noVNC_has_module_support = true;
    </script>
    <script>
        window.addEventListener("load", function() {
            if (window._noVNC_has_module_support) return;
            const loader = document.createElement("script");
            loader.src = "novnc/vendor/browser-es-module-loader/dist/" + 
                "novnc/browser-es-module-loader.js";
            document.head.appendChild(loader);
        });
    </script>

    <script type="module" crossorigin="anonymous">
        import RFB from '/modules/servers/hetznercloud/novnc/core/rfb.js';

        let rfb;
        let desktopName;

        function connectedToServer(e) {
            status("Connected to " + desktopName);
        }

        function disconnectedFromServer(e) {
            if (e.detail.clean) {
                status("Disconnected");
            } else {
                status("Something went wrong, connection is closed");
            }
        }
        function credentialsAreRequired(e) {
            const password = prompt("Password Required:");
            rfb.sendCredentials({ password: password });
        }

        function updateDesktopName(e) {
            desktopName = e.detail.name;
        }

        function sendCtrlAltDel() {
            rfb.sendCtrlAltDel();
            return false;
        }

        function status(text) {
            document.getElementById('status').textContent = text;
        }

        function readQueryVariable(name, defaultValue) {
            const re = new RegExp('.*[?&]' + name + '=([^&#]*)'),
                  match = document.location.href.match(re);
            if (typeof defaultValue === 'undefined') { defaultValue = null; }

            if (match) {
                return decodeURIComponent(match[1]);
            }

            return defaultValue;
        }

        document.getElementById('sendCtrlAltDelButton').onclick = sendCtrlAltDel;

        const host = readQueryVariable('host', '<?php echo $consoleURL; ?>');
        let port = readQueryVariable('port', '');
        const password = readQueryVariable('password', '<?php echo $password; ?>');
        const path = readQueryVariable('path', '');

        status("Connecting");

        let url;
        if (window.location.protocol === "https:") {
            url = '';
        } else {
            url = '';
        }
        url += '' + host;
        if(port) {
            url += ':' + port;
        }
        url += '' + path;

        rfb = new RFB(document.getElementById('screen'), url,
                      { credentials: { password: password } });

        rfb.addEventListener("connect",  connectedToServer);
        rfb.addEventListener("disconnect", disconnectedFromServer);
        rfb.addEventListener("credentialsrequired", credentialsAreRequired);
        rfb.addEventListener("desktopname", updateDesktopName);

        rfb.viewOnly = readQueryVariable('view_only', false);
        rfb.scaleViewport = readQueryVariable('scale', false);
    </script>
</head>

<body>
    <div id="top_bar">
        <div id="status">Loading</div>
        <div id="sendCtrlAltDelButton">Send CtrlAltDel</div>
    </div>
    <div id="screen">
    </div>
</body>
</html>




<?php
    }
?>