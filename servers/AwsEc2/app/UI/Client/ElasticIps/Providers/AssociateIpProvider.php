<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Providers;

use Aws\Exception\AwsException;
use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\SecurityRulesConstants;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\Service;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\di;
use ModulesGarden\Servers\AwsEc2\Core\Helper\BuildUrl;

class AssociateIpProvider extends BaseDataProvider
{

    public function read()
    {
        $ip = json_decode(base64_decode($this->actionElementId));
        $this->data['name'] = $ip->name;
        $this->data['ipv4address']     = $ip->ipv4address;
        $this->data['allocationid']         = $ip->allocationid;
        $this->data['privateip']    = $ip->privateip;
        $this->data['networkinterface']       = $ip->networkinterface;
        $this->data['associated']         = $ip->associated;
        $this->data['associationId']         = $ip->associationId;

    }
    public function getNetworkInterfaces()
    {
        $this->read();

        $serviceId    = $this->getWhmcsParamByKey('serviceid');
        $productId    = $this->getWhmcsParamByKey('pid');
        $instanceName = $this->getWhmcsParamByKey('domain');

        $apiConnection = new ClientWrapper($productId, $serviceId);
        $instanceName = $this->getWhmcsParamByKey('domain');
        $instance = $apiConnection->describeInstances(['InstanceIds' => [$this->getWhmcsParamByKey('customfields')['InstanceId']]]);
        $instanceData=$instance->get("Reservations")['0']['Instances']['0'];
        $subnet=$instanceData['SubnetId'];
        $interfacesdata= $apiConnection->describeNetworkInterfaces(['Filter'=>['subnet-id'=>$subnet]]);
        $data=[];
        $interfaces=$interfacesdata->get("NetworkInterfaces");
         foreach ($interfaces as $key => $interface) {
            $instanceName=$securitygroup=$publicip=$privateip='';
            if($interface['Status']=='in-use'){
                if(!empty($interface['TagSet'])){
                    foreach ($interface['TagSet'] as $key => $tag) {
                        if($tag['Key']=="Name"){
                            $instanceName=$tag['Value'];
                            break;
                        }
                    }
                }
            }
            if(isset($interface['Association'])){
                $publicip=$interface['Association']['PublicIp'];
            }
            if(isset($interface['Groups'])){
                $securitygroup=$interface['Groups'][0]['GroupName'];
            }
            $data []=[
                    'name' => $instanceName,
                    'interfaceid' =>$interface['NetworkInterfaceId'],
                    'subnet' => $interface['SubnetId'],
                    // 'vpc' => $interface['VpcId'],
                    'zone' => $interface['AvailabilityZone'],
                    'securitygroup'=>$securitygroup ,//'server1651233398.cyberpower.co.il_281_626bd9580',
                    'interfacetype'=>$interface['InterfaceType'],
                    // 'instanceid'=>$interface['instanceid'],
                    'status'=>$interface['Status'],
                    'publicip'=>$publicip,
                    'privateip'=>$interface['PrivateIpAddress']

                ];
        }
        return $data;
    }
    public function create()
    {



        $postdata=[];
        $postdata['AllocationId']=$this->data['allocationid'];
        if($this->formData['resourceType']=="Instance"){

            $postdata["InstanceId"]=$this->getWhmcsParamByKey('customfields')['InstanceId'];;
        }else{
            if($this->formData['networkinterface']==-1){
                return (new HtmlDataJsonResponse())
                ->setStatusError()
                ->setMessage(di('lang')->T('networkinterfacerequired'));
            }
            $postdata["NetworkInterfaceId"]=$this->formData['networkinterface'];
        }
        if(isset($this->formData['reassociate'])){
            $postdata['AllowReassociation']=($this->formData['reassociate']['reassociate']=="on");
        }
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
        $url=BuildUrl::getUrl('elasticIps', 'index', ['id'=>$_GET['id']]);
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

        $serviceId     = $this->getWhmcsParamByKey('serviceid');
        $productId     = $this->getWhmcsParamByKey('pid');
        $apiConnection = new ClientWrapper($productId, $serviceId);



        try
        {
            $associationId=$this->formData['associationId'];
            $disassociated        = $apiConnection->disassociateAddress(['AssociationId'=>$associationId]);
        }
        catch (AwsException $e)
        {
            return (new HtmlDataJsonResponse())
            ->setStatusError()
            ->setMessage($e->getAwsErrorMessage());
        }


        $callbackFunction='redirectTo';
        $url=BuildUrl::getUrl('elasticIps', 'index', ['id'=>$_GET['id']]);
        return (new HtmlDataJsonResponse())
        ->setStatusSuccess()
        ->addData('redirecturl',$url)
        ->setMessageAndTranslate('ruleDeletedSuccesfully')
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
