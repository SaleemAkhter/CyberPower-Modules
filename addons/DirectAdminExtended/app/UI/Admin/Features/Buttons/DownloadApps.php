<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Buttons;

use \ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;

class DownloadApps extends ButtonRedirect implements AdminArea
{
    use \ModulesGarden\DirectAdminExtended\Core\UI\Traits\DisableButtonByColumnValue;
    
    protected $id    = 'downloadAppsButton';
    protected $name  = 'downloadAppsButton';
    protected $title = 'downloadAppsButton';
//    protected $class = ['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--plain lu-btn--default lu-tooltip'];
    protected $icon = 'icon-in-button lu-zmdi lu-zmdi-download';
   
    public function initContent()
    {
        $this->setDisableByColumnValue('autoinstaller', '');
        parent::initContent();
    }
    
}
