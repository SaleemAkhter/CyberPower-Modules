<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\NetworkInterfaces\Forms;


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
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Radio;
use function ModulesGarden\Servers\AwsEc2\Core\Helper\sl;

class AddNetworkInterfaceForm extends BaseStandaloneForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setContainerWidth(6)
            ->setContainerClasses(['shadow1','p-20'])
            ->setProvider(new NetworkInterfaceProvider());

        $description = new Text('description');
        $description->setHelpText('descriptionDescription');
        $this->addField($description);

        $subnets=$this->dataProvider->getSubnets();
        $subnetlist = ["-1"=>"Select subnet"];

        foreach ($subnets as $subnet) {
            $subnetlist[$subnet] = $subnet;
        }

        $name = new Hidden('instanceName');
        $this->addField($name);

        $typeSelect = new Select('subnet');
        $typeSelect->setAvailableValues($subnetlist)->setValue('-1');
        // $typeSelect->addHtmlAttribute('reload_change', 'updateValuesAfterTypeChange');
        $typeSelect->setHelpText('subnethelptext');
        $this->addField($typeSelect);


        $resourceType = new Radio('privateIpAddress');
        $resourceType->setDescription('descriptionDescription');
        $resourceType->addHtmlAttribute('hidefields', 'updateValuesAfterResourceType');
        $resourceType->setAvailableValues([
            'autoAssign'=>sl('lang')->T('autoAssign'),
            'custom'=>sl('lang')->T('custom')
        ])->setDefaultValue(["autoAssign"]);
        $this->addField($resourceType);





        // $autoassign = (new Switcher('autoAssign'))->setSwitchFieldLabel('privateIpAddress')->setSwitchFieldLabelSmalltext('privateIpAddressSmall');
        // $autoassign->addGroupName('autoAssign');

        // $this->addField($autoassign);
        // $custom = new Switcher('custom');
        // $custom->addGroupName('custom');
        // $this->addField($custom);
        $ipv4address = new Text('ipv4address');
        $ipv4address->addHtmlAttribute('show-on', 'custom');
        $this->addField($ipv4address);

    }

}
