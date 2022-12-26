<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class SoapClient_BW extends \SoapClient
{

    function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $request = $this->appendXsiTypeForExtendedDatastructures($request);

        $headers = parent::__getLastResponseHeaders();
        $arHeaders = explode(PHP_EOL, $headers);
        foreach ($arHeaders as $value) {
            $val = explode(':', $value);
            $arHeaders[strtolower($val[0])] = ($val[1]) ? trim($val[1]) : '';
        }

        if (!empty($arHeaders['set-cookie'])) {
            $re = '/(.+)="(.+)";/m';
            preg_match_all($re, $arHeaders['set-cookie'], $matches, PREG_SET_ORDER, 0);
            parent::__setCookie('vmware_soap_session', '"' . $matches[0][2] . '"');
        }

        $result = parent::__doRequest($request, $location, $action, $version, $one_way);

        if (isset($this->__soap_fault) && $this->__soap_fault) {
            throw $this->__soap_fault;
        }
        return $result;
    }

    /* PHP does not provide inheritance information for wsdl types so we have to specify that its and xsi:type

     * php bug #45404

     * */

    private function appendXsiTypeForExtendedDatastructures($request)
    {
        return $request = str_replace(array("xsi:type=\"ns1:TraversalSpec\"", '<ns1:selectSet />'), array("xsi:type=\"ns1:TraversalSpec\"", ''), $request);
    }
}

global $whmcs;

$type = $whmcs->get_req_var("type");

$dbQuery = Capsule::table('tblhosting')->select('regdate', 'packageid')->where('id', $params['serviceid'])->get();

$getCustomFieldId = Capsule::table('tblcustomfields')->where('type', 'product')->where('relid', $dbQuery[0]->packageid)->where('fieldname', 'like', '%vm_name%')->first();

$getCustomFieldVal = Capsule::table('tblcustomfieldsvalues')->select('value')->where('fieldid', $getCustomFieldId->id)->where('relid', $params['serviceid'])->first();

$vm_name = $getCustomFieldVal->value;

if ($type == '0') {

    $intervalId = '300';
} else {

    $intervalId = '7200';

    $startDate = $dbQuery[0]->regdate;
}

function vmwareTimestampToIso8601($timestamp, $utc = true)
{

    $datestr = date('Y-m-d\TH:i:sO', $timestamp);

    if ($utc) {

        $eregStr = '/([0-9]{4})-' .
            '([0-9]{2})-' .
            '([0-9]{2})' .
            'T' .
            '([0-9]{2}):' .
            '([0-9]{2}):' .
            '([0-9]{2})(\.[0-9]*)?' .
            '(Z|[+\-][0-9]{2}:?[0-9]{2})?/';



        if (preg_match($eregStr, $datestr, $regs)) {

            return sprintf('%04d-%02d-%02dT%02d:%02d:%02dZ', $regs[1], $regs[2], $regs[3], $regs[4], $regs[5], $regs[6]);
        }

        return false;
    } else {

        return $datestr;
    }
}

