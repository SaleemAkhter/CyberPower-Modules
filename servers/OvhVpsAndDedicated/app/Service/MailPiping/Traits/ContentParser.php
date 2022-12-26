<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\MailPiping\Traits;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Servers\Vps;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\ServiceManager;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Api\Ovh;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Managers\OvhAccountOrder;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh\MailBoxRead;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh\Orders;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\CustomFields\CustomFields;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\CustomField;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\CustomFieldValue;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\Hosting;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\ConfigOptions;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated\Repository as DedicatedRepository;

/**
 * Description of ImapReader
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
//TODO PRZEROBIc tą KASZANE bo maskara

trait ContentParser
{
    protected $orders;

    protected $mailAction;

    private function readMails()
    {


        //$this->setOrders();

        if (is_array($this->imapHeader) === false)
        {
            echo sprintf("No emails from date:  %s \r\n",$this->date);
            return $this;
        }

        foreach ($this->imapHeader as $mailId)
        {
            if (!empty($this->checkEmailExistOnSystem($mailId)))
            {

                continue;
            }

            try
            {

                $hostingID = $this->assignMailToAccount($mailId);
                if (!$hostingID)
                {
                    continue;
                }

                $this->saveDetailsInDB($mailId, 'success', 'Email has been forwarded.', $hostingID);
            }
            catch (Exception $ex)
            {
                if(!preg_match("/There is no credentials with given mail/", $ex->getMessage())){
                    $this->saveDetailsInDB($mailId, 'error', $ex->getMessage());
                }
            }
        }

        return $this;
    }

    private function checkEmailExistOnSystem($mailID)
    {
        return MailBoxRead::whereMailID($mailID)->whereMail($this->imapConfig->username)->whereProductId($this->currentProduct->id)->first();
    }

    private function saveDetailsInDB($mailID, $status, $msg, $hostingID = 0)
    {
        $mailSaved             = new MailBoxRead();
        $mailSaved->mail_id    = $mailID;
        $mailSaved->product_id = $this->currentProduct->id;
        $mailSaved->status     = $status;
        $mailSaved->message    = $msg;
        $mailSaved->hosting_id = $hostingID;
        $mailSaved->mail       = $this->imapConfig->username;
        $mailSaved->save();
        echo ucfirst($status) . ": " . $msg . "\n";
    }

    private function getServiceIp(Hosting $service)
    {
        $params =  \ModuleBuildParams($service->id);
        Ovh::$api = null;
        $dedicatedRepo = new DedicatedRepository($params);
        $result = $dedicatedRepo->get($service->serviceName)->model();

        return $result->getIp();
    }

    private function getFieldsConfigurationFromBodyAndSave(Hosting $service, $mailBody, $mailHeader)
    {
        if (!$this->isOVHService($service->serviceName))
        {
            $ip     = $this->getServiceIp($service);
            $body   = $this->getConfigFromMessage($mailBody, $ip);
            $header = $this->getConfigFromMessage($mailHeader, $ip);
        }
        else
        {
            $body   = $this->getConfigFromMessage($mailBody);
            $header = $this->getConfigFromMessage($mailHeader);
        }
        if (!empty($body))
        {
            $this->checkFieldsToUpdate($service, $body, $header, $mailHeader);
        }
    }

    private function getConfigFromMessage($message, $ip = false)
    {
        $body = strip_tags($message);
        preg_match_all("/[a-zA-Z ]+[:](.*)+/", $body, $output_array);

        $configArray = [];
        foreach ($output_array[0] as $item)
        {
            $conf = explode(":", $item);

            if ($ip && stripos($item, "://" . $ip) !== false)
            {
                $configArray['ip'] = $ip;
            }
            if (count($conf) > 2)
            {
                continue;
            }
            $configArray[strtolower(str_replace(' ', '', trim($conf[0])))] = str_replace(' ', '', trim($conf[1]));
        }

        return $configArray;
    }

    //żenada jakas z tym ;p
    private function checkFieldsToUpdate($service, $configArray, $configHeaderArray, $originalHeader)
    {

        if ($this->isOVHService($service->serviceName))
        {
            $ovhTemplateHeader = $originalHeader->fullHeaders->{'X-Ovh-Template'};
            $credentialsConfig = $this->getCredentialsConfig($service, $configArray, $configHeaderArray);

            if ($this->mailAction != 'create')
            {
                if (stripos($ovhTemplateHeader, 'rescue') !== false)
                {
                    $this->mailAction = 'rescue';
                }
                else
                {
                    $this->mailAction = 'reinstall';
                }
            }
        }
        else
        {
            $ovhTemplateHeader = $originalHeader->fullHeaders->{'X-Ovh-Template'};
            $credentialsConfig = $this->getCredentialsConfigDedicated($service, $configArray, $configHeaderArray);
            if ($this->mailAction != 'create')
            {
                if (stripos($ovhTemplateHeader, 'rescue') !== false)
                {
                    $this->mailAction = 'rescue';
                }
                else
                {
                    $this->mailAction = 'reinstall';
                }
            }
        }
        if (!$credentialsConfig['username'] && !$credentialsConfig['password'])
        {
            throw new Exception('There is no credentials with given mail');
        }

        $dedicatedConfig = $this->searchForDedicatedMessage($originalHeader->fullHeaders->{'X-Ovh-Template'});

        if ($this->isOVHService($service->serviceName))
        {
            unset($dedicatedConfig);
        }

        if (!empty($dedicatedConfig))
        {
            $this->updateAccountInformation($service, $credentialsConfig);
            $this->sendDedicatedEmailMessage($service, $credentialsConfig, $dedicatedConfig);
        }
        else
        {
            $this->updateAccountInformation($service, $credentialsConfig);
            $this->sendEmailMessage($service, $credentialsConfig);
        }
    }

    private function searchForDedicatedMessage($message)
    {

        if (stripos($message, 'rescue'))
        {
            return [
                'action' => 'reboot',
                'params' => [
                    'rebootMode' => 'rescue'
                ],
            ];
        }

        if (stripos($message, 'hardReboot'))
        {
            return [
                'action' => 'reboot',
                'params' => [
                    'rebootMode' => 'hard'
                ],
            ];
        }

        if (stripos($message, 'livraison_') !== false)
        {
            return ['action' => 'reinstall'];
        }
    }

    private function sendDedicatedEmailMessage($service, $credentialsConfig, $dedicatedConfig)
    {
        $config = $this->getConfig($service->packageid);

        $basicParams = [
            "service_name" => (string)$service->serviceName,
            "username"     => (string)$credentialsConfig['username'],
            "password"     => (string)$credentialsConfig['password']
        ];


        $messageName = '';
        if ($dedicatedConfig['action'] == 'reboot')
        {
            $messageName = $config->getField('rebootEmailTemplate', 'Ovh Dedicated Server Reboot');

        }
        else
        {
            if ($dedicatedConfig['action'] == 'reinstall')
            {
                $messageName = $config->getField('reinstallEmailTemplate', 'Ovh Dedicated Reinstall Email');
            }
        }

        $basicParams += $dedicatedConfig['params'] ? $dedicatedConfig['params'] : [];

        $postData = [
            'id'          => $service->id,
            'messagename' => $messageName,
            'customvars'  => base64_encode(serialize(
                $basicParams
            )),
        ];

        $result = \localAPI('SendEmail', $postData);

        if ($result['result'] != "success")
        {
            throw new Exception('Email cannot be sent. Hositng ID #' . $service->id);
        }

    }


    private function assignMailToAccount($mailId)
    {

        $mailBody   = $this->readBody($mailId);
        $mailHeader = $this->readHederInfo($mailId);
        echo  sprintf("Reading: %s \r\n", $mailHeader->subject);
        $template = $mailHeader->fullHeaders->{'X-Ovh-Template'};
        $serverMachineName = $this->getServiceMachineNameFromSubject($mailHeader);

        $service = $this->findServiceForVps($serverMachineName);

        if ($this->orders[$serverMachineName] && stripos($template, 'rescue') === false) //CREATE
        {
            $serviceId = $this->orders[$serverMachineName]['hosting_id'];
            CustomFields::set($serviceId, ConfigOptions::OVH_SERVER_FIELD, $serverMachineName);
            Orders::destroy($this->orders[$serverMachineName]['id']);
            $this->mailAction = 'create';
        }

        $this->getFieldsConfigurationFromBodyAndSave($service, $mailBody, $mailHeader);
        return $service->id;
    }

    private function getServiceMachineNameFromSubject($mailHeader)
    {
        if (!empty($mailHeader->subject))
        {
            $vpsName = $this->findVpsName($mailHeader->subject);
            if (empty($vpsName))
            {
                throw new Exception(sprintf("Message: '%s' not from Ovh",$mailHeader->subject ));
            }
            return $vpsName;
        }
        throw new Exception('Message with an empty subject');
    }

    private function sendEmailMessage(Hosting $service, $machineConfig)
    {
        $config = $this->getConfig($service->packageid);
        switch ($this->mailAction)
        {
            case 'create':
                $postData = [
                    'id'          => $service->id,
                    'messagename' => $config->getField('createEmailTemplate', 'OVH VPS Has Been Created'),
                    'customvars'  => base64_encode(serialize([
                        "service_name" => $service->serviceName,
                        "username"     => $machineConfig['username'],
                        "password"     => $machineConfig['password']
                    ])),
                ];
                $vpsAccount = new Vps($this->getWhmcsServiceClient($service->id));
                $vpsAccount->assignDomainAndIpToService();
                break;
            case 'rescue':
                $postData = [
                    'id'          => $service->id,
                    'messagename' => $config->getField('rescueEmailTemplate', 'OVH VPS Rescue Reboot'),
                    'customvars'  => base64_encode(serialize([
                        "service_name" => $service->serviceName,
                        "username"     => $machineConfig['username'],
                        "password"     => $machineConfig['password']
                    ])),
                ];
                break;
            case 'reinstall':
                $postData = [
                    'id'          => $service->id,
                    'messagename' => $config->getField('reinstallEmailTemplate', 'OVH VPS Reinstalled'),
                    'customvars'  => base64_encode(serialize([
                        "service_name" => $service->serviceName,
                        "username"     => $machineConfig['username'],
                        "password"     => $machineConfig['password']
                    ])),
                ];
                break;
        }

        if (empty($postData))
        {
            Helper\errorLog('vpsEmailActionNotFound', $service->toArray());
            throw new Exception('Email cannot be sent. Mail action is not specified. Hosting ID #' . $service->id);
        }

        $result = localAPI('SendEmail', $postData);
        if ($result['result'] != "success")
        {
            throw new Exception('Email cannot be sent. Hositng ID #' . $service->id);
        }
    }


    private function getConfig($id)
    {
        return new \ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider($id);
    }

    private function getCredentialsConfig($service, $configArray, $configHeaderArray)
    {
        $i            = 0;
        $ServiceIndex = -999;

        $updateFields = [];
        foreach ($configArray as $value)
        {
            if (in_array($value,['root' , 'arch', 'debian', 'fedora', 'centos', 'ubuntu', 'openvpn'] ) )
            {
                $updateFields['username'] = trim($value);
                $ServiceIndex             = $i;
            }
            if ($ServiceIndex + 1 == $i)
            {
                $updateFields['password'] = trim($value);
                break;
            }
//            {
//                $updateFields['username'] = trim($value);
//            }
//            if ($value == $service->serviceName)
//            {
//                $ServiceIndex = $i;
//            }
//
//            if ($ServiceIndex + 2 == $i)
//            {
//                $updateFields['username'] = trim($value);
//            }
//            if ($ServiceIndex + 3 == $i)
//            {
//                $updateFields['password'] = trim($value);
//                break;
//            }
            $i++;
        }
        $updateFields['password'] = preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $updateFields['password']); //whitespaces

        return $updateFields;
    }

    private function getCredentialsConfigDedicated($service, $configArray, $configHeaderArray)
    {
        $i            = 0;
        $ServiceIndex = -999;

        $updateFields = [];
        foreach ($configArray as $value)
        {
            if ($value == 'root')
            {
                $updateFields['username'] = trim($value);
                $serviceIndex             = $i;
            }
            if ($serviceIndex && $serviceIndex + 1 == $i)
            {
                $updateFields['password'] = trim($value);
                break;
            }
            if ($value == $service->serviceName)
            {
                $serviceIndex2 = $i;
            }

            if ($serviceIndex2 && $serviceIndex2 + 2 == $i)
            {
                $updateFields['username'] = $value;
            }
            if ($serviceIndex2 && $serviceIndex2 + 3 == $i)
            {
                $updateFields['password'] = $value;
                break;
            }
            $i++;
        }

        return $updateFields;
    }

    private function findServiceForVps($serverMachineName)
    {
        $customFieldsValuesTable = (new CustomFieldValue())->getTable();
        $customFieldsTable       = (new CustomField())->getTable();
        $hostingTable            = (new Hosting())->getTable();

        $service = Hosting::select("{$hostingTable}.*", "{$customFieldsValuesTable}.value as serviceName")
            ->join($customFieldsValuesTable, "{$customFieldsValuesTable}.relid", '=', "{$hostingTable}.id")
            ->join($customFieldsTable, "{$customFieldsTable}.id", '=', "{$customFieldsValuesTable}.fieldid")
            ->where("{$customFieldsValuesTable}.value", '=', $serverMachineName)
            ->where("{$customFieldsTable}.type", '=', 'product')
            ->where("{$hostingTable}.packageid", '=', $this->currentProduct->id)
            ->first();


        if (!is_null($service))
        {
            return $service;
        }
        throw new \Exception('Service not found. Vps name: ' . $serverMachineName);
    }

    private function findVpsName($subject)
    {
        preg_match_all("#[^\[]+(?=\])#", $subject, $output);

        $output = $output[0];

        if (!empty($output[1]))
        {
            return $output[1];
        }
    }

    private function isOVHService($serviceName)
    {
        return (stripos($serviceName, '.ovh.') !== false);
    }

    private function updateAccountInformation(Hosting $service, $config)
    {
        if (is_array($config))
        {
            $service->update($config);
            $service->save();
        }
    }

    public function setOrders($orders)
    {
        $this->orders = $orders;
        return $this;
        $orders = Orders::all();
        $out = [];
        foreach ($orders as $order)
        {
            try {
                Ovh::$api = null;
                $client = $this->getWhmcsServiceClient($order->hosting_id);
                if ($client == null)
                {
                    continue;
                }
                $ovhAccountOrder = new OvhAccountOrder($client);
                $vpsName         = $ovhAccountOrder->getServiceNameFromOrder($order->order_id);
                if (stripos($vpsName, '*/001') !== false || $vpsName[0] == '*') //vps is not paid
                {
                    continue;
                }

                $out[$vpsName] = $order->toArray();
            }catch (\Exception $ex){
                echo "Error: " . $ex->getMessage() . "\n";
            }

        }
        $this->orders = $out;
    }

    protected function getWhmcsServiceClient($hostingId)
    {
        $params = ServiceManager::getWhmcsParamsByHostingId($hostingId);
        if(empty($params))
        {
            return null;
        }
        return new Client($params);
    }




}
