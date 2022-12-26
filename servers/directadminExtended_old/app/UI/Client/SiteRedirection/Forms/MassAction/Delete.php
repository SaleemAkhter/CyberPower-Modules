<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SiteRedirection\Forms\MassAction;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;

class Delete extends BaseForm implements ClientArea
{
    protected $id    = 'massDelete';
    protected $name  = 'massDelete';
    protected $title = 'massDelete';

    public function getDefaultActions()
    {
        return ['massDelete'];
    }

    public function initContent()
    {
        $this->setFormType('massDelete')
                ->setProvider(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SiteRedirection\Providers\SiteRedirection())
                ->setConfirmMessage('confirmMassDelete');
    }
}
