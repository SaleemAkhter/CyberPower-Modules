<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Forms;


use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\SecurityRulesConstants;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\ElasticIps\Providers\AssociateIpProvider;
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

class AssociateIpForm extends BaseStandaloneForm implements ClientArea
{
    protected $id    = 'associateIpForm';
    protected $name  = 'associateIpForm';
    protected $title = 'associateIpForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setContainerWidth(6)
            ->setContainerClasses(['shadow1','p-20'])
            ->setProvider(new AssociateIpProvider());
        $resourceType = new Radio('resourceType');
        $resourceType->setDescription('descriptionDescription');
        $resourceType->addHtmlAttribute('hidefields', 'updateValuesAfterResourceType');
        $resourceType->setAvailableValues([
            'Instance'=>'Current Instance',
            'networkInterface'=>'Network interface'
        ])->setDefaultValue(["Instance"]);
        $this->addField($resourceType);

        $interfaces=$this->dataProvider->getNetworkInterfaces();
        $privateIplist=["-1"=>"Choose a private IP address"];
        $interfacelist = ["-1"=>"Choose a network interface"];
        foreach ($interfaces as $interface) {
            $interfacelist[$interface['interfaceid']] = $interface['interfaceid'];
            $privateIplist[$interface['privateip']]=$interface['privateip'];
        }
        $typeSelect = new Select('networkinterface');
        $typeSelect->setAvailableValues($interfacelist);
        $typeSelect->addHtmlAttribute('show-on', 'networkInterface');

        $this->addField($typeSelect);

        $typeSelect = new Select('privateip');
        $typeSelect->setAvailableValues($privateIplist)->setHelpText('privateiphelp');

        $this->addField($typeSelect);

        $autoassign = (new Switcher('reassociate'))->setSwitchFieldLabel('reassociate')->setSwitchFieldLabelSmalltext('reassociateSmall');
        $autoassign->addGroupName('reassociate');

        $this->addField($autoassign);
    }

}
