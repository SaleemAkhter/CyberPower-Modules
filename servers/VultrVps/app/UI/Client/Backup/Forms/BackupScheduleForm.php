<?php
/* * ********************************************************************
*  VultrVps Product developed. (27.03.19)
* *
*
*  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
*  CONTACT                        ->       contact@modulesgarden.com
*
*
* This software is furnished under a license and may be used and copied
* only  in  accordance  with  the  terms  of such  license and with the
* inclusion of the above copyright notice.  This software  or any other
* copies thereof may not be provided or otherwise made available to any
* other person.  No title to and  ownership of the  software is  hereby
* transferred.
*
*
* ******************************************************************** */

namespace ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Forms;

use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Providers\BackupProvider;
use ModulesGarden\Servers\VultrVps\App\UI\Client\Backup\Providers\BackupScheduleProvider;
use ModulesGarden\Servers\VultrVps\App\UI\Validators\BackupValidator;
use ModulesGarden\Servers\VultrVps\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms\Fields\Text;


class BackupScheduleForm extends BaseForm implements ClientArea
{
    protected $formData=[];
    public function initContent()
    {
        $this->initIds('backupScheduleForm');
        $this->setFormType('update');
        $this->setProvider(new BackupScheduleProvider());
        $this->setInternalAlertMessage('confirmCreate');
        $this->setInternalAlertMessageType(AlertTypesConstants::INFO);
        //init data
        $this->formData = $this->request->get('formData');
        if(!$this->request->get('formData')){
            $this->loadProvider();
            $this->dataProvider->initData();
            $this->formData = $this->dataProvider->getData();
        }
        $this->initFields();
        $this->loadDataToForm();

    }

    public function getAllowedActions()
    {
        return ['update'];
    }

    private function initFields()
    {
        //type
        $field = new Select('type');
        $field->addHtmlAttribute('bi-event-change', "reloadVueModal");
        $this->addField($field);
        //dow type:{weekly}
        if(in_array($this->formData['type'],['weekly'])){
            $field = new Select('dow');
            $this->addField($field);
        }
        //dom type:{monthly}
        if(in_array($this->formData['type'],['monthly'])){
            $field = new Select('dom');
            $this->addField($field);
        }
        //hour
        $field = new Select('hour');
        $this->addField($field);
    }
}