try {



    $context = stream_context_create(array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    ));

    $serverData = Capsule::table('mod_vmware_server')->where('server_name', $ApiServerName)->get();
    if (count($serverData) == 0)
        $serverData = Capsule::table('mod_vmware_server')->where('id', $ApiServerName)->get();
    $getip = explode('://', $serverData[0]->vsphereip);
    $client = new SoapClient_BW($serverData[0]->vsphereip . "/sdk/vimService.wsdl", array("location" => $serverData[0]->vsphereip . "/sdk/", 'trace' => 1, 'stream_context' => $context));
    $soap_message["_this"] = new SoapVar("ServiceInstance", XSD_STRING, "ServiceInstance");

    $result = $client->RetrieveServiceContent($soap_message);

    $service_instance = $result->returnval;

    $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData[0]->vspherepassword);

    $soap_message["_this"] = $service_instance->sessionManager;

    $soap_message["userName"] = $serverData[0]->vsphereusername;

    $soap_message["password"] = $decryptPw;

    $result = $client->Login($soap_message);

    $soap_session = $result->returnval;


    $soapmsg['_this'] = $service_instance->propertyCollector;

    $soapmsg['specSet']['propSet']['type'] = 'VirtualMachine';

    $soapmsg['specSet']['propSet']['all'] = '0';

    $soapmsg['specSet']['propSet']['pathSet'] = array('name', 'config.hardware');

    $soapmsg['specSet']['objectSet']['obj'] = $service_instance->rootFolder;

    $soapmsg['specSet']['objectSet']['skip'] = false;

    $ss1 = new soapvar(array('name' => 'DataCenterVMTraversalSpec'), SOAP_ENC_OBJECT, null, null, 'selectSet', null);

    $ss2 = new soapvar(array('name' => 'FolderTraversalSpec'), SOAP_ENC_OBJECT, null, null, 'selectSet', null);

    $a = array('name' => 'FolderTraversalSpec', 'type' => 'Folder', 'path' => 'childEntity', 'skip' => false, $ss1, $ss2);

    $ss = new soapvar(array('name' => 'FolderTraversalSpec'), SOAP_ENC_OBJECT, null, null, 'selectSet', null);

    $b = array('name' => 'DataCenterVMTraversalSpec', 'type' => 'Datacenter', 'path' => 'vmFolder', 'skip' => false, $ss);

    $traversalSpec = new SoapVar($a, SOAP_ENC_OBJECT, 'TraversalSpec');

    $traversalSpec02 = new SoapVar($b, SOAP_ENC_OBJECT, 'TraversalSpec');

    $soapmsg['specSet']['objectSet']['selectSet'] = array($traversalSpec, $traversalSpec02);

    $aryVMHardwareDevices = $client->RetrieveProperties($soapmsg);



    $vm_name = $getCustomFieldVal->value;

    $vmID = '';

    $intNetworkCardID = '';

    foreach ($aryVMHardwareDevices->returnval as $vmInfo05) {

        if (urldecode($vmInfo05->propSet[1]->val) == $vm_name) {

            $vmID = $vmInfo05->obj->_;

            $aryVMHardwareDevices = $vmInfo05->propSet[0]->val->device;



            foreach ($aryVMHardwareDevices as $vmDevice) {

                if (isset($vmDevice->macAddress)) {

                    $intNetworkCardID = $vmDevice->key;
                }
            }

            break;
        }
    }



    $soapmsg['_this'] = $service_instance->perfManager;

    $soapmsg['level'] = 1;

    $perfResults = $client->QueryPerfCounterByLevel($soapmsg);



    $netcounterId = '';

    $cpuCounterId = '';

    $memoryCounterId = '';

    $i = 0;



    foreach ($perfResults->returnval as $perfResults) {

        if (strtolower($perfResults->groupInfo->key) == 'net') {

            $netcounterId = $perfResults->key;
        }

        if (strtolower($perfResults->groupInfo->key) == 'cpu' && strtolower($perfResults->nameInfo->key) == 'usage') {

            if ($perfResults->unitInfo->key == 'percent')
                $cpuPercentCounterId = $perfResults->key;

            if ($perfResults->unitInfo->key == 'megaHertz')
                $cpuCounterId = $perfResults->key;
        }

        if (strtolower($perfResults->groupInfo->key) == 'mem' && strtolower($perfResults->nameInfo->key) == 'usage') {

            if ($perfResults->unitInfo->key == 'percent')
                $memoryPercentCounterId = $perfResults->key;

            if ($perfResults->unitInfo->key == 'kiloBytes')
                $memoryCounterId = $perfResults->key;
        }

        $i++;
    }



    $soapmsg['_this'] = $service_instance->perfManager;

    $soapmsg['querySpec']['entity'] = array('_' => $vmID, 'type' => 'VirtualMachine');



    $currentDate = date('Y-m-d H:i:s');

    $oneDayBackDate = date('Y-m-d H:i:s', strtotime('-1 Day' . $currentDate));



    if ($type == '1') {

        $soapmsg['querySpec']['startTime'] = vmwareTimestampToIso8601(strtotime($startDate));

        $soapmsg['querySpec']['endTime'] = vmwareTimestampToIso8601(strtotime(date('Y-m-d')));
    } else {

        $soapmsg['querySpec']['startTime'] = vmwareTimestampToIso8601(strtotime($oneDayBackDate));

        $soapmsg['querySpec']['endTime'] = vmwareTimestampToIso8601(strtotime($currentDate));
    }

    $soapmsg['querySpec']['metricId']['counterId'] = $netcounterId;

    $soapmsg['querySpec']['metricId']['instance'] = '';

    $soapmsg['querySpec']['intervalId'] = $intervalId;



    $soapmsg['querySpec']['format'] = 'normal';



    $usageDatas = $client->QueryPerf($soapmsg);



    $netData = '';

    if (isset($usageDatas->returnval->sampleInfo)) {

        foreach ($usageDatas->returnval->sampleInfo as $key => $usageData) {

            $newtime01 = date('Y-m-d H:i:s', strtotime($usageData->timestamp));

            $newtime = date('Y,m,d,H,i,s', strtotime($newtime01 . ' -1 months'));



            $datevalue2 = 'Date.UTC(' . $newtime . ')';

            $datavalue = $usageDatas->returnval->value->value;

            $netData .= "[" . $datevalue2 . ", " . $datavalue[$key] / 125 . "],";
        }

        $netData = rtrim($netData, ",");
    } else {

        $netData = '<font color="red">Network Usage data not available.</font>';
    }



    $soapmsg['_this'] = $service_instance->perfManager;

    $soapmsg['querySpec']['entity'] = array('_' => $vmID, 'type' => 'VirtualMachine');



    if ($type == '1') {

        $soapmsg['querySpec']['startTime'] = vmwareTimestampToIso8601(strtotime($startDate));

        $soapmsg['querySpec']['endTime'] = vmwareTimestampToIso8601(strtotime(date('Y-m-d')));
    } else {

        $soapmsg['querySpec']['startTime'] = vmwareTimestampToIso8601(strtotime($oneDayBackDate));

        $soapmsg['querySpec']['endTime'] = vmwareTimestampToIso8601(strtotime($currentDate));
    }



    $soapmsg['querySpec']['metricId']['counterId'] = $cpuPercentCounterId;

    $soapmsg['querySpec']['metricId']['instance'] = "*";

    $soapmsg['querySpec']['intervalId'] = $intervalId;

    $soapmsg['querySpec']['format'] = 'normal';

    $cpu_usageDatas = $client->QueryPerf($soapmsg);



    $cpuData = '';

    if (isset($cpu_usageDatas->returnval->sampleInfo)) {

        foreach ($cpu_usageDatas->returnval->sampleInfo as $key => $cpu_usageData) {

            $cpu_newtime01 = date('Y-m-d H:i:s', strtotime($cpu_usageData->timestamp));

            $cpu_newtime = date('Y,m,d,H,i,s', strtotime($cpu_newtime01 . ' -1 months'));

            $cpu_datevalue2 = 'Date.UTC(' . $cpu_newtime . ')';

            $cpu_datavalue = $cpu_usageDatas->returnval->value->value;

            $cpuData .= "[" . $cpu_datevalue2 . ", " . $cpu_datavalue[$key] / 100 . "],";
        }

        $cpuData = rtrim($cpuData, ",");
    } else {

        $cpuData = '<font color="red">CPU Usage data not found.</font>';
    }



    $soapmsg['_this'] = $service_instance->perfManager;

    $soapmsg['querySpec']['entity'] = array('_' => $vmID, 'type' => 'VirtualMachine');

    if ($type == '1') {

        $soapmsg['querySpec']['startTime'] = vmwareTimestampToIso8601(strtotime($startDate));

        $soapmsg['querySpec']['endTime'] = vmwareTimestampToIso8601(strtotime(date('Y-m-d')));
    } else {

        $soapmsg['querySpec']['startTime'] = vmwareTimestampToIso8601(strtotime($oneDayBackDate));

        $soapmsg['querySpec']['endTime'] = vmwareTimestampToIso8601(strtotime($currentDate));
    }

    $soapmsg['querySpec']['metricId']['counterId'] = $memoryPercentCounterId;

    $soapmsg['querySpec']['metricId']['instance'] = "*";

    $soapmsg['querySpec']['intervalId'] = $intervalId;



    $soapmsg['querySpec']['format'] = 'normal';

    $memory_usageDatas = $client->QueryPerf($soapmsg);



    $memoryData = '';

    if (isset($memory_usageDatas->returnval->sampleInfo)) {

        foreach ($memory_usageDatas->returnval->sampleInfo as $key => $memory_usageData) {

            $memory_newtime01 = date('Y-m-d H:i:s', strtotime($memory_usageData->timestamp));

            $memory_newtime = date('Y,m,d,H,i,s', strtotime($memory_newtime01 . ' -1 months'));

            $memory_datevalue2 = 'Date.UTC(' . $memory_newtime . ')';

            $memory_datavalue = $memory_usageDatas->returnval->value->value;

            $memoryData .= "[" . $memory_datevalue2 . ", " . $memory_datavalue[$key] / 100 . "],";
        }

        $memoryData = rtrim($memoryData, ",");
    } else {

        $memoryData = '<font color="red">Memory Usage data not available.</font>';
    }
} catch (Exception $e) {

    if ($e->getMessage() == 'A specified parameter was not correct: querySpec.startTime' || $e->getMessage() == 'A specified parameter was not correct: querySpec.startTime, querySpec.endTime') {

        echo '<font color="red">Data not available.</font>';
    } else {

        echo '<font color="red">' . $e->getMessage() . '.</font>';
    }

    exit;
}
?>



