<?php



use Illuminate\Database\Capsule\Manager as Capsule;



global $whmcs;


class SoapClient_Addon extends \SoapClient
{



    function __doRequest($request, $location, $action, $version, $one_way = 0)
    {

        $request = $this->AddonappendXsiTypeForExtendedDatastructures($request);

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

    private function AddonappendXsiTypeForExtendedDatastructures($request)
    {

        return $request = str_replace(array("xsi:type=\"ns1:TraversalSpec\"", '<ns1:selectSet />'), array("xsi:type=\"ns1:TraversalSpec\"", ''), $request);
    }
}

$dbQuery = Capsule::table('tblhosting')->select('regdate', 'packageid')->where('id', $whmcs->get_req_var('relid'))->first();

$intervalId = '7200'; //86400//7200//1800//300

if (!empty($_POST['timerange']))

    $intervalId = $whmcs->get_req_var('timerange');

//$startDate = $dbQuery->regdate;

$strStartTime = date('Y-m-d 00:00:01', strtotime('first day of this month'));

if (!empty($_POST['timerange'])) {

    if ($intervalId == 300)

        $strStartTime = date("Y-m-d 00:00:01", strtotime("-1 day", strtotime(date("Y-m-d H:i:s"))));

    elseif ($intervalId == 1800)

        $strStartTime = date("Y-m-d 00:00:01", strtotime("-7 days", strtotime(date("Y-m-d H:i:s"))));

    elseif ($intervalId == 1800)

        $strStartTime = date("Y-m-d 00:00:01", strtotime("-30 days", strtotime(date("Y-m-d H:i:s"))));

    elseif ($intervalId == 86400)

        $strStartTime = date("Y-m-d 00:00:01", strtotime("-1 year", strtotime(date("Y-m-d H:i:s"))));
}

try {

    $context = stream_context_create(array(

        'ssl' => array(

            'verify_peer' => false,

            'verify_peer_name' => false,

            'allow_self_signed' => true

        )

    ));

    $serverData = Capsule::table('mod_vmware_server')->where('id', $whmcs->get_req_var('sid'))->first();

    $getip = explode('://', $serverData->vsphereip);



    $client = new SoapClient_Addon($serverData->vsphereip . "/sdk/vimService.wsdl", array("location" => $serverData->vsphereip . "/sdk/", 'trace' => 1, 'stream_context' => $context));



    $soap_message["_this"] = new SoapVar("ServiceInstance", XSD_STRING, "ServiceInstance");

    $result = $client->RetrieveServiceContent($soap_message);

    $service_instance = $result->returnval;

    $decryptPw = $WgsVmwareObj->wgsVmwarePwEncryptDcrypt($serverData->vspherepassword);

    $soap_message["_this"] = $service_instance->sessionManager;

    $soap_message["userName"] = $serverData->vsphereusername;

    $soap_message["password"] = $decryptPw;

    $result = $client->Login($soap_message);

    $soap_session = $result->returnval;





    $soapmsg['_this'] = $service_instance->propertyCollector;

    $soapmsg['specSet']['propSet']['type'] = 'VirtualMachine';

    // $soapmsg['specSet']['propSet']['all'] = '0';

    $soapmsg['specSet']['propSet']['pathSet'] = array('name', 'config.hardware');

    $soapmsg['specSet']['objectSet']['obj'] = array("_" => $whmcs->get_req_var('vmid'), "type" => 'VirtualMachine'); //$service_instance->rootFolder;

    $aryVMHardwareDevices = $client->RetrieveProperties($soapmsg);

    $intNetworkCardID = '';

    foreach ($aryVMHardwareDevices->returnval->propSet->val->device as $vmDevice) {

        if (isset($vmDevice->macAddress)) {

            $intNetworkCardID = $vmDevice->key;
        }
    }

    unset($soapmsg);

    $soapmsg['_this'] = $service_instance->perfManager;

    $soapmsg['level'] = 1; //1//2

    $perfResults = $client->QueryPerfCounterByLevel($soapmsg);



    $netcounterId = '';

    $i = 0;



    foreach ($perfResults->returnval as $perfResults) {

        //        echo $perfResults->nameInfo->key . '---' . $perfResults->groupInfo->key . '---' . $perfResults->key . '--' . $perfResults->rollupType;

        //        echo '<br/>';

        //usage//received//transmitted

        if (strtolower($perfResults->nameInfo->key) == 'usage' && $perfResults->groupInfo->key == 'net') {

            $netcounterId = $perfResults->key;
        }

        $i++;
    }

    unset($soapmsg);



    $strEndTime = date("Y-m-d 23:59:59", strtotime("-1 day", strtotime(date("Y-m-d H:i:s"))));

    //    $strStartTime = date("Y-m-d 00:00:01", strtotime("-1 day", strtotime(date("Y-m-d H:i:s"))));

    $soapmsg['_this'] = $service_instance->perfManager;

    $soapmsg['entity'] = array('_' => $whmcs->get_req_var('vmid'), 'type' => 'VirtualMachine');

    $soapmsg['beginTime'] = vmwareTimestampToIso8601Ajax(strtotime($strStartTime));

    $soapmsg['endTime'] = vmwareTimestampToIso8601Ajax(strtotime($strEndTime));

    $soapmsg['intervalId'] = (int) $intervalId;

    $aryAvailableCounters = $client->QueryAvailablePerfMetric($soapmsg);

    $intCounterAvailable = 0;

    foreach ($aryAvailableCounters->returnval as $counter) {

        if ($counter->counterId == (int) $netcounterId) {

            $intCounterAvailable = 1;

            break;
        }
    }

    if ($intCounterAvailable) {

        unset($soapmsg);

        $soapmsg['_this'] = $service_instance->perfManager;

        $soapmsg['querySpec']['entity'] = array('_' => $whmcs->get_req_var('vmid'), 'type' => 'VirtualMachine');

        $soapmsg['querySpec']['startTime'] = vmwareTimestampToIso8601Ajax(strtotime($strStartTime));

        $soapmsg['querySpec']['endTime'] = vmwareTimestampToIso8601Ajax(strtotime($strEndTime));

        $soapmsg['querySpec']['metricId']['counterId'] = (int) $netcounterId;

        $soapmsg['querySpec']['metricId']['instance'] = (string) $intNetworkCardID;

        $soapmsg['querySpec']['intervalId'] = (int) $intervalId;

        $soapmsg['querySpec']['format'] = 'normal';

        $usageDatas = $client->QueryPerf($soapmsg);



        $netData = 0;

        if (isset($usageDatas->returnval->sampleInfo)) {

            foreach ($usageDatas->returnval->sampleInfo as $key => $usageData) {

                $datavalue = $usageDatas->returnval->value->value;

                $netData = $netData + $datavalue[$key] / 125;
            }

            echo $netData . ' Mbps';
        } else {

            echo $netData = '<font color="red">Network received data not available.</font>';
        }
    } else {

        echo $netData = '<font color="red">Network received data not available.</font>';
    }
} catch (Exception $ex) {

    echo '<font color="red">' . $ex->getMessage() . '</font>';
}



function vmwareTimestampToIso8601Ajax($timestamp, $utc = true)
{

    $datestr = date("Y-m-d\\TH:i:sO", $timestamp);

    $pos = strrpos($datestr, "+");

    if ($pos === false) {

        $pos = strrpos($datestr, "-");
    }

    if ($pos !== false && strlen($datestr) == $pos + 5) {

        $datestr = substr($datestr, 0, $pos + 3) . ":" . substr($datestr, -2);
    }

    if ($utc) {

        $pattern = "/" . "([0-9]{4})-" . "([0-9]{2})-" . "([0-9]{2})" . "T" . "([0-9]{2}):" . "([0-9]{2}):" . "([0-9]{2})(\\.[0-9]*)?" . "(Z|[+\\-][0-9]{2}:?[0-9]{2})?" . "/";

        if (preg_match($pattern, $datestr, $regs)) {

            return sprintf("%04d-%02d-%02dT%02d:%02d:%02dZ", $regs[1], $regs[2], $regs[3], $regs[4], $regs[5], $regs[6]);
        }

        return false;
    }

    return $datestr;
}
