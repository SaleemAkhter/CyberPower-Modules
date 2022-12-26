<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Pages;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Buttons\Add;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Buttons\Delete;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\SecurityRulesConstants;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\Traits\Lang;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\DataTable;

class FirewallRulesPage extends DataTable implements AdminArea, ClientArea
{
    use Lang;
    protected $id    = 'firewallRulesPage';
    protected $name  = 'firewallRulePage';
    protected $title = 'firewallRulePageTitle';

    protected function loadHtml()
    {
        $this->addColumn((new Column('rule'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('protocol'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('ports'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('source'))->setOrderable()->setSearchable(true));
    }

    public function initContent()
    {
        $btn = new Add();

        $btn->setData(['rulesCount' => $this->getRulesCount()]);

        $noAvailRules = $this->getAvailableNumberOfRules();

        if($noAvailRules != -1)
            if($this->getRulesCount() >= $noAvailRules)
                $btn->setHtmlAttributes(['disabled' => "true"]);
            else
                $this->addInternalAlert(['limitreached'], ['danger']);

        $this->addButton($btn);
        $this->addActionButton(new Delete());
    }

    public function loadData()
    {
        $this->loadLang();

        $productId = $this->getWhmcsParamByKey('packageid');
        $serviceId = $this->getWhmcsParamByKey('serviceid');

        $apiInstance = new ClientWrapper($productId, $serviceId);
        $instanceName = $this->getWhmcsParamByKey('domain');
        $securityGroupName = $apiInstance->getInstanceSecurityGroups(['InstanceIds' => [$this->getWhmcsParamByKey('customfields')['InstanceId']]])[0]['GroupName'];

        $sgroup = $apiInstance->getSecurityGroup($securityGroupName);
        $inboundRules = $sgroup['IpPermissions'];
        $outboundRules = $sgroup['IpPermissionsEgress'];

        $data = [];
        $this->parseReceivedData($data,$inboundRules, SecurityRulesConstants::RULE_INBOUND);
        $this->parseReceivedData($data,$outboundRules, SecurityRulesConstants::RULE_OUTBOUND);

        foreach ($data as &$dataEntity)
        {
            $dataEntity['id'] =
                base64_encode(
                    json_encode([
                        'instanceName' => $instanceName,
                        'portRange' => $dataEntity['ports'],
                        'protocol' => strtoupper($dataEntity['protocol']),
                        'rule' => $dataEntity['rule'],
                        'source' => $dataEntity['source'],
            ]));
        }

        $provider = new ArrayDataProvider();
        $provider->setData($data);
        $this->setDataProvider($provider);
    }

    public function replaceFieldPorts($key, $row)
    {
        return $row[$key] < 0 || $row[$key] === null ? "All" : $row[$key];
    }

    public function replaceFieldProtocol($key, $row)
    {
        if(strtoupper($row[$key]) == "ICMPV6")
            return "IPv6 ICMP";
        if(strtoupper($row[$key]) == "ICMPV4")
            return "IPv4 ICMP";

        return $row[$key] < 0 ? "All" : strtoupper($row[$key]);
    }

    private function parseReceivedData(&$result, $data, $ruleBound) {
        foreach ($data as $rule) {
            foreach ($rule['IpRanges'] as $ipRange) {
                $result[] = [
                    'rule' => $ruleBound,
                    'protocol' => $rule['IpProtocol'],
                    'ports' => $rule['FromPort'] != $rule['ToPort']
                        ? $rule['FromPort'] . SecurityRulesConstants::TABLE_PORTS_SEPARATOR . $rule['ToPort']
                        : $rule['FromPort'],
                    'source' => $ipRange['CidrIp'],
                    ];
            }
            foreach ($rule['Ipv6Ranges'] as $ipRange) {
                $result[] = [
                    'rule' => $ruleBound,
                    'protocol' => $rule['IpProtocol'],
                    'ports' => $rule['FromPort'] != $rule['ToPort']
                        ? $rule['FromPort'] . SecurityRulesConstants::TABLE_PORTS_SEPARATOR . $rule['ToPort']
                        : $rule['FromPort'],
                    'source' => $ipRange['CidrIpv6'],
                ];
            }
        }
    }

    private function getRulesCount()
    {
        $productId = $this->getWhmcsParamByKey('packageid');
        $serviceId = $this->getWhmcsParamByKey('serviceid');

        $apiInstance = new ClientWrapper($productId, $serviceId);
        $securityGroupName = $apiInstance->getInstanceSecurityGroups(['InstanceIds' => [$this->getWhmcsParamByKey('customfields')['InstanceId']]])[0]['GroupName'];

        $sgroup = $apiInstance->getSecurityGroup($securityGroupName);
        $inboundRules = $sgroup['IpPermissions'];
        $outboundRules = $sgroup['IpPermissionsEgress'];

        $count = 0;
        foreach ($inboundRules as $rule) {
            foreach ($rule['Ipv6Ranges'] as $ipRange)
                $count++;
            foreach ($rule['IpRanges'] as $ipRange)
                $count++;
        }

        foreach ($outboundRules as $rule) {
            foreach ($rule['Ipv6Ranges'] as $ipRange)
                $count++;
            foreach ($rule['IpRanges'] as $ipRange)
                $count++;
        }
        return $count;
    }

    private function getAvailableNumberOfRules()
    {
        if($this->getWhmcsParamByKey('configoptions')['firewallRules'] !== null)
            return $this->getWhmcsParamByKey('configoptions')['firewallRules'];
        else {
            $productConfigRepo = new Repository();
            $productSettings = $productConfigRepo->getProductSettings($this->getWhmcsParamByKey('pid'));
            return $productSettings['numberOfFirewallRules'];
        }
    }

}