<div class="tab-pane active ">

    <h4 style="text-align:left;">Network Usage</h4>

    <div id="container2<?php echo $type; ?>" style="width:100%; height:400px;"></div>

</div>



<?php if ($netData != '<font color="red">Network Usage data not available.</font>' && $netData != '') { ?>

    <script>
        jQuery(function() {

            jQuery('#container2<?php echo $type; ?>').highcharts({

                chart: {
                    zoomType: 'x'
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: 'Network Usage'
                },

                xAxis: {
                    type: 'datetime',
                    tickPixelInterval: 250,
                    dateTimeLabelFormats: {
                        day: '%e of %b'
                    }
                },

                yAxis: {
                    title: {
                        text: 'Mbps'
                    }
                },
                legend: {
                    enabled: false
                },

                plotOptions: {

                    area: {

                        fillColor: {

                            linearGradient: {
                                x1: 0,
                                y1: 0,
                                x2: 0,
                                y2: 1
                            },

                            stops: [

                                [0, Highcharts.getOptions().colors[0]],

                                [1, Highcharts.getOptions().colors[0]]

                            ]

                        },

                        marker: {

                            radius: 2

                        },

                        lineWidth: 1,

                        states: {

                            hover: {

                                lineWidth: 1

                            }

                        },

                        threshold: null

                    }

                },

                series: [{

                    type: 'area',

                    name: 'Mbps',

                    data: [<?php echo $netData; ?>],

                }]

            });



        });
    </script>

<?php } else { ?>

    <script>
        jQuery('#container2<?php echo $type; ?>').html('<?php echo $netData; ?>');

        jQuery('#container2<?php echo $type; ?>').css('height', '30px');
    </script>

<?php } ?>



