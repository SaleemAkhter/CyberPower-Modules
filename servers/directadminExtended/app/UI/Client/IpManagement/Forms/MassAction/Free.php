<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\IpManagement\Providers;


class Free extends BaseForm implements ClientArea
{
    protected $id    = 'massFreeForm';
    protected $name  = 'massFreeForm';
    protected $title = 'massFreeForm';

    public function getDefaultActions()
    {
        return ['massFreeMany'];
    }

    public function initContent()
    {
        $this->setFormType('massFreeMany')
            ->setProvider(new Providers\IpManagement())
            ->setConfirmMessage('massFreeMany');
    }
}
