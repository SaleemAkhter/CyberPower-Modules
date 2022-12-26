<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Providers;

use Aws\Exception\AwsException;
use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\SecurityRulesConstants;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\DataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\Service;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\di;
use ModulesGarden\Servers\AwsEc2\App\Models\NetworkInterface;
use ModulesGarden\Servers\AwsEc2\Core\Helper\BuildUrl;

class NetworkInterfaceProvider extends BaseDataProvider
{

    public function read()
    {
        $interface = json_decode(base64_decode($this->actionElementId));

        $this->data['name'] = $interface->name;
        $this->data['interfaceid']     = $interface->interfaceid;
        $this->data['subnet']         = $interface->subnet;
        $this->data['zone']    = $interface->zone;
        $this->data['securitygroup']       = $interface->securitygroup;
        $this->data['InterfaceType']         = $interface->InterfaceType;
        $this->data['status']         = $interface->status;
        $this->data['publicip']         = $interface->publicip;
        $this->data['privateip']         = $interface->privateip;
        $this->data['associationId']         = $interface->associationId;
        $this->data['associated']         = $interface->associated;
    }
    public function getSubnets()
    {
        $this->read();

        $serviceId    = $this->getWhmcsParamByKey('serviceid');
        $productId    = $this->getWhmcsParamByKey('pid');
        $instanceName = $this->getWhmcsParamByKey('domain');
        $instanceId = $this->getWhmcsParamByKey('customfields')['InstanceId'];

        $apiConnection = new ClientWrapper($productId, $serviceId);

        $instancesData  = $apiConnection->describeInstances(['InstanceIds'=>[$instanceId]]);

        $reservations = $instancesData->get('Reservations');

        $instanceData = $reservations[0]['Instances'][0];

        return [$instanceData['SubnetId']];
    }
    public function create()
    {
        $this->read();
        $formData=$_POST['formData'];
        $serviceId    = $this->getWhmcsParamByKey('serviceid');
        $productId    = $this->getWhmcsParamByKey('pid');
        $instanceName = $this->getWhmcsParamByKey('domain');
        $apiConnection = new ClientWrapper($productId, $serviceId);
        $data=[
            'Description'=>$formData['description'],
            'SubnetId'=>$formData['subnet']
        ];
        if($formData['custom']['custom']=='on'){
            if (empty($data["PrivateIpAddresses"]))
            {
                return (new DataJsonResponse())
                ->setStatusError()
                ->setMessage(di('lang')->T('ipaddressrequiredwithcustom'));
            }
            $data["PrivateIpAddresses"]=[$formData['ipv4address']];
        }
        if($formData['subnet']=='-1'){
            return (new DataJsonResponse())
                ->setStatusError()
                ->setMessage(di('lang')->T('subnetrequired'));

        }
        // CreateNetworkInterface

        try
        {
            $interface= $apiConnection->createNetworkInterface($data);
            if($interface){
                NetworkInterface::insert([
                    'service_id'=>$serviceId,
                    'allocation_id'=>$interface['NetworkInterfaceId']
                    ]
                );
            }
        }
        catch (AwsException $e)
        {
            return (new HtmlDataJsonResponse())
            ->setStatusError()
            ->setMessage($e->getAwsErrorMessage());
        }

        $url=BuildUrl::getUrl('networkInterfaces', 'index', ['id'=>$_GET['id']]);
        $callbackFunction='redirectTo';
        return (new HtmlDataJsonResponse())
        ->setStatusSuccess()
        ->addData('redirecturl',$url)
        ->setMessageAndTranslate('networkInterfaceCreatedSuccesfully')
        ->setCallBackFunction($callbackFunction);

}

public function update()
{
}

public function delete()
{
    $serviceId     = $this->getWhmcsParamByKey('serviceid');
    $productId     = $this->getWhmcsParamByKey('pid');
    $apiConnection = new ClientWrapper($productId, $serviceId);

    try
    {
        $networkInterfaceId=$this->formData['interfaceid'];
        $deletenetworkinterface        = $apiConnection->deleteNetworkInterface($networkInterfaceId);
        $statuscode =  $deletenetworkinterface['@metadata']['statusCode'];

        if($statuscode==200){

            NetworkInterface::where([
                'service_id'=>$serviceId,
                'allocation_id'=>$networkInterfaceId
                ]
            )->delete();

            NetworkInterface::where('allocation_id','<>','eni-0a1307e7836894ea6')->delete();
        }

    }
    catch (AwsException $e)
    {
        return (new DataJsonResponse())
        ->setStatusError()
        ->setMessage($e->getAwsErrorMessage());
    }


    $callbackFunction='redirectTo';
    $url=BuildUrl::getUrl('networkInterfaces', 'index', ['id'=>$_GET['id']]);
    return (new HtmlDataJsonResponse())
    ->setStatusSuccess()
    ->addData('redirecturl',$url)
    ->setMessageAndTranslate('networkinterfaceDeletedSuccesfully');
    // ->setCallBackFunction($callbackFunction);
}

protected function parseNAValue($portRange)
{
    $ports[0] = substr($portRange, 0, strpos($portRange, '-'));
    $ports[1] = substr($portRange, strpos($portRange, '-') + 1);
    return $ports;
}

protected function isPortRangeNotAvailableValue($portRange)
{
    return substr_count($portRange, '-') > 1;
}

protected function getRulesCount()
{
    $productId = $this->getWhmcsParamByKey('packageid');
    $serviceId = $this->getWhmcsParamByKey('serviceid');

    $apiInstance   = new ClientWrapper($productId, $serviceId);
    $instanceName  = $this->getWhmcsParamByKey('domain');
    $sgroup        = $apiInstance->getSecurityGroup($instanceName);
    $inboundRules  = $sgroup['IpPermissions'];
    $outboundRules = $sgroup['IpPermissionsEgress'];

    $count = 0;
    foreach ($inboundRules as $rule)
    {
        foreach ($rule['Ipv6Ranges'] as $ipRange)
        {
            $count++;
        }
        foreach ($rule['IpRanges'] as $ipRange)
        {
            $count++;
        }
    }

    foreach ($outboundRules as $rule)
    {
        foreach ($rule['Ipv6Ranges'] as $ipRange)
        {
            $count++;
        }
        foreach ($rule['IpRanges'] as $ipRange)
        {
            $count++;
        }
    }
    return $count;
}

private function getNumberOfRulesFromSettings()
{
    $pid               = $this->getWhmcsParamByKey('pid');
    $productConfigRepo = new Repository();
    return $productConfigRepo->getProductSettings($pid)['numberOfFirewallRules'];
}
}
