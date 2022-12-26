<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Forms\Basics\BaseProductAppForm;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Config;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Password;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\Sections\HalfPageSection;

/**
 * Description of RebuildVM
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class SMTPFields extends BaseProductAppForm implements ClientArea, AdminArea
{

    protected $id    = 'SMTPConfigFields';
    protected $name  = 'SMTPConfigFields';
    protected $title = 'SMTPConfigFields';

    public function getClass()
    {
        
    }

    public function initContent()
    {
        $this->addClass('lu-row');
        $config = new FieldsProvider($this->getRequestValue('id'));

        $this->addSection($this->leftSection($config));
        $this->addSection($this->rightSection($config));
        $this->loadDataToForm();
    }

    private function leftSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('leftSection');
        $section->addField($this->getHostnameField($config->getField('hostname')));
        $section->addField($this->getPortField($config->getField('port')));
        $section->addField($this->getSslTypeField($config->getField('sslType')));

        return $section;
    }

    private function rightSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('rightSection');
        $section->addField($this->getUsernameField($config->getField('username')));
        $section->addField($this->getPasswordField($config->getField('password')));
        $section->addField($this->getFolderField($config->getField('folder')));
        return $section;
    }

    private function getHostnameField($value = null)
    {
        $field = new Text('packageconfigoption_hostname');
        $field->setValue($value);
        $field->addHtmlAttribute('autocomplete', 'off');
        return $field;
    }

    private function getPortField($value = null)
    {
        $field = new Text('packageconfigoption_port');
        $field->setValue($value);
        $field->addHtmlAttribute('autocomplete', 'off');
        return $field;
    }

    private function getSslTypeField($value = null)
    {
        $field = new Select('packageconfigoption_sslType');
        $field->setAvailableValues(Config::getSslType());
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getUsernameField($value = null)
    {
        $field = new Text('packageconfigoption_username');
        $field->setValue($value);
        $field->addHtmlAttribute('autocomplete', 'off');
        return $field;
    }

    private function getPasswordField($value = null)
    {
        $field = new Password('packageconfigoption_password');
        $field->setValue($value);
        $field->addHtmlAttribute('autocomplete', 'off');
        return $field;
    }
    
    private function getFolderField($value = null)
    {
        $field = new Text('packageconfigoption_folder');
        $field->setValue($value);
        $field->addHtmlAttribute('autocomplete', 'off');
        return $field;
    }
}
