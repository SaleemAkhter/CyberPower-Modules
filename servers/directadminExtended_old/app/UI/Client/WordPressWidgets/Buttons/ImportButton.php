<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Modals\ImportModal;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

class ImportButton extends \ModulesGarden\WordpressManager\App\UI\Installations\Buttons\ImportButton implements ClientArea
{
    public function initContent()
    {
        $this->initIds('ImportButton');
        $this->initLoadModalAction(new ImportModal());
    }
}