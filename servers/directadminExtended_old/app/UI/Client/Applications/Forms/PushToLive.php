<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Forms;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

class PushToLive extends BaseForm implements ClientArea
{
    protected $id    = 'pushToLiveForm';
    protected $name  = 'pushToLiveForm';
    protected $title = 'pushToLiveForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
            ->setProvider(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers\ApplicationsPushToLive())
            ->setConfirmMessage('confirmApplicationPushToLive');

        $field = new Hidden('id');

        $this->addField($field)
            ->loadDataToForm();
    }
}