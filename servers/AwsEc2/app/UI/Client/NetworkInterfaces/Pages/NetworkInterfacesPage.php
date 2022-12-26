<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Pages;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Buttons\Add;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Buttons\Delete;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Buttons\Associate;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Buttons\Disassociate;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Buttons\Attach;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Buttons\Detach;

use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\SecurityRulesConstants;
use ModulesGarden\Servers\AwsEc2\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\AwsEc2\Core\Traits\Lang;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\DataTable\DataTable;
use Illuminate\Database\Capsule\Manager as Capsule;
use ModulesGarden\Servers\AwsEc2\Core\Helper\BuildUrl;
use ModulesGarden\Servers\AwsEc2\App\Models\NetworkInterface;

class NetworkInterfacesPage extends DataTable implements AdminArea, ClientArea
{
    use Lang;
    protected $id    = 'networkInterfacesPage';
    protected $name  = 'networkInterfacesPage';
    protected $title = 'networkInterfacesTitle';

    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('interfaceid'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('subnet'))->setOrderable()->setSearchable(true));
        // $this->addColumn((new Column('vpc'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('zone'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('securitygroup'))->setOrderable()->setSearchable(true));
        // $this->addColumn((new Column('instanceid'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('status'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('publicip'))->setOrderable()->setSearchable(true));
        $this->addColumn((new Column('privateip'))->setOrderable()->setSearchable(true));
    }

    public function initContent()
    {
        $params=['id'=>$_GET['id']];
        $btn = new Add();
        $btn->setRawUrl(BuildUrl::getUrl('networkInterfaces', 'add', ['id'=>$_GET['id']]))->setRedirectParams($params);

        $this->addButton($btn);
        $this->addActionButton(new Associate());
        $this->addActionButton(new Disassociate());
        $this->addActionButton(new Attach());
        $this->addActionButton(new Detach());
        $this->addActionButton(new Delete());
    }

    public function loadData()
    {
        $this->loadLang();

        $productId = $this->getWhmcsParamByKey('packageid');
        $serviceId = $this->getWhmcsParamByKey('serviceid');

        $apiInstance = new ClientWrapper($productId, $serviceId);
        $instanceName = $this->getWhmcsParamByKey('domain');
        $instance = $apiInstance->describeInstances(['InstanceIds' => [$this->getWhmcsParamByKey('customfields')['InstanceId']]]);
        $instanceData=$instance->get("Reservations")['0']['Instances']['0'];
        $subnet=$instanceData['SubnetId'];
        $ids=NetworkInterface::where('service_id',$serviceId)->pluck('allocation_id')->toArray();

        $data=[];
        if(!empty($ids)){
            $interfacesdata= $apiInstance->describeNetworkInterfaces([
                'NetworkInterfaceIds'=>$ids
            ]);

            $interfaces=$interfacesdata->get("NetworkInterfaces");
// debug($interfaces);die();
            // $sgroup = $apiInstance->getSecurityGroup($securityGroupName);
            // $inboundRules = $sgroup['IpPermissions'];
            // $outboundRules = $sgroup['IpPermissionsEgress'];


            // $this->parseReceivedData($data,$inboundRules, SecurityRulesConstants::RULE_INBOUND);
            // $this->parseReceivedData($data,$outboundRules, SecurityRulesConstants::RULE_OUTBOUND);

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
                $associationId='';
                if(isset($interface['Association'])){
                    $publicip=$interface['Association']['PublicIp'];
                    $associationId=$interface['Association']['AssociationId'];
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
                        'securitygroup'=>$securitygroup ,
                        'interfacetype'=>$interface['InterfaceType'],
                        // 'instanceid'=>$interface['instanceid'],
                        'status'=>$interface['Status'],
                        'publicip'=>$publicip,
                        'privateip'=>$interface['PrivateIpAddress'],
                        'associated'=>!(empty($interface['Association'])),
                        'associationId'=>$associationId,
                        'attached'=>!(empty($interface['Attachment'])),

                    ];
                }
                foreach ($data as &$dataEntity)
                {
                    $dataEntity['id'] =
                    base64_encode(
                        json_encode([
                            'name' => $dataEntity['name'],
                            'interfaceid' => $dataEntity['interfaceid'],
                            'subnet' =>  $dataEntity['subnet'],
                            'zone' =>  $dataEntity['zone'],
                            'securitygroup'=> $dataEntity['securitygroup'],
                            'InterfaceType'=> $dataEntity['InterfaceType'],
                            'status'=> $dataEntity['status'],
                            'publicip'=> $dataEntity['publicip'],
                            'privateip'=> $dataEntity['privateip'],
                            'associated'=>$dataEntity['associated'],
                            'associationId'=>$dataEntity['associationId'],
                            'attached'=>$dataEntity['associated'],
                        ]));
                }
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
