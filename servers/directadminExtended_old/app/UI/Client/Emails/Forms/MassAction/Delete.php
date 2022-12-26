<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Emails\Providers;


class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'deleteManyForm';
    protected $name  = 'deleteManyForm';
    protected $title = 'deleteManyForm';

    public function getDefaultActions()
    {
        return ['deleteMany'];
    }

    public function initContent()
    {
        $this->setFormType('deleteMany')
            ->setProvider(new Providers\Emails())
            ->setConfirmMessage('confirmMassAddonDomainDelete');
    }
}