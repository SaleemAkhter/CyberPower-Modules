<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Pages;


use ModulesGarden\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use function ModulesGarden\DirectAdminExtended\Core\Helper\di;

class BackupsContainer extends BaseContainer implements AdminArea
{
    protected $id = 'backupsContainer';
    protected $title = 'backupsContainerTitle';

    public function initContent()
    {
        $this->addElement(di(FtpEndPointsTable::class))
            ->addElement(BackupTable::class);
    }
}
