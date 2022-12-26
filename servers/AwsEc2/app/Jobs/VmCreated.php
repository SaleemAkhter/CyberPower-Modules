<?php

namespace ModulesGarden\Servers\AwsEc2\App\Jobs;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\Core\Models\Whmcs\Hosting;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\di;
use ModulesGarden\Servers\AwsEc2\Core\Queue\Job;
use ModulesGarden\Servers\AwsEc2\Core\Queue\Queue;
use ModulesGarden\Servers\AwsEc2\App\Helpers\EmailSender;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\App\Models\NetworkInterface;
class VmCreated extends Job
{
    protected $params;
    protected $productSettings;
    protected $pid = null;
    protected $sid = null;
    protected $instanceData = [];
    protected $ipv4Count = 0;
    protected $instanceId = null;
    protected $tags = null;
    protected $securityGroupIds = null;
    protected $hosting;

    protected $amiusernames=[
        'centos'=>'CentOS',
        'rocky'=>'Rocky',
        'Administrator'=>'Windows',
        'admin'=>'Debian',
        'ubuntu'=>'Ubuntu',
        'fedora'=>'Fedora',
        'ec2-user'=>'FreeBSD'
    ];


    public function handle($pid = null, $sid = null, $instanceId = null, $ipv4Count = 0, $tags = null, $securityGroupIds = null)
    {
        $this->pid = $pid;
        $this->sid = $sid;
        $this->instanceId = $instanceId;
        $this->securityGroupIds = $securityGroupIds;

        if ((int)$ipv4Count > 0)
        {
            $this->ipv4Count = (int)$ipv4Count;
        }

        if (is_array($tags))
        {
            $this->tags = $tags;
        }

        return $this->runTask();
    }

    public function runTask()
    {

        $this->model->increaseRetryCount();

        try
        {
            $this->loadApiConnection();

            return $this->runTaskActions();
        }
        catch (\Aws\Exception\AwsException $exc)
        {
            $errorMessage = $exc->getAwsErrorMessage();
        }
        catch (\Exception $exc)
        {
            $errorMessage = $exc->getMessage();
        }
        finally
        {
            if ($exc)
            {
                $this->log->error($errorMessage, ['pid' => $this->pid, 'sid' => $this->sid]);

                return $this->pospond(true);
            }
        }

        return true;
    }

    public function runTaskActions()
    {
        $this->loadRemoteInstanceData();
        if (!$this->isInstanceInProperState())
        {
            return $this->pospond();
        }

        $hosting    = Hosting::where('id', $this->sid)->first();

        if ($this->ipv4Count)
        {
            $this->addNetworkInterfacesV4();
        }
        else
        {
            $this->updateDedicateIp($this->instanceData['PublicIpAddress']);
        }
        $this->afterTaskActionProcess($hosting);
        return true;
    }

