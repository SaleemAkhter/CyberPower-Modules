<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Forms;


use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\SecurityRulesConstants;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Providers\NetworkInterfaceProvider;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Validators\IpValidator;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Validators\NotEmptyValidator;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Validators\PortRangeValidator;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Switcher;

use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormConstants;
use ReflectionClass;

class DisassociateIpForm extends BaseStandaloneForm implements ClientArea
{
    protected $id    = 'disassociateIpForm';
    protected $name  = 'disassociateIpForm';
    protected $title = 'disassociateIpForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new NetworkInterfaceProvider());
        $description = new Text('description');
        $description->setDescription('descriptionDescription');
        $this->addField($description);

        $subnets=$this->dataProvider->getSubnets();
        $subnetlist = [];
        foreach ($subnets as $subnet) {
            $subnetlist[$subnet['SubnetId']] = $subnet['SubnetId']." ".$subnet['AvailabilityZone'];
        }

        $name = new Hidden('instanceName');
        $this->addField($name);

        $typeSelect = new Select('subnet');
        $typeSelect->setAvailableValues($subnetlist);
        // $typeSelect->addHtmlAttribute('reload_change', 'updateValuesAfterTypeChange');
        $typeSelect->setDescription('typeDescription');
        $this->addField($typeSelect);

        $autoassign = (new Switcher('autoAssign'))->setSwitchFieldLabel('privateIpAddress')->setSwitchFieldLabelSmalltext('privateIpAddressSmall');
        $autoassign->addGroupName('autoAssign');

        $this->addField($autoassign);
        $custom = new Switcher('custom');
        $custom->addGroupName('custom');
        $this->addField($custom);
        $ipv4address = new Text('ipv4address');
        $this->addField($ipv4address);

    }

}
