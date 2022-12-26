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

class ElasticIpsProvider extends BaseDataProvider
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

    public function create()
    {

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
