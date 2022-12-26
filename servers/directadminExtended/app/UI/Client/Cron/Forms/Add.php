<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Cron\Providers;

class Add extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->setProvider(new Providers\Cron());

        $commonSettings = (new Fields\Select('commonSettings'))
            ->addHtmlAttribute('bi-event-change', 'initReloadModal');


        $minute     = new Fields\Text('minute');
        $hour       = new Fields\Text('hour');
        $day        = new Fields\Text('day');
        $month      = new Fields\Text('month');
        $week       = new Fields\Text('week');
        $command    = new Fields\Text('command');

        $this->addInternalAlert('description', AlertTypesConstants::INFO)
            ->addField($commonSettings)
            ->addField($minute)
            ->addField($hour)
            ->addField($day)
            ->addField($month)
            ->addField($week)
            ->addField($command)
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
