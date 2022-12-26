<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Basics\BaseProductAppForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Sections\HalfPageSection;

/**
 * Description of RebuildVM
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class EmailTemplate extends BaseProductAppForm implements ClientArea, AdminArea
{

    protected $id    = 'emailTemplate';
    protected $name  = 'emailTemplate';
    protected $title = 'emailTemplate';

    public function getClass()
    {
        
    }

    public function initContent()
    {
        $this->addClass('lu-row');
        $config = $this->getConfig();
        $this->addSection($this->leftSecion($config));
        $this->addSection($this->rightSection($config));
    }
    private function getConfig(){

        return new FieldsProvider($this->getRequestValue('id'));
    }
    
    private function leftSecion(FieldsProvider $config)
    {
        $section = new HalfPageSection('leftSection');
        $section->addField($this->getCreateTemplate($config->getField('createEmailTemplate', 'OVH VPS Has Been Created')));
        $section->addField($this->getReinstallTemplate($config->getField('reinstallEmailTemplate', 'OVH VPS Reinstalled')));

        return $section;
    }
    private function rightSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('rightSection');
        $section->addField($this->getRescueTemplate($config->getField('rescueEmailTemplate', 'OVH VPS Rescue Reboot')));
        return $section;
    }

    private function getCreateTemplate($value = null)
    {
        $field = new Select('packageconfigoption[createEmailTemplate]');
        $field->setAvailableValues($this->getProductEmailTemplate());
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getReinstallTemplate($value = null)
    {
        $field = new Select('packageconfigoption[reinstallEmailTemplate]');
        $field->setAvailableValues($this->getProductEmailTemplate());
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getRescueTemplate($value = null)
    {
        $field = new Select('packageconfigoption[rescueEmailTemplate]');
        $field->setAvailableValues($this->getProductEmailTemplate());
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getProductEmailTemplate()
    {
        $tempaltes = \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\EmailTemplate::where('type', 'product')->get();
        return $this->parseTemplate($tempaltes);
    }

    private function parseTemplate($template)
    {
        $templateArray = [];
        foreach ($template as $single)
        {
            $templateArray[$single->name] = $single->name;
        }
        asort($templateArray);
        return $templateArray;
    }

}
