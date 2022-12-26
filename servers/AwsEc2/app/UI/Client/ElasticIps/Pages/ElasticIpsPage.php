<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Pages;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Buttons\Add;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Buttons\Associate;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Buttons\Disassociate;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Buttons\Delete;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\SecurityRulesConstants;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\Traits\Lang;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\DataTable;
use Illuminate\Database\Capsule\Manager as Capsule;
use ModulesGarden\Servers\AwsEc2\App\Models\ElasticIp;
class ElasticIpsPage extends DataTable implements AdminArea, ClientArea
{
    use Lang;
    protected $id    = 'elasticIpsPage';
    protected $name  = 'elasticIpsPage';
    protected $title = 'elasticIpsTitle';

    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('ipv4address'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('allocationid'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('privateip'))->setOrderable()->setSearchable(true));
        // $this->addColumn((new Column('networkinterface'))->setOrderable()->setSearchable(true));
    }

    public function initContent()
    {

        $params=['id'=>$_GET['id']];
        $btn = new Add();
        $btn->setRawUrl("cart.php?gid=addons")->setRedirectParams($params)->setTitle("addElasticIp");



        $this->addButton($btn);
        $this->addActionButton(new Associate());
        $this->addActionButton(new Disassociate());
        $this->addActionButton(new Delete());
    }
    public function getAssociateButton()
    {
        $button = new ButtonRedirect('loginAsButton');

        $button->setRawUrl($this->getLoginURL())
            ->setIcon('lu-btn__icon lu-zmdi lu-zmdi-square-right')
            ->setRedirectParams(['actionElementId'=>':id','doLogin'=>'1']);

        return $button;
    }
    public function getDissociateButton()
    {
        $button = new ButtonRedirect('loginAsButton');

        $button->setRawUrl($this->getLoginURL())
            ->setIcon('lu-btn__icon lu-zmdi lu-zmdi-square-right')
            ->setRedirectParams(['actionElementId'=>':id','doLogin'=>'1']);

        return $button;
    }

    public function loadData()
    {
        $this->loadLang();

        $productId = $this->getWhmcsParamByKey('packageid');
        $serviceId = $this->getWhmcsParamByKey('serviceid');
        $apiInstance = new ClientWrapper($productId, $serviceId);
        $data = [];
        $ipsdata=ElasticIp::where('service_id',$serviceId)->get();

        if($ipsdata->count()){
            $ips=$ipsdata->pluck('elastic_ip')->toArray();
            $instanceName = $this->getWhmcsParamByKey('domain');
            $elasticips = $apiInstance->describeAddresses($ips);
            // debug($elasticips);die();
            foreach ( $elasticips as $key => $ip) {
                $networkInterface=@$ip['NetworkInterfaceId'];
                $data []=[
                    'name' => "",
                    'ipv4address' => $ip['PublicIp'],
                    'allocationid' => $ip['AllocationId'],
                    'privateip' => @$ip['PrivateIpAddress'],
                    'networkinterface'=>$networkInterface,
                    'associated'=>(!empty($networkInterface)),
                    'associationId'=>@$ip['AssociationId']
                ];
            }
        }else{
            $elasticips=[];
        }




        foreach ($data as &$dataEntity)
        {
            $dataEntity['id'] =
                base64_encode(
                    json_encode([
                        'name' => $dataEntity['name'],
                        'ipv4address' => $dataEntity['ipv4address'],
                        'allocationid' => $dataEntity['allocationid'],
                        'privateip' => $dataEntity['privateip'],
                        'networkinterface'=>$dataEntity['networkinterface'],
                        'associationId'=>$dataEntity['associationId']
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
