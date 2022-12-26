<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Dedicated;

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

    private function getConfig()
    {
        return new FieldsProvider($this->getRequestValue('id'));
    }

    private function leftSecion(FieldsProvider $config)
    {
        $section = new HalfPageSection('leftSection');
        $section->addField($this->getCreateTemplate($config->getField('reinstallEmailTemplate', 'Ovh Dedicated Reinstall Email')));
        return $section;
    }

    private function rightSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('rightSection');
        $section->addField($this->getPasswordsTemplate($config->getField('rebootEmailTemplate', 'Ovh Dedicated Reboot Email')));
        return $section;
    }

    private function getCreateTemplate($value = null)
    {
        $field = new Select('packageconfigoption[reinstallEmailTemplate]');
        $field->setAvailableValues($this->getProductEmailTemplate());
        $field->setValue($value);
        $field->setSelectedValue($value);

        return $field;
    }

    private function getPasswordsTemplate($value = null)
    {
        $field = new Select('packageconfigoption[rebootEmailTemplate]');
        $field->setAvailableValues($this->getProductEmailTemplate());
        $field->setValue($value);
        $field->setSelectedValue($value);

        return $field;
    }

    private function getProductEmailTemplate()
    {
        $templates = \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Models\Whmcs\EmailTemplate::where('type', 'product')->get()->pluck('name', 'name')->toArray();
        asort($templates);

        return $templates;
    }


}
