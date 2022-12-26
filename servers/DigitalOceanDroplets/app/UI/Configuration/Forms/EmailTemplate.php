<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Helpers\Config;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Sections\HalfPageSection;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class EmailTemplate extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'emailTemplate';
    protected $name  = 'emailTemplate';
    protected $title = 'emailTemplate';

    public function getClass()
    {
        
    }

    public function initContent()
    {
        $config = $this->getConfig();
        $this->addSection($this->leftSecion($config));
        $this->addSection($this->rightSection($config));
    }
    private function getConfig(){
        return new FieldsProvider($_REQUEST['id']);
    }
    
    private function leftSecion(FieldsProvider $config)
    {
        
        $section = new HalfPageSection('leftSection');
        $section->addField($this->getCreateTemplate($config->getField('createEmailTemplate', 'Digital Ocean Create Email')));
        return $section;
    }
    private function rightSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('rightSection');
        $section->addField($this->getPasswordsTemplate($config->getField('passwordEmailTemplate', 'Digital Ocean Password Reset Email')));
        return $section;
    }

    private function getCreateTemplate($value = null)
    {
        $field = new Select('packageconfigoption[createEmailTemplate]');
        $field->setAvalibleValues($this->getProductEmailTemplate());
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getPasswordsTemplate($value = null)
    {
        $field = new Select('packageconfigoption[passwordEmailTemplate]');
        $field->setAvalibleValues($this->getProductEmailTemplate());
        $field->setValue($value);
        $field->setSelectedValue($value);
        return $field;
    }

    private function getProductEmailTemplate()
    {
        $tempaltes = \ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\EmailTemplate::where('type', 'product')->get();
        return $this->parseTemaplate($tempaltes);
    }

    private function parseTemaplate($template)
    {
        $templateArray = [];
        foreach ($template as $single)
        {
            $templateArray[$single->name] = $single->name;
        }
        return $templateArray;
    }

}
