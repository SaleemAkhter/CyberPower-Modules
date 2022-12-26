<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Fields\PortField;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Firewall\Providers\FirewallRule;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\FormConstants;

class EditRuleForm extends BaseForm implements ClientArea, AdminArea
{
    protected $id    = 'editRuleForm';
    protected $name  = 'editRuleForm';
    protected $title = 'editRuleForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new FirewallRule());
        $this->addField(new Hidden('id'));

        $this->addField($this->getAppField());
        $this->addField($this->getTypeField());
        $this->addField($this->getProtocolField());
        $this->addField($this->getPortField());
        $this->addField($this->getAddressesField());


        $this->loadDataToForm();
    }
    private function getTypeField(){
        $field = new Select('type');
        $field->setAvalibleValues([
            'inbound_rules' => Lang::getInstance()->T('inboundRules'),
            'outbound_rules' => Lang::getInstance()->T('outboundRules'),
        ]);
        $field->setDescription('description');
        return $field;
    }

    private function getProtocolField(){
        $field = new Select('protocol');
        $field->setAvalibleValues([
            'tcp' => Lang::getInstance()->T('tcp'),
            'udp' => Lang::getInstance()->T('udp'),
            'icmp' => Lang::getInstance()->T('icmp'),
        ])->addHtmlAttribute('reload_change', 'hideInputIfValue');
        $field->setDescription('description');
        return $field;
    }

    private function getPortField(){
        $field = new PortField('port');
        $field->setPortValidator();
        $field->setDescription('description');
        return $field;
    }

    private function getAddressesField(){
        $field = new Text('addresses');
        $field->setDescription('description');

        return $field;
    }

    private function getAppField()
    {
        $field = new Select('apps');
        $field->setDescription('description');
        $field->addHtmlAttribute('reload_change', 'updateValuesAfterAppChange');

        $avalVals = [
            'custom'=>'Custom',
            'alltcp'=>'All TCP',
            'alludp'=>'All UDP',
            'http' => 'HTTP',
            'https'=>'HTTPS',
            'ssh' => 'SSH',
            'mysql'=>'MySQL',
            'dnstcp'=>'DNS (TCP)',
            'dnsudp'=>'DNS (UDP)',
            'icmp'=>'ICMP',
        ];

        $field->setAvalibleValues($avalVals);

        return $field;
    }
}