    protected function addNetworkInterfacesV4($newIpCount = null)
    {
        $defaultSubnet = $this->getDefaultSubnetForAz($this->instanceData['Placement']['AvailabilityZone']);

        $deviceIndex = count($this->instanceData['NetworkInterfaces']);
        $networkDevices = $this->instanceData['NetworkInterfaces'];

        $ipCount = (is_int($newIpCount) && $newIpCount >= 0) ? $newIpCount : $this->ipv4Count;
        while ($ipCount > 0)
        {
            $networkDevice = $this->getAmazonIpDevice($networkDevices);

            if (!$networkDevice)
            {
                $networkInterfaceParams = [
                    'Description' => 'WHMCS_SERVICE_' . $this->sid,
//                    'InterfaceType' => 'efa',
                    'SubnetId' => $defaultSubnet['SubnetId']
                ];

                if (is_array($this->securityGroupIds) && !empty($this->securityGroupIds))
                {
                    $networkInterfaceParams['Groups'] = $this->securityGroupIds;
                }

                $networkInterface = $this->apiConnection->createNetworkInterface($networkInterfaceParams);

                $this->createTags($networkInterface['NetworkInterfaceId']);

                try
                {
                    NetworkInterface::insert([
                        'service_id'=>$this->sid,
                        'allocation_id'=>$networkInterface['NetworkInterfaceId']
                    ]);
                    $attachmentId = $this->apiConnection->attachNetworkInterface([
                        'DeviceIndex' => $deviceIndex,
                        'InstanceId' => $this->instanceId,
                        'NetworkInterfaceId' => $networkInterface['NetworkInterfaceId']
                    ]);
                }
                catch (\Aws\Exception\AwsException $exc)
                {
                    //if interface was created but we were unable to assign it, just remove it
                    $this->apiConnection->deleteNetworkInterface($networkInterface['NetworkInterfaceId']);

                    throw new \Exception($exc->getAwsErrorMessage());
                }

                $deviceIndex++;
            }
            else
            {
                $networkInterface = $networkDevice;
                $this->createTags($networkInterface['NetworkInterfaceId']);
                $exists=NetworkInterface::where([
                        'service_id'=>$this->sid,
                        'allocation_id'=>$networkInterface['NetworkInterfaceId']
                    ])->exists();
                if(!$exists){
                    NetworkInterface::insert([
                        'service_id'=>$this->sid,
                        'allocation_id'=>$networkInterface['NetworkInterfaceId']
                    ]);
                }

            }

            $ip = $this->apiConnection->allocateAddress([]);
            $ipDetails = $ip->toArray();

            $this->updateDedicateIp($ipDetails['PublicIp'], false);


            $this->createTags($ipDetails['AllocationId']);

            $this->apiConnection->associateAddress([
                'AllocationId' => $ipDetails['AllocationId'],
                'NetworkInterfaceId' => $networkInterface['NetworkInterfaceId']
            ]);

            $ipCount--;
        }
    }

    public function getAmazonIpDevice(&$devices)
    {
        foreach ($devices as $key => $device)
        {
            if ($device['Association']['IpOwnerId'] === 'amazon' || !$device['Association'])
            {
                unset($devices[$key]);

                return $device;
            }
        }

        return false;
    }

    public function isInstanceInProperState($state = null)
    {
        if (in_array($this->instanceData['State']['Name'], ['running', 'stopped']))
        {
            return true;
        }

        return false;
    }

    public function loadRemoteInstanceData()
    {
        $instancesData = $this->apiConnection->describeInstances([
            'InstanceIds' => [$this->instanceId]
        ]);

        $reservations = $instancesData->get('Reservations');

        $this->instanceData = $reservations[0]['Instances'][0];
    }

    public function getDefaultSubnetForAz($zone = null)
    {
        $defaultSubnet = $this->apiConnection->getDefaultSubnetForAz($zone);
        if (!$defaultSubnet)
        {
            $defaultSubnet = $this->createDefaultSubnet($zone);
        }

        return $defaultSubnet;
    }

    public function createDefaultSubnet()
    {
        $subnet = $this->apiConnection->createDefaultSubnetForAvZone();

        return $subnet;
    }

    protected function loadApiConnection()
    {
        $this->apiConnection = new ClientWrapper($this->pid, $this->sid);
    }

