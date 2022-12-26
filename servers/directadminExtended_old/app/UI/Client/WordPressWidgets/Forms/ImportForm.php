<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Forms;

use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;

class ImportForm extends \ModulesGarden\WordpressManager\App\UI\Installations\Forms\ImportForm
{
    public function initContent()
    {
        $this->request = \ModulesGarden\Servers\DirectAdminExtended\App\Helpers\WordPressManager::replaceRequestClass([
           'hostingId' => di('request')->get('id')
        ]);

        parent::initContent();
    }
}