<div class="tab-pane active ">

    <h4 style="text-align:left;">CPU Usage (%)</h4>

    <div id="container3<?php echo $type; ?>" style="width:100%; height:400px;"></div>

</div>

<?php if ($cpuData != '<font color="red">CPU Usage data not found.</font>' && $cpuData != '') { ?>

    <script>
        jQuery(function() {

            jQuery('#container3<?php echo $type; ?>').highcharts({

                chart: {
                    zoomType: 'x'
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: 'CPU Usage'
                },

                xAxis: {
                    type: 'datetime',
                    tickPixelInterval: 250,
                    dateTimeLabelFormats: {
                        day: '%e of %b'
                    }
                },

                yAxis: {
                    title: {
                        text: 'Percent'
                    }
                },
                legend: {
                    enabled: false
                },

                plotOptions: {

                    area: {

                        fillColor: {

                            linearGradient: {
                                x1: 0,
                                y1: 0,
                                x2: 0,
                                y2: 1
                            },

                            stops: [

                                [0, Highcharts.getOptions().colors[0]],

                                [1, Highcharts.getOptions().colors[0]]

                            ]

                        },

                        marker: {

                            radius: 2

                        },

                        lineWidth: 1,

                        states: {

                            hover: {

                                lineWidth: 1

                            }

                        },

                        threshold: null

                    }

                },

                series: [{

                    type: 'area',

                    name: 'Percent',

                    data: [<?php echo $cpuData; ?>],

                }]

            });



        });
    </script>

<?php } else { ?>

    <script>
        jQuery('#container3<?php echo $type; ?>').html('<?php echo $cpuData; ?>');

        jQuery('#container3<?php echo $type; ?>').css('height', '30px');
    </script>

<?php } ?>

<div class="tab-pane active ">

    <h4 style="text-align:left;">Memory Usage (%)</h4>

    <div id="container4<?php echo $type; ?>" style="width:100%; height:400px;"></div>

</div>

<?php if ($memoryData != '<font color="red">Memory Usage data not available.</font>' && $memoryData != '') { ?>

    <script>
        jQuery(function() {

            jQuery('#container4<?php echo $type; ?>').highcharts({

                chart: {
                    zoomType: 'x'
                },
                title: {
                    text: ''
                },
                subtitle: {
                    text: 'Memory Usage'
                },

                xAxis: {
                    type: 'datetime',
                    tickPixelInterval: 250,
                    dateTimeLabelFormats: {
                        day: '%e of %b'
                    }
                },

                yAxis: {
                    title: {
                        text: 'Percent'
                    }
                },
                legend: {
                    enabled: false
                },

                plotOptions: {

                    area: {

                        fillColor: {

                            linearGradient: {
                                x1: 0,
                                y1: 0,
                                x2: 0,
                                y2: 1
                            },

                            stops: [

                                [0, Highcharts.getOptions().colors[0]],

                                [1, Highcharts.getOptions().colors[0]]

                            ]

                        },

                        marker: {

                            radius: 2

                        },

                        lineWidth: 1,

                        states: {

                            hover: {

                                lineWidth: 1

                            }

                        },

                        threshold: null

                    }

                },

                series: [{

                    type: 'area',

                    name: 'Percent',

                    data: [<?php echo $memoryData; ?>],

                }]

            });



        });
    </script>

<?php } else { ?>

    <script>
        jQuery('#container4<?php echo $type; ?>').html('<?php echo $memoryData; ?>');

        jQuery('#container4<?php echo $type; ?>').css('height', '30px');
    </script>

<?php }
?>