    protected function updateDedicateIp($ip, $force = true)
    {
        $hosting    = Hosting::where('id', $this->sid)->first();

        if($hosting->dedicatedip && $force == false)
        {
            return;
        }

        $hosting->dedicatedip   = $ip;
        // $hosting->domain=$this->instanceData['PublicDnsName'];
        $hosting->save();
        $this->hosting=$hosting;
        $networkDevices = $this->instanceData['NetworkInterfaces'];
        if(isset($networkDevices[0])){
            $networkInterface = $networkDevices[0];
        }else{
            $networkInterface = $this->getAmazonIpDevice($networkDevices);
        }
        if($networkInterface){
            logModuleCall("AWSEc2","updateDedicateIp",$ip,$this->instanceData);
            $exists=NetworkInterface::where([
                    'service_id'=>$this->sid,
                    'allocation_id'=>$networkInterface['NetworkInterfaceId']
                ])->exists();
            if(!$exists){
                NetworkInterface::insert([
                    'service_id'=>$this->sid,
                    'allocation_id'=>$networkInterface['NetworkInterfaceId']
                ]);
            }
        }

    }
    protected function afterTaskActionProcess($hosting)
    {

        $id=$hosting->id;
        $moduleInterface = new \WHMCS\Module\Server();
        $moduleInterface->loadByServiceID($id);
        $this->params = $moduleInterface->buildParams();
        $this->loadProductConfig($this->params);

        $mailresponse=$this->sendWelcomeEmail($id, $this->params, $this->instanceData);
    }
    protected function loadProductConfig($params)
    {
        $this->pid = $params['pid'];
        $this->sid = $params['serviceid'];

        $this->params = $params;

        $productConfigRepo = new Repository();

        $this->productSettings = $productConfigRepo->getProductSettings($this->pid);
    }
    protected function getLoginUsername()
    {
        $imageid=$this->instanceData['ImageId'];
        if(!$imageid){
            return false;
        }
        $imagesData = $this->apiConnection->describeImages([
            'ImageIds'=>[$imageid]
        ]);
        $loginusername=false;
        if(isset($imagesData[0])){
            $imagename=$imagesData[0]['Name'];
            foreach ($this->amiusernames as $username => $amitype) {
                if(strpos($imagename, $amitype)!==false){
                    $loginusername=$username;
                    break;
                }
            }
        }
        return $loginusername;
    }
    protected function sendWelcomeEmail(int $serviceId, array $params, array $customVars = null)
    {
        $templateId = $this->productSettings['emailTemplate'];
        if($templateId === 'off')
            return;

        unset($params['model']);
        array_merge($params, $params['customfields']);
        $params = array_merge($params, $params['customfields'], $params['configoptions'], $params['clientsdetails']);
        unset($params['customfields']);
        unset($params['configoptions']);
        unset($params['clientsdetails']);

        $customVars = $this->extractResponseArray($customVars);

        $loginusername=$this->getLoginUsername();
        if($loginusername){
            $customVars['loginusername']=$loginusername;
        }

        $emailSender = new EmailSender();
        if($customVars){
            $cv=array_merge($customVars, $params);
        }else{
            logActivity("Instance data empty for email");
            $cv=$params;
        }
        $cv=json_decode(json_encode($cv),true);
        if(!is_bool($cv)){
            $res = $emailSender->send($templateId, $serviceId, $cv);
        }else{
            logModuleCall("AWSEc2","VmCreated".__LINE__,$cv,$customVars);
        }

    }

    protected function extractResponseArray($instanceDetails)
    {
        $result = [];
        foreach ($instanceDetails as $key => $detail) {
            if(is_array($detail) && !empty($detail))
            {
                $temp = $this->extractResponseArray($detail);
                foreach($temp as $name => $value)
                {
                    $result[$key . '_' . $name] = $value;
                }
            }
            else
            {
                $result[$key] = $detail;
            }
        }
        return $result;
    }

    public function pospond($isError = false)
    {
        $postponeTime = $this->getPostponeTime();

        if ($isError === true)
        {
            $this->model->setError();
        }
        else
        {
            $this->model->setWaiting();
        }

        $this->model->setRetryAfter(date('Y-m-d H:i:s', strtotime('+ ' . $postponeTime . ' seconds')));

        return false;
    }

    public function getPostponeTime()
    {
        if ($this->model->retry_count <= 6)
        {
            return '240';
        }

        if ($this->model->retry_count <= 12)
        {
            return '540';
        }

        return '3480';
    }

    public function cancelPreviousTasks()
    {
        $query = di(Queue::class);

        $query->cancelRelated($this->model->id);
    }

    public function createTags($resourceId)
    {
        if (!is_array($this->tags))
        {
            return;
        }

        $this->apiConnection->createTags([
            'Resources' => [$resourceId],
            'Tags' => $this->tags
        ]);
    }
}
