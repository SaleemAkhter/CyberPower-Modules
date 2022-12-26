<?php
use Illuminate\Database\Capsule\Manager as Capsule;
if (file_exists(__DIR__ . '/class/class.php'))
    require_once __DIR__ . '/class/class.php';
if (file_exists(__DIR__ . '/manage_cfields.php'))
    require_once __DIR__ . '/manage_cfields.php';
if (file_exists(dirname(dirname(dirname(__DIR__))) . '/init.php'))
    include_once dirname(dirname(dirname(__DIR__))) . '/init.php';
global $whmcs;
$tokenEncrypt = $whmcs->get_req_var('console_token');
$WgsVmwareObj = new WgsVmware();
$WgsVmwareObj->vmware_includes_files($params);
$consoleDataArr = $WgsVmwareObj->wgsvmwarePwdecryption($tokenEncrypt);
parse_str($consoleDataArr, $consoleData);
$params = $consoleData['consoleVars'];
$ticket = $host = $port = '';
$getServerIp = Capsule::table("mod_vmware_proxy_setup")->first();

$proxyIP = $getServerIp->server_ip;
if (count($params) == 0 || (!isset($_SESSION['uid']) && empty($_SESSION['uid']))) {
    echo '<style>
            p{
                text-align: center;
                color: #ff0000;
                font-size: 30px;
                position: absolute;
                margin: 0 auto;
                left: 0;
                right: 0;
                padding-top: 5%;
                height: 100%;
                width: 100%;
                background: #282525;
            }
            body{margin:0px;}
           </style> ';
    die('<p>Your session has been lost!</p>');
} else {
    $ApiServerName = ($params['customfields']['vcenter_server_name'] != '') ? $params['customfields']['vcenter_server_name']  : $params['configoption3'];
    $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->first();
    if (count($serverData) == 0) {
        $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->first();
    }

    $getip = explode('://', $serverData->vsphereip);
    $decryptPw = decrypt($serverData->vspherepassword);
    $vms = new vmware($getip[1], $serverData->vsphereusername, html_entity_decode($decryptPw));
    $customFieldVal = vmwareGetCustomFiledVal($params);
    $vm_name  = $customFieldVal['vm_name'];
    $vm_state = $vms->get_vm_info($vm_name)->summary->runtime->powerState;
    if ( 'poweredOn' != $vm_state ) {
        echo '<style>
            p{
                text-align: center;
                color: #ff0000;
                font-size: 30px;
                position: absolute;
                margin: 0 auto;
                left: 0;
                right: 0;
                padding-top: 5%;
                height: 100%;
                width: 100%;
                background: #282525;
            }
            body{margin:0px;}
           </style> ';
        die('<p>You can\'t proceed if VM is in the '.$vm_state.' state. Please Power on to view the console.</p>');
    }
    if($vms->WGS_vSphereVersion() < 6){
        $consoleTicket = $vms->createTicketToken($vm_name, true);
    }else{
        $consoleTicket = $vms->createTicketToken($vm_name);
    }
    $ticket = $consoleTicket->ticket;
    $host = $consoleHost = (!empty($consoleTicket->host)) ? $consoleTicket->host : $params['hostName'];
    if ($proxyIP)
        $consoleHost = $proxyIP;
    $port = 443;//$consoleTicket->port;
    $cfgFile = $consoleTicket->cfgFile;
    $sslThumbprint = $consoleTicket->sslThumbprint;
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $http = 'wss';
        $imageProtocol = 'https';
    } else {
        $http = 'ws';
        $imageProtocol = 'http';
    }
}
if (!empty($ticket)) {
?>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title><?php echo $vm_name; ?></title>
        <link rel="stylesheet" type="text/css" href="console_lib/wmks-all.css" />
        <link rel="stylesheet" type="text/css" href="console_lib/style.css" />
    </head>
    <script type="text/javascript">
        if (!window.console) {
            console = {
                log: function() {
                }
            };
        }
    </script>
    <body oncontextmenu="return false">
        <div id="bar">
            <div id="buttonBar">
                <div class="buttonC">
                    <button id="relativepad">
                        Toggle RelativePad
                    </button>
                    <button id="keyboard" data-toggle="false" data-alt="Don't Enforce US Keyboard Layout">
                        Enforce US Keyboard Layout
                    </button>
                    <button id="fullscreen">
                        View Fullscreen
                    </button>
                    <button id="cad">
                        Send Ctrl+Alt+Delete
                    </button>
                </div>
            </div>
            <div id="vmName">
                <span id="vmTitle"><?php echo $vm_name; ?></span>
            </div>
        </div>
        <div id="container"></div>
        <div id="spinner">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
            <div class="bar4"></div>
            <div class="bar5"></div>
            <div class="bar6"></div>
            <div class="bar7"></div>
            <div class="bar8"></div>
            <div class="bar9"></div>
            <div class="bar10"></div>
            <div class="bar11"></div>
            <div class="bar12"></div>
        </div>
        <script type="text/javascript" src="console_lib/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="console_lib/jquery-ui.min.js"></script>
        <script type="text/javascript" src="console_lib/wmks.min.js"></script>
        <script>
            function layout() {
                var w = $(window).width();
                var h = $(window).height();
                if (!wmks.isFullScreen()) {
                    container.css({
                        top: bar.outerHeight() + "px"
                    });
                    container.width(w).height(h - bar.outerHeight());
                    wmks.updateScreen();
                } else {
                    container.css({
                        top: 0,
                        left: 0
                    });
                    container.width(w).height(h);
                }
            }
            function showMessage(message) {
                container.html(message);
                bar.slideDown("fast", layout);
                spinner.hide();
            }
            function getKeyboardLayout() {
                var locale = "en_US".
                replace("-", "_");
                switch (locale) {
                    case "de":
                    case "de_DE":
                        return "de-DE";
                    case "ja":
                    case "ja_JP":
                        return "ja-JP_106/109";
                    case "it":
                    case "it_IT":
                        return "it-IT";
                    case "es":
                    case "es_ES":
                        return "es-ES";
                    case "pt":
                    case "pt_PT":
                        return "pt-PT";
                    default:
                        return "en-US";
                }
            }
            var bar = $("#bar");
            var cad = $("#cad");
            var container = $("#container");
            var fullscreen = $("#fullscreen");
            var keyboard = $("#keyboard");
            var spinner = $("#spinner");
            var relativepad = $("#relativepad");
            var wmks = WMKS.createWMKS("container", {
                keyboardLayoutId: getKeyboardLayout()
            });
            wmks.register(WMKS.CONST.Events.CONNECTION_STATE_CHANGE, function(evt, data) {
                switch (data.state) {
                    case WMKS.CONST.ConnectionState.CONNECTING:
                        console.log("The console is connecting");
                        bar.slideUp("slow", layout);
                        break;
                    case WMKS.CONST.ConnectionState.CONNECTED:
                        console.log("The console has been connected");
                        spinner.hide();
                        bar.slideDown("fast", layout);
                        break;
                    case WMKS.CONST.ConnectionState.DISCONNECTED:
                        console.log("The console has been disconnected");
                        showMessage("The console has been disconnected. Close this window and re-launch the console to reconnect.");
                        break;
                }
            });
            wmks.register(WMKS.CONST.Events.ERROR, function(evt, data) {
                console.log("Error: " + data.errorType);
            });
            wmks.register(WMKS.CONST.Events.REMOTE_SCREEN_SIZE_CHANGE, function(evt, data) {
                layout();
            });
            cad.on("click", function() {
                wmks.sendCAD();
            });
            if (wmks.canFullScreen()) {
                fullscreen.on("click", function(evt) {
                    wmks.enterFullScreen();
                });
            } else {
                fullscreen.hide();
            }
            keyboard.on("click", function(evt) {
                var fixANSIEquivalentKeys = keyboard.data("toggle");
                var label = keyboard.html();
                wmks.setOption("fixANSIEquivalentKeys", !fixANSIEquivalentKeys);
                keyboard.html(keyboard.data("alt"));
                keyboard.data("toggle", !fixANSIEquivalentKeys);
                keyboard.data("alt", label);
            });
            relativepad.on("click", function(evt) {
                wmks.toggleRelativePad();
            });
            //listen for window events
            $(window).on("resize", layout);
            wmks.connect("<?php echo $http; ?>://<?php echo $consoleHost; ?>:<?php echo $port; ?>/ticket/<?php echo $ticket; ?>?host=<?php echo $host;?>");
            layout();
            spinner.show();
        </script>
        <!--</div>-->
    </body>
    </html>
<?php
} ?>