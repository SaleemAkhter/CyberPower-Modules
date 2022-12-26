<?php

namespace ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration;

use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\Models\Whmcs\ServersRelations;
use ModulesGarden\Servers\AwsEc2\Core\Traits\AppParams;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\Service;

class ClientWrapper
{
    use AppParams;

    private $client = null;
    private $product = null;
    private $service = null;
    private $productConfig = [];
    private $region;
    private $server = null;

    public function __construct($productId = null, $serviceId = null, $region = null)
    {
        $this->loadProduct($productId);
        $this->loadService($serviceId);
        $this->loadProductConfig($productId);
        $this->region = $region;

        $this->loadServerByServerGroup($this->product->servergroup);

        $this->loadApiClient();
    }

    private function loadApiClient()
    {
        // debug([
        //         'service' => 'ec2',
        //         'region' => $this->region ?: $this->getRegion(),
        //         'version' => '2016-11-15',
        //         'credentials' =>
        //             [
        //                 'key' => $this->server->username,
        //                 'secret' => \decrypt($this->server->password)
        //             ]
        //     ]);die();
        $this->client = new \Aws\AwsClient(
            [
                'service' => 'ec2',
                'region' => $this->region ?: $this->getRegion(),
                'version' => '2016-11-15',
                'credentials' =>
                    [
                        'key' => $this->server->username,
                        'secret' => \decrypt($this->server->password)
                    ]
            ]
        );
    }

    protected function runApiCall($function, $params)
    {
        try
        {
            $resault = $this->client->{$function}($params);

            $this->logApiCall($function, $params, $resault);
        }
        catch (\Aws\Exception\AwsException $exc)
        {
            $resault = $exc->getAwsErrorMessage();

            $this->logApiCall($function, $params, $resault);

            throw $exc;
        }

        return $resault;
    }

    protected function logApiCall($action, $request, $response, $processedData = [], $replacements = [])
    {
        if ($this->productConfig['logApiRequests'] !== 'on')
        {
            return;
        }

        $moduleName = $this->getAppParam('systemName');

        //remove api details from the logs just in case
        $replacements[] = $this->server->username;
        $replacements[] = \decrypt($this->server->password);

        $requestLogParams = [
            'serviceId' => $this->service ? $this->service->getServiceId() : 'none',
            'productId' => $this->product->id,
            'params' => $request
        ];

        $responseLogParams = is_object($response) ? var_export($response, true) : $response;

        \logModuleCall($moduleName, $action, $requestLogParams, [], $responseLogParams, $replacements);
    }

    public function getRegion()
    {
        if ($this->service)
        {
            $serviceConfigOptions = $this->service->getConfigurableOptionsValues();
            if (is_string($serviceConfigOptions['region']['value']) && trim($serviceConfigOptions['region']['value']) !== '')
            {
                return $serviceConfigOptions['region']['value'];
            }
        }

        return $this->productConfig['region'] ? $this->productConfig['region'] : 'us-west-2';
    }

    protected function loadServerByServerGroup($serverGroupId = null)
    {
        $serverGroupModel = new ServersRelations();
        $serverD = $serverGroupModel->select('serverid')->leftJoin('tblservers', 'tblservers.id', '=', 'tblservergroupsrel.serverid')
            ->where('tblservergroupsrel.groupid', $serverGroupId);

        $serverId = $serverD ? $serverD->first()->serverid : null;

        $this->server = \WHMCS\Product\Server::find($serverId);
        if (!$this->server)
        {
            throw new \Exception('Module Configuration Incomplete');
        }
    }

    protected function loadProduct($productId = null)
    {
        $this->product = \WHMCS\Product\Product::find($productId);
    }

    protected function loadService($serviceId = null)
    {
        if ($serviceId)
        {
            $this->service = new Service($serviceId);
        }
    }

    protected function loadProductConfig($productId = null)
    {
        $productConfigRepo = new Repository();

        $this->productConfig = $productConfigRepo->getProductSettings($productId);
    }

    public function getProductConfig($productId = null)
    {
        $this->loadProductConfig($productId);
        return $this->productConfig;
    }

    public function getImagesList($params)
    {
        $imagesHelper = new Helpers\Images();

        $params = [
            'Filters' => $imagesHelper->prepareFiltersData($params['filters'])
        ];

        return $this->getImagesListRaw($params);
    }

    public function getDefaultSubnetForAz($zone = null)
    {
        $instances = $this->runApiCall('describeSubnets', [
            'Filters' => [
                [
                    'Name' => 'default-for-az',
                    'Values' => ['true']
                ],
                [
                    'Name' => 'availability-zone',
                    'Values' => [$zone]
                ]
            ]
        ]);

        $subnets = $instances->get('Subnets');

        return $subnets[0];
    }

    public function createDefaultSubnetForAvZone($zone = null)
    {
        $instances = $this->runApiCall('createDefaultSubnet', [
            'AvailabilityZone' => ($zone ? : $this->getRegion())
        ]);

        $subnet = $instances->get('Subnet');

        return $subnet;
    }

    public function createNetworkInterface($params = [])
    {
        $instances = $this->runApiCall('createNetworkInterface', $params);

        $interface = $instances->get('NetworkInterface');

        return $interface;
    }

    public function attachNetworkInterface($params = [])
    {
        $instances = $this->runApiCall('attachNetworkInterface', $params);

        $attachmentId = $instances->get('AttachmentId');

        return $attachmentId;
    }

    public function getImagesListRaw($params)
    {
        $instances = $this->runApiCall('describeImages', $params);

        $images = $instances->get('Images');

        return $images;
    }

    public function getSecurityGroups($params)
    {
        $instances = $this->runApiCall('describeSecurityGroups', $params);

        $securityGroups = $instances->get('SecurityGroups');

        return $securityGroups;
    }

    public function getSecurityGroup($groupName)
    {
        if(!$this->service)
        {
            return null;
        }

        $serviceId      = $this->service->getServiceId();
        $securityGroups = $this->getSecurityGroups([]);

        $pattern        = !empty($groupName) ? '/^'.$groupName.'_'.$serviceId.'_[[:alnum:]]+$/' : '/^sg_'.$serviceId.'_[[:alnum:]]+$/';
        foreach ($securityGroups as $securityGroup) {
            if ((!empty($groupName) && $groupName == $securityGroup['GroupName']) || preg_match($pattern, $securityGroup['GroupName'])) {
                return $securityGroup;
            }
        }
        return null;
    }

    public function createSecurityGroup($params = [])
    {
        $instances = $this->runApiCall('createSecurityGroup', $params);

        return $instances;
    }

    public function deleteSecurityGroup($params = [])
    {
        $instances = $this->runApiCall('deleteSecurityGroup', $params);

        return $instances;
    }

    public function getRegions()
    {
        $regions = $this->runApiCall('describeRegions', []);

        return $regions->get('Regions');
    }

    public function createTags($params = [])
    {
        $instances = $this->runApiCall('createTags', $params);

        return $instances;
    }

    public function runInstances($params = [])
    {
        $instances = $this->runApiCall('runInstances', $params);

        return $instances;
    }

    public function allocateAddress($params = [])
    {
        $instances = $this->runApiCall('allocateAddress', $params);

        return $instances;
    }

    public function associateAddress($params = [])
    {
        $instances = $this->runApiCall('associateAddress', $params);

        return $instances;
    }

    public function modifyInstanceAttribute($params = [])
    {
        $instances = $this->runApiCall('modifyInstanceAttribute', $params);

        return $instances;
    }

    public function importKeyPair($params = [])
    {
        $instances = $this->runApiCall('importKeyPair', $params);

        return $instances;
    }

    public function describeAddress($address = null)
    {
        $instances = $this->runApiCall('describeAddresses', ['PublicIps' => [$address]]);

        $addressDetails = $instances->get('Addresses');

        return $addressDetails[0];
    }
    public function describeAddresses($addresses = [])
    {
        $instances = $this->runApiCall('describeAddresses', ['PublicIps' => $addresses]);

        $addressDetails = $instances->get('Addresses');

        return $addressDetails;
    }
    public function disassociateAddress($params = [])
    {
        $instances = $this->runApiCall('disassociateAddress', $params);

        return $instances;
    }
    public function releaseAddress($params = [])
    {
        $instances = $this->runApiCall('releaseAddress', $params);

        return $instances;
    }

    public function detachNetworkInterface($attachmentId = null)
    {
        $instances = $this->runApiCall('detachNetworkInterface', ['AttachmentId' => $attachmentId]);

        return $instances;
    }
    public function describeNetworkInterfaces($params)
    {
        $interfaces = $this->runApiCall('describeNetworkInterfaces',$params);

        return $interfaces;
    }
    public function deleteNetworkInterface($networkInterfaceId = null)
    {
        $instances = $this->runApiCall('deleteNetworkInterface', ['NetworkInterfaceId' => $networkInterfaceId]);

        return $instances;
    }

    public function deleteKeyPair($params = [])
    {
        $instances = $this->runApiCall('deleteKeyPair', $params);

        return $instances;
    }

    public function assignPrivateIpAddresses($params = [])
    {
        $instances = $this->runApiCall('assignPrivateIpAddresses', $params);

        return $instances;
    }

    public function assignIpv6Addresses($params = [])
    {
        $instances = $this->runApiCall('assignIpv6Addresses', $params);

        return $instances;
    }

    public function stopInstances($params = [])
    {
        $instances = $this->runApiCall('stopInstances', $params);

        return $instances;
    }

    public function terminateInstances($params = [])
    {
        $instances = $this->runApiCall('terminateInstances', $params);

        return $instances;
    }

    public function startInstances($params = [])
    {
        $instances = $this->runApiCall('startInstances', $params);

        return $instances;
    }

    public function rebootInstances($params = [])
    {
        $instances = $this->runApiCall('rebootInstances', $params);

        return $instances;
    }

    public function describeInstanceStatus($params = [])
    {
        $instance = $this->runApiCall('describeInstanceStatus', $params);

        return $instance;
    }

    public function describeInstances($params)
    {
        return  $this->runApiCall('describeInstances', $params);
    }

    public function getInstanceSecurityGroups($params)
    {
        $instance =  $this->runApiCall('describeInstances', $params);
        $reservations = $instance->get('Reservations');
        $instanceData = $reservations[0]['Instances'][0];
        $securityGroups = $instanceData["SecurityGroups"];

        return $securityGroups;
    }

    public function describeImages($params)
    {
        $instance = $this->runApiCall('describeImages', $params);

        return $instance->get('Images');
    }

    public static function testConnection($key = null, $pass = null)
    {
        $client = new \Aws\AwsClient(
            [
                'service' => 'ec2',
                'region' => 'us-west-2',
                'version' => '2016-11-15',
                'credentials' =>
                    [
                        'key' => $key,
                        'secret' => $pass
                    ]
            ]
        );
        $regions = $client->describeRegions([]);

        return $regions->get('Regions');
    }

    public function networkInterfaceExists($networkInterfaceId = null)
    {
        $instance = $this->runApiCall('describeNetworkInterfaces', [
            'NetworkInterfaceIds' => [$networkInterfaceId]
        ]);

        $interfaces = $instance->get('NetworkInterfaces');

        if ($interfaces[0]['NetworkInterfaceId'] === $networkInterfaceId)
        {
            return true;
        }

        return false;
    }

    public function getWindowsPassword($instanceId = null)
    {
        $instancesPassword = $this->runApiCall('getPasswordData', [
            'InstanceId' => $instanceId
        ]);

        return $instancesPassword->get('PasswordData');
    }

    public function describeVolumes($params = [])
    {
        $volumesInfo = $this->runApiCall('describeVolumes', $params);

         return $volumesInfo->get('Volumes');
    }

    public function modifyVolume($params = [])
    {
       $volumesInfo = $this->runApiCall('modifyVolume', $params);

       return $volumesInfo;
    }

    public function describeSubnets($params = [])
    {
        $subnetsDescription = $this->runApiCall('DescribeSubnets', $params);

        return $subnetsDescription;
    }

    public function describeVpcs($params = [])
    {
        $vpcsDescription = $this->runApiCall('DescribeVpcs', $params);

        return $vpcsDescription;
    }

    public function authorizeSecurityGroupInboundRule($params = [])
    {
        $ruleAdded = $this->runApiCall('authorizeSecurityGroupIngress', $params);

        return $ruleAdded;
    }

    public function authorizeSecurityGroupOutboundRule($params = [])
    {
        $ruleAdded = $this->runApiCall('authorizeSecurityGroupEgress', $params);

        return $ruleAdded;
    }

    public function deleteSecurityGroupInboundRule($params = [])
    {
        $deleted = $this->runApiCall('RevokeSecurityGroupIngress', $params);

        return $deleted;
    }

    public function deleteSecurityGroupOutboundRule($params = [])
    {
        $deleted = $this->runApiCall('RevokeSecurityGroupEgress', $params);

        return $deleted;
    }

    public function getSecurityGroupRulesCout()
    {

    }


}
