<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Providers;

use Aws\Exception\AwsException;
use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\SecurityRulesConstants;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\Service;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\di;
use ModulesGarden\Servers\AwsEc2\App\Models\NetworkInterface;
use ModulesGarden\Servers\AwsEc2\Core\Helper\BuildUrl;

class AssociateIpProvider extends BaseDataProvider
{

    public function read()
    {
        $interface = json_decode(base64_decode($this->actionElementId));
        $this->data['actionElementId']=$this->actionElementId;
        $this->data['name'] = $interface->name;
        $this->data['interfaceid']     = $interface->interfaceid;
        $this->data['subnet']         = $interface->subnet;
        $this->data['zone']    = $interface->zone;
        $this->data['securitygroup']       = $interface->securitygroup;
        $this->data['InterfaceType']         = $interface->InterfaceType;
        $this->data['status']         = $interface->status;
        $this->data['publicip']         = $interface->publicip;
        $this->data['privateip']         = $interface->privateip;
        $this->data['associated']         = $interface->associated;
        $this->data['associationId']         = $interface->associationId;
    }
    public function getElasticIps()
    {
        $this->read();

        $serviceId    = $this->getWhmcsParamByKey('serviceid');
        $productId    = $this->getWhmcsParamByKey('pid');
        $instanceName = $this->getWhmcsParamByKey('domain');

        $apiConnection = new ClientWrapper($productId, $serviceId);
        $subnets  = $apiConnection->describeSubnets();
        $subnets=$subnets->get('Subnets');
        return $subnets;
    }
    public function create()
    {
        $postdata=[];

        if($this->formData['elasticip']==-1){
            return (new HtmlDataJsonResponse())
            ->setStatusError()
            ->setMessage(di('lang')->T('networkinterfacerequired'));
        }else{
            $postdata['AllocationId']=$this->formData['elasticip'];
        }

        $postdata['NetworkInterfaceId']=$this->data['interfaceid'];
        if($this->formData['privateip']!=-1){
            $postdata['PrivateIpAddress']=$this->formData['privateip'];
        }
        $serviceId     = $this->getWhmcsParamByKey('serviceid');
        $productId     = $this->getWhmcsParamByKey('pid');
        $apiConnection = new ClientWrapper($productId, $serviceId);

        try
        {
         $response= $apiConnection->associateAddress($postdata);
     }catch (AwsException $e)
     {
        return (new HtmlDataJsonResponse())
        ->setStatusError()
        ->setMessage($e->getAwsErrorMessage());
    }


    $callbackFunction='redirectTo';
    $url=BuildUrl::getUrl('networkInterfaces', 'index', ['id'=>$_GET['id']]);
    return (new HtmlDataJsonResponse())
    ->setStatusSuccess()
    ->addData('redirecturl',$url)
    ->setMessageAndTranslate('ipAssociatedSuccesfully')
    ->setCallBackFunction($callbackFunction);

}

public function update()
{
}

public function delete()
{
    $this->actionElementId=$this->formData['actionElementId'];
    $this->read();

    $serviceId     = $this->getWhmcsParamByKey('serviceid');
    $productId     = $this->getWhmcsParamByKey('pid');
    $apiConnection = new ClientWrapper($productId, $serviceId);



    try
    {
        $associationId=$this->formData['associationId'];
        if($associationId){
            $disassociated        = $apiConnection->disassociateAddress(['AssociationId'=>$associationId]);
        }


    }catch (AwsException $e){
        return (new HtmlDataJsonResponse())
        ->setStatusError()
        ->setMessage($e->getAwsErrorMessage());
    }

    $callbackFunction='redirectTo';
    $url=BuildUrl::getUrl('networkInterfaces', 'index', ['id'=>$_GET['id']]);
    return (new HtmlDataJsonResponse())
    ->setStatusSuccess()
    ->addData('redirecturl',$url)
    ->setMessageAndTranslate('ipDisassociatedSuccesfully')
    ->setCallBackFunction($callbackFunction);
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
