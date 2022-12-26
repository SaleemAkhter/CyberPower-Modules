<?php


namespace ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Forms;

use ModulesGarden\Servers\HetznerVps\App\Helpers\Validators\IpValidator;
use ModulesGarden\Servers\HetznerVps\App\UI\Firewall\Providers\RuleProvider;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Fields\Textarea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\HalfPageSection;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\RawSection;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections\SectionLuRow;

class AddRuleForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id = 'ruleAddForm';
    protected $name = 'ruleAddForm';
    protected $title = 'ruleAddForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new RuleProvider());

        $this->loadProvider();
//        $this->addSection($this->mainSection());

        $this->loadDataToForm();
        $this->reloadFormStructure();
    }

    public function mainSection()
    {
        $halfPageSectionOne = (new HalfPageSection('leftSection'));
        $halfPageSectionTwo = (new HalfPageSection('rightSection'));
        $row                = (new SectionLuRow('rowSection'));

        $label = (new Text('label'))
            ->initIds('label');

        $halfPageSectionTwo->addField($label);

        $this->addToRow($row, $halfPageSectionOne);
        $this->addToRow($row, $halfPageSectionTwo);

        return $row;
    }

    public function rulesSection($data)
    {
        $row                = (new SectionLuRow('rulesRowSection'));
        $baseSection        = (new RawSection('baseSection'));
        $mainSection        = (new RawSection('mainRawSection'));
        $halfPageSectionOne = (new HalfPageSection('rulesLeftSection'));
        $halfPageSectionTwo = (new HalfPageSection('rulesRightSection'));

        $direction = (new Select('direction'))
            ->initIds('direction')
            ->addHtmlAttribute('bi-event-change', 'reloadVueModal')
            ->notEmpty();
        $protocol  = (new Select('protocol'))
            ->initIds('protocol')
            ->addHtmlAttribute('bi-event-change', 'reloadVueModal')
            ->notEmpty();

        $port = (new Text('port'))
            ->initIds('port')
            ->setDescription('portDescription')
            ->notEmpty();

        $sourceIp      = (new Textarea('sourceIp'))
            ->initIds("sourceIp")
            ->setDescription('ipDescription')
            ->notEmpty()
            //->addValidator(new IpValidator())
        ;
        $destinationIp = (new Textarea('destinationIp'))
            ->initIds("destinationIp")
            ->setDescription('ipDescription')
            ->notEmpty()
            //->addValidator(new IpValidator())
        ;

        $halfPageSectionOne
            ->addField($protocol)//            ->addField($port)
        ;
        $halfPageSectionTwo
            ->addField($direction);

        $this->addToRow($row, $halfPageSectionOne);
        $this->addToRow($row, $halfPageSectionTwo);

        $chosenProtocol = $data['protocol'];

        if ($chosenProtocol == 'icmp' || $chosenProtocol == 'esp' || $chosenProtocol == 'gre')
        {
            $port->setValue(null);
            $baseSection->removeField($port);
        }
        else
        {
            $baseSection->addField($port);
        }

        if ($data['direction'] != 'out')
        {
            $baseSection->removeField($destinationIp);
            $baseSection->addField($sourceIp);
        }
        else
        {
            $baseSection->removeField($sourceIp);
            $baseSection->addField($destinationIp);
        }

        $mainSection
            ->addSection($row)
            ->addSection($baseSection);

        return $mainSection;
    }

    public function ipField($data)
    {
        $halfPageSectionTwo = (new HalfPageSection('rightSection'));
        $row                = (new SectionLuRow('rowSection'));

        $sourceIp      = (new Text('sourceIp'))
            ->initIds("sourceIp");
        $destinationIp = (new Text('destinationIp'))
            ->initIds("destinationIp");

        if ($data['direction'] != 'out')
        {
            $halfPageSectionTwo->addField($sourceIp);
        }
        else
        {
            $halfPageSectionTwo->addField($destinationIp);
        }

        $this->addToRow($row, $halfPageSectionTwo);

        return $row;
    }

    public function addToRow($row, $section)
    {
        return $row->addSection($section);
    }

    public function reloadFormStructure()
    {
        $this->dataProvider->read();
        $data = $this->getFormData();
        $this->addSection($this->rulesSection($data));
        $this->loadDataToForm();
    }
}