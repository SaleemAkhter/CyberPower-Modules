<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Tabs;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;

class Applications extends Tabs implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    
    protected $id    = 'ApplicationsPage';
    protected $name  = 'ApplicationsPage';
    protected $title = 'ApplicationsPage';
    protected $tabs  = [
        'applications' => ApplicationsTable::class,
        'backups'      => BackupsTable::class,
        'scripts'      => ApplicationsNew::class
    ];

    public function initContent()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::APPS_BACKUPS) === false)
        {
            unset($this->tabs['backups']);
        }
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::APPS_INSTALL) === false)
        {
            unset($this->tabs['scripts']);
        }
        foreach ($this->tabs as $tab)
        {
            $this->addElement(Helper\di($tab));
        }
    }
}
