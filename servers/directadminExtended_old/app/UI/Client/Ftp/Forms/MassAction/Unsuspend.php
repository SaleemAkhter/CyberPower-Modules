<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Forms\MassAction;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ftp\Providers;

class Unsuspend extends BaseForm implements ClientArea
{
    protected $id    = 'unsuspendForm';
    protected $name  = 'unsuspendForm';
    protected $title = 'unsuspendForm';

    public function getDefaultActions()
    {
        return ['unsuspendMany'];
    }

    public function initContent()
    {
        $this->setFormType('unsuspendMany')
            ->setProvider(new Providers\Ftp())
            ->setConfirmMessage('unsuspendMany');
    }
}