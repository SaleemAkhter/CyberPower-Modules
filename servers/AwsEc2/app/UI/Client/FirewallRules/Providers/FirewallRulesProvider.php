<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Providers;

use Aws\Exception\AwsException;
use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\SecurityRulesConstants;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\Service;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\di;

class FirewallRulesProvider extends BaseDataProvider
{

    public function read()
    {
        $rule = json_decode(base64_decode($this->actionElementId));

        $this->data['instanceName'] = $rule->instanceName;
        $this->data['protocol']     = $rule->protocol;
        $this->data['type']         = $rule->type;
        $this->data['portRange']    = $rule->portRange;
        $this->data['source']       = $rule->source;
        $this->data['rule']         = $rule->rule;
    }

    public function create()
    {
        $numberOfRules = $this->getWhmcsParamByKey('configoptions')['firewallRules']
        ? $this->getWhmcsParamByKey('configoptions')['firewallRules']
        : $this->getNumberOfRulesFromSettings();

        if ($this->getRulesCount() >= $numberOfRules)
        {
            return (new HtmlDataJsonResponse())
            ->setStatusError()
            ->setMessage(di('lang')->T('numberOfRulesExceedTheLimit'));
        }

        $this->read();

        $serviceId    = $this->getWhmcsParamByKey('serviceid');
        $productId    = $this->getWhmcsParamByKey('pid');
        $instanceName = $this->getWhmcsParamByKey('domain');

        $apiConnection = new ClientWrapper($productId, $serviceId);
        $sgroup  = $apiConnection->getSecurityGroup($instanceName);
        $groupId = $sgroup['GroupId'];
        $ports = [];

        if (($this->formData['portRange'] == '-1') || (strtolower($this->formData['portRange']) === 'all'))
        {
            if ($this->formData['protocol'] == 'TCP' || $this->formData['protocol'] == 'UDP')
            {
                $ports = SecurityRulesConstants::ALL_TCP_UDP_PORTS;
            }
            else
            {
                $ports[0] = '-1';
            }
        }
        else
        {
            $ports = explode(SecurityRulesConstants::TABLE_PORTS_SEPARATOR, $this->formData['portRange'], 2);
        }

        $this->formData['protocol'] = $this->formData['protocol'] === 'ICMP IPv6' ? 'ICMPV6' : $this->formData['protocol'];
        $ipPerm                     = [
            'FromPort'   => $ports[0],
            'ToPort'     => $ports[1] ? $ports[1] : $ports[0],
            'IpProtocol' => $this->formData['protocol'] == 'All' ? '-1' : $this->formData['protocol'],
        ];

        if (!trim($this->formData['source']))
        {
            $this->formData['source'] = '0.0.0.0/0';
        }
        if (strpos($this->formData['source'], '/') === false)
        {
            $this->formData['source'] = $this->formData['source'] . "/32";
        }

        if (strpos($this->formData['source'], '::') === 0)
        {
            $ipPerm['Ipv6Ranges'] = [['CidrIpv6' => $this->formData['source']]];
        }
        else
        {
            $ipPerm['IpRanges'] = [['CidrIp' => $this->formData['source']]];
        }

        $ipPermissions = [
            'GroupId'       => $groupId,
            'IpPermissions' => [$ipPerm]
        ];

        $inboundrule=[
            'GroupId' => "sg-063428b6d5805d9f8",
            'IpPermissions' => [
               [
                'FromPort' => 22,
                'ToPort' => 22,
                'IpProtocol' => TCP,
                'IpRanges' => [
                    [
                        'CidrIp' => '0.0.0.0/0'
                    ]
                ]

                ]

            ]
        ];
    try
    {
        if ($this->formData['rule'] == SecurityRulesConstants::RULE_INBOUND)
        {
            $apiConnection->authorizeSecurityGroupInboundRule($ipPermissions);
        }
        else
        {
            if ($this->formData['rule'] == SecurityRulesConstants::RULE_OUTBOUND)
            {
                $apiConnection->authorizeSecurityGroupOutboundRule($ipPermissions);
            }
        }
    }
    catch (AwsException $e)
    {
        return (new HtmlDataJsonResponse())
        ->setStatusError()
        ->setMessage($e->getAwsErrorMessage());
    }

    if ($this->getRulesCount() < $numberOfRules || $numberOfRules == -1)
    {
        $callbackFunction = 'showAddRuleButton';
    }
    else
    {
        $callbackFunction = 'hideAddRuleButton';
    }

    return (new HtmlDataJsonResponse())
    ->setStatusSuccess()
    ->setMessageAndTranslate('ruleCreatedSuccesfully')
    ->setCallBackFunction($callbackFunction);
}

public function update()
{
}

public function delete()
{
    $ports = [];
    if ($this->formData['portRange'] === null || $this->formData['portRange'] === '')
    {
        $ports[0] = '-1';
    }
    else
    {
        if ($this->isPortRangeNotAvailableValue($this->formData['portRange']))
        {
            $ports = $this->parseNAValue($this->formData['portRange']);
        }
        else
        {
            if ($this->formData['portRange'] != -1)
            {
                $ports = explode(SecurityRulesConstants::TABLE_PORTS_SEPARATOR, $this->formData['portRange']);
            }
            else
            {
                $ports[0] = '-1';
            }
        }
    }

    $serviceId     = $this->getWhmcsParamByKey('serviceid');
    $productId     = $this->getWhmcsParamByKey('pid');
    $apiConnection = new ClientWrapper($productId, $serviceId);
    $sgroup        = ($apiConnection->getSecurityGroup($this->formData['instanceName']));
    $groupId       = $sgroup['GroupId'];

    $ipPerm = [
        'FromPort'   => $ports[0],
        'ToPort'     => $ports[1] ? $ports[1] : $ports[0],
        'IpProtocol' => $this->formData['protocol'],
    ];

    if (strpos($this->formData['source'], '::') === 0)
    {
        $ipPerm['Ipv6Ranges'] = [['CidrIpv6' => $this->formData['source']]];
    }
    else
    {
        $ipPerm['IpRanges'] = [['CidrIp' => $this->formData['source']]];
    }

    $ipPermissions = [
        'GroupId'       => $groupId,
        'IpPermissions' => [$ipPerm]
    ];

    try
    {
        if ($this->formData['rule'] == SecurityRulesConstants::RULE_INBOUND)
        {
            $apiConnection->deleteSecurityGroupInboundRule($ipPermissions);
        }
        else
        {
            if ($this->formData['rule'] == SecurityRulesConstants::RULE_OUTBOUND)
            {
                $apiConnection->deleteSecurityGroupOutboundRule($ipPermissions);
            }
        }
    }
    catch (AwsException $e)
    {
        return (new HtmlDataJsonResponse())
        ->setStatusError()
        ->setMessage($e->getAwsErrorMessage());
    }

    $numberOfRules = $this->getWhmcsParamByKey('configoptions')['firewallRules']
    ? $this->getWhmcsParamByKey('configoptions')['firewallRules']
    : $this->getNumberOfRulesFromSettings();

    if ($this->getRulesCount() < $numberOfRules || $numberOfRules == -1)
    {
        $callbackFunction = 'showAddRuleButton';
    }
    else
    {
        $callbackFunction = 'hideAddRuleButton';
    }

    return (new HtmlDataJsonResponse())
    ->setStatusSuccess()
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
