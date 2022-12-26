<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Helpers\Config;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Password;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Text;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Sections\HalfPageSection;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class SMTPFields extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'SMTPConfigFields';
    protected $name  = 'SMTPConfigFields';
    protected $title = 'SMTPConfigFields';

    public function getClass()
    {
        
    }

    public function initContent()
    {
        $config = new FieldsProvider($_REQUEST['id']);

        $this->addSection($this->leftSecion($config));
        $this->loadDataToForm();
    }

    private function leftSecion(FieldsProvider $config)
    {
        $section = new HalfPageSection('leftSection');
        $section->addField($this->getHostnameField($config->getField('hostname')));
        $section->addField($this->getPortField($config->getField('port')));
        $section->addField($this->getSslTypeField($config->getField('sslType')));
        $section->addField($this->getNoValidateField($config->getField('noValidateCertificate')));
        $section->addField($this->getUsernameField($config->getField('username')));
        $section->addField($this->getPasswordField($config->getField('password')));
        $section->addField($this->getFolderField($config->getField('folder')));
        

        return $section;
    }

    private function getHostnameField($value = null)
    {
        $field = new Text('packageconfigoption[hostname]');
        $field->setValue($value);
        return $field;
    }

    private function getPortField($value = null)
    {
        $field = new Text('packageconfigoption[port]');
        $field->setValue($value);
        return $field;
    }

    private function getSslTypeField($value = null)
    {
        $field = new Select('packageconfigoption[sslType]');
        $field->setAvalibleValues(Config::getSslType());
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getNoValidateField($value = null)
    {

        $field = new Switcher('packageconfigoption[noValidateCertificate]');
        $field->setValue($value);
        $field->setDescription('description');
        $field->setDefaultValue('off');
        return $field;
    }

    private function getUsernameField($value = null)
    {
        $field = new Text('packageconfigoption[username]');
        $field->setValue($value);
        return $field;
    }

    private function getPasswordField($value = null)
    {
        $field = new Password('packageconfigoption[password]');
        $field->setValue($value);
        return $field;
    }
    
    private function getFolderField($value = null)
    {
        $field = new Text('packageconfigoption[folder]');
        $field->setValue($value);
        return $field;
    }

}
