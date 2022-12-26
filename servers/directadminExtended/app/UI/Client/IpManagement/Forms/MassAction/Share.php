<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Providers;


class Share extends BaseForm implements ClientArea
{
    protected $id    = 'massShareForm';
    protected $name  = 'massShareForm';
    protected $title = 'massShareForm';

    public function getDefaultActions()
    {
        return ['markSharedMany'];
    }

    public function initContent()
    {
        $this->setFormType('markSharedMany')
            ->setProvider(new Providers\IpManagement())
            ->setConfirmMessage('markSharedMany');
    }
}
