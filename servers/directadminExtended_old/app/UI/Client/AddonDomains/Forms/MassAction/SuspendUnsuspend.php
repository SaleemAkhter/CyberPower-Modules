<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Forms\MassAction;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Text;

class SuspendUnsuspend extends BaseForm implements ClientArea
{
    protected $id    = 'suspendForm';
    protected $name  = 'suspendForm';
    protected $title = 'suspendForm';

    public function getDefaultActions()
    {
        return ['suspendUnsuspendMany'];
    }

    public function initContent()
    {
        $this->setFormType('suspendUnsuspendMany')
            ->setProvider(new Providers\AddonDomains())
            ->setConfirmMessage('suspendUnsuspendMany');
    }
}