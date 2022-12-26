<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Forms;

use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\FieldsProvider;

use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Fields\CustomSelect;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Helpers\Config;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Select2vue;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Select2vueByValueOnly;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Fields\Switcher;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\Sections\HalfPageSection;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ClientAreaFeatures extends BaseForm implements ClientArea, AdminArea
{

    protected $id    = 'ClientAreaFeatures';
    protected $name  = 'ClientAreaFeatures';
    protected $title = 'ClientAreaFeatures';

    public function getClass()
    {
        
    }

    public function initContent()
    {
        $config = new FieldsProvider($_REQUEST['id']);


        $this->addSection($this->leftSecion($config))
             ->addSection($this->rightSection($config));
        $this->loadDataToForm();
    }
    
    private function rightSection(FieldsProvider $config)
    {
        $section = new HalfPageSection('rightSection');
        $section->addField($this->getClientAreaChangeHostname($config->getField('clientAreaChangeHostname')))
                ->addField($this->getClientAreaAllowOnlyInitialImageRebuild($config->getField('clientAreaOnlyInitialImageRebuild')))
                ->addField($this->getClientAreaPowerOn($config->getField('clientAreaPowerOn')))
                ->addField($this->getClientAreaPowerOff($config->getField('clientAreaPowerOff')))
                ->addField($this->getClientAreaShutDown($config->getField('clientAreaShutDown')))
                ->addField($this->getClientAreaReboot($config->getField('clientAreaReboot')))
                ->addField($this->getClientAreaResetPasword($config->getField('clientAreaResetPassword')));
                
        
        return $section;
    }

    private function leftSecion(FieldsProvider $config)
    {
        $section = new HalfPageSection('leftSection');
        $section->addField($this->getClientAreaRebuild($config->getField('clientAreaRebuild')))
                ->addField($this->getClientAreaSnapshots($config->getField('clientAreaSnapshots')))
                ->addField($this->getClientAreaTask($config->getField('clientAreaTask')))
                ->addField($this->getClientAreaBackups($config->getField('clientAreaBackups')))
                ->addField($this->getClientAreaKernel($config->getField('clientAreaKernel')))
                ->addField($this->getClientAreaFirewall($config->getField('clientAreaFirewall')))
                ->addField($this->getClientAreaAvailableImages($config->getField('clientAreaAvailableImages')));
                
        return $section;
    }


    private function getClientAreaRebuild($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaRebuild]');
        $field->setDescription('clientAreaRebuildDescription')
                ->setValue($value);
        return $field;
    }

    private function getClientAreaSnapshots($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaSnapshots]');
        $field->setDescription('clientAreaSnapshotsDescription')
            ->setValue($value);
        return $field;
    }

    private function getClientAreaTask($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaTask]');
        $field->setDescription('clientAreaTaskDescription')
            ->setValue($value);
        return $field;
    }
    private function getClientAreaBackups($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaBackups]');
        $field->setDescription('clientAreaBackupsDescription')
            ->setValue($value);
        return $field;
    }
    private function getClientAreaResetPasword($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaResetPassword]');
        $field->setDescription('clientAreaResetPasswordDescription')
            ->setValue($value);
        return $field;
    }
    private function getClientAreaChangeHostname($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaChangeHostname]');
        $field->setDescription('clientAreaChangeHostnameDescription')
            ->setValue($value);
        return $field;
    }
    private function getClientAreaKernel($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaKernel]');
        $field->setDescription('clientAreaKernelDescription')
            ->setValue($value);
        return $field;
    }    private function getClientAreaFirewall($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaFirewall]');
        $field->setDescription('clientAreaFirewallDescription')
            ->setValue($value);
        return $field;
    }
    private function getClientAreaPowerOn($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaPowerOn]');
        $field->setDescription('clientAreaPowerOnDescription')
                ->setValue($value);
        return $field;
    }
    private function getClientAreaPowerOff($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaPowerOff]');
        $field->setDescription('clientAreaPowerOffDescription')
                ->setValue($value);
        return $field;
    }
    private function getClientAreaShutDown($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaShutDown]');
        $field->setDescription('clientAreaShutDownDescription')
                ->setValue($value);
        return $field;
    }
    private function getClientAreaReboot($value = null)
    {
        $field = new Switcher('packageconfigoption[clientAreaReboot]');
        $field->setDescription('clientAreaRebootDescription')
                ->setValue($value);
        return $field;
    }

    private function getClientAreaAvailableImages($value = null)
    {

        $field = new CustomSelect('packageconfigoption[clientAreaAvailableImages][]');
        $field->setDescription('clientAreaAvailableImagesDescription')
            ->setAvalibleValues(Config::getImagesList())
            ->setValue(['value' => $value === '[]' ? null : json_decode($value)])
            ->enableMultiple();

        return $field;
    }

    private function getClientAreaAllowOnlyInitialImageRebuild( $value = null )
    {
        $field = new Switcher('packageconfigoption[clientAreaOnlyInitialImageRebuild]');
        $field->setDescription('clientAreaOnlyInitialImageRebuildDescription');
        $field->setValue($value);
        return $field;
    }

}
