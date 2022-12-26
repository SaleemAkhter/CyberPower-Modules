<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Forms;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

class Suspend extends BaseForm implements ClientArea
{
    protected $id    = 'toggleSuspendForm';
    protected $name  = 'toggleSuspendForm';
    protected $title = 'toggleSuspendForm';

    public function getDefaultActions()
    {
        return ['toggleSingleSuspend'];
    }
    public function initContent()
    {
        $this->setFormType('toggleSingleSuspend')
            ->setProvider(new Providers\Ftp())
            ->setConfirmMessage('confirmFtpSuspend');

        $field = new Hidden('login');
        $isSuspended = new Hidden('isSuspended');

        $this->addField($field)
            ->addField($isSuspended)
            ->loadDataToForm();
    }
}