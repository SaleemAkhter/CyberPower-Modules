<?php


namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Forms;


use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\SecurityRulesConstants;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Providers\FirewallRulesProvider;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Validators\IpValidator;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Validators\NotEmptyValidator;
use ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Validators\PortRangeValidator;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\FormConstants;
use ReflectionClass;

class AddForm extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new FirewallRulesProvider());

        $reflectionTypes = (new ReflectionClass('ModulesGarden\Servers\AwsEc2\App\UI\Client\FirewallRules\Helpers\RuleTypeEnum'))->getConstants();
        $types = [];
        foreach ($reflectionTypes as $type) {
            $types[$type[0]] = $type[1];
        }

        $name = new Hidden('instanceName');
        $this->addField($name);

        $typeSelect = new Select('type');
        $typeSelect->setAvailableValues($types);
        $typeSelect->addHtmlAttribute('reload_change', 'updateValuesAfterTypeChange');
        $typeSelect->setDescription('typeDescription');
        $this->addField($typeSelect);

        $rule = new Select('rule');
        $rule->setAvailableValues(SecurityRulesConstants::getRuleDirections());
        $rule->setDescription('ruleDescription');
        $this->addField($rule);

        $protocol = new Select('protocol');
        $protocol->setAvailableValues(SecurityRulesConstants::getProtocols());
        $protocol->setDescription('protocolDescription');
        $this->addField($protocol);

        $portRange = new Text('portRange');
        $portRange->setDescription('portRangeDescription')
                    ->addValidator(new NotEmptyValidator())
                    ->addValidator(new PortRangeValidator());
        $this->addField($portRange);

        $source = new Text('source');
        $source->setDescription('sourceDescription')
                ->addValidator(new IpValidator());
        $this->addField($source);

    }

}