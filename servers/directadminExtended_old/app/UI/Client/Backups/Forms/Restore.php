<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Forms;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Forms\Sections\CustomBaseSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;

class Restore extends BaseForm implements ClientArea
{
    protected $id    = 'restoreForm';
    protected $name  = 'restoreForm';
    protected $title = 'restoreForm';

    protected $createdSections = [];

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->setProvider(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Providers\Backups());

        $field = new Hidden('backup');

        $this->addField($field);

        $this->dataProvider->initData();
        $formData = $this->getFormData();

        $this->createField('domainLocal', $formData['domain'], 'domainSection');
        $this->createField('subdomainLocal', $formData['subdomain'], 'domainSection');

        $this->createField('forwarderLocal', $formData['forwarder'], 'emailSection');
        $this->createField('listLocal', $formData['list'], 'emailSection');
        $this->createField('autoresponderLocal', $formData['autoresponder'], 'emailSection');
        $this->createField('emailSettingsLocal', $formData['emailsettings'], 'emailSection');
        $this->createField('emailDataLocal', $formData['email_data'], 'emailSection');
        $this->createField('emailLocal', $formData['email'], 'emailSection');
        $this->createField('vacationLocal', $formData['vacation'], 'emailSection');

        $this->createField('ftpLocal', $formData['ftp'], 'ftpSection');
        $this->createField('ftpSettingsLocal', $formData['ftpsettings'], 'ftpSection');

        $this->createField('databaseLocal', $formData['database'], 'dataBaseSection');
        $this->createField('databaseDataLocal', $formData['database_data'], 'dataBaseSection');

        if(empty($this->createdSections)){
            $this->setConfirmMessage('confirmBackupRestore');
        }

        foreach($this->createdSections as $section){
            $this->addSection($section);
        }

        $this->loadDataToForm();
    }

    public function createField($fieldName, $status, $sectionName)
    {
        $field = new Fields\Switcher($fieldName);

        if($status != "show"){
            return;
        }
        $field->setDefaultValue('on');
        $section = $this->getAndCreateIfNotExist($sectionName);
        $section->addField($field);

    }

    private function getAndCreateIfNotExist($sectionName){
        if(array_key_exists($sectionName, $this->createdSections)){
            return $this->createdSections[$sectionName];
        }

        $newSection = new Sections\BaseSection($sectionName);
        $newSection->unsetShowTitle();
        $this->createdSections[$sectionName] = $newSection;

        return $newSection;
    }


}
