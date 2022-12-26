<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Forms\MassAction;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\MessageSystem\Providers;


class MarkRead extends BaseForm implements ClientArea
{
    protected $id    = 'massMarkReadForm';
    protected $name  = 'massMarkReadForm';
    protected $title = 'massMarkReadForm';

    public function getDefaultActions()
    {
        return ['markReadMany'];
    }

    public function initContent()
    {
        $this->setFormType('markReadMany')
            ->setProvider(new Providers\MessageSystem())
            ->setConfirmMessage('markReadMany');
    }
}
