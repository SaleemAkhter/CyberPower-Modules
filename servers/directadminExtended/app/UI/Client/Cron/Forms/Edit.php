<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Providers;

class Edit extends BaseForm implements ClientArea
{
    protected $id    = 'editForm';
    protected $name  = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new Providers\CronEdit());

        $commonSettings = (new Fields\Select('commonSettings'))
            ->addHtmlAttribute('bi-event-change', 'initReloadModal');


        $hiddenID   = new Fields\Hidden('hiddenID');
        $minute     = (new Fields\Text('minute'))->setDefaultValue(0);
        $hour       = (new Fields\Text('hour'))->setDefaultValue(0);
        $day        = (new Fields\Text('day'))->setDefaultValue(0);
        $month      = (new Fields\Text('month'))->setDefaultValue(0);
        $week       =( new Fields\Text('week'))->setDefaultValue(0);
        $command    = new Fields\Text('command');

        $this->addInternalAlert('description', AlertTypesConstants::INFO)
            ->addField($commonSettings)
            ->addField($minute)
            ->addField($hour)
            ->addField($day)
            ->addField($month)
            ->addField($week)
            ->addField($command)
            ->addField($hiddenID)
            ->loadDataToForm();
    }

    protected function reloadFormStructure()
    {
        if($this->getRequestValue('formData')['commonSettings'] !== '--')
        {
            unset($this->fields['minute']);
            unset($this->fields['hour']);
            unset($this->fields['day']);
            unset($this->fields['month']);
            unset($this->fields['week']);
        }

        $this->dataProvider->reload();
        $this->loadDataToForm();
    }

}
