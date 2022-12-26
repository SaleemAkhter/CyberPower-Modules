<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Forms;


use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\SecurityRulesConstants;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Providers\AssociateIpProvider;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Validators\IpValidator;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Validators\NotEmptyValidator;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Validators\PortRangeValidator;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Radio;

use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Switcher;

use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormConstants;
use ReflectionClass;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Readonlyfield;
use ModulesGarden\Servers\AwsEc2\App\Models\ElasticIp;
use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;

class AssociateIpForm extends BaseStandaloneForm implements ClientArea
{
    protected $id    = 'associateIpForm';
    protected $name  = 'associateIpForm';
    protected $title = 'associateIpForm';

    public function initContent()
    {
        $productId = $this->getWhmcsParamByKey('packageid');
        $serviceId = $this->getWhmcsParamByKey('serviceid');
        $apiInstance = new ClientWrapper($productId, $serviceId);
        $this->setFormType(FormConstants::CREATE)
            ->setContainerWidth(6)
            ->setContainerClasses(['shadow1','p-20'])
            ->setProvider(new AssociateIpProvider());
        $this->dataProvider->read();

        $data=$this->dataProvider->getData();
        $networkinterface = new Readonlyfield('networkinterface');
        $networkinterface->setValue($data['interfaceid']);
        $this->addField($networkinterface);

        $ipsdata=ElasticIp::where('service_id',$serviceId)->get();
        $privateIplist=["-1"=>"Choose a private IPV4 address"];
        $elasticiplist = ["-1"=>"Choose an Elastic IP address"];
        if($ipsdata->count()){
            $ips=$ipsdata->pluck('elastic_ip')->toArray();
            $instanceName = $this->getWhmcsParamByKey('domain');
            $elasticips = $apiInstance->describeAddresses($ips);
            foreach ( $elasticips as $key => $ip) {
                $networkInterface=@$ip['NetworkInterfaceId'];
                if(isset($ip['PrivateIpAddress'])){
                    $privateIplist[$ip['PrivateIpAddress']]= $ip['PrivateIpAddress'];
                }
                $elasticiplist[$ip['AllocationId']]=$ip['PublicIp'];

            }
        }

        $typeSelect = new Select('elasticip');
        $typeSelect->setValue("");
        $typeSelect->notEmpty();
        $typeSelect->setAvailableValues($elasticiplist);

        $this->addField($typeSelect);

        $typeSelect = new Select('privateip');
        $typeSelect->setAvailableValues($privateIplist);

        $this->addField($typeSelect);


    }

}
