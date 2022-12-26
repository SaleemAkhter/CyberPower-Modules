<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Backups\Forms;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;

class Download extends ButtonRedirect implements ClientArea
{
    protected $id    = 'downloadButton';
    protected $name  = 'downloadButton';
    protected $title = 'downloadButton';
    protected $class = ['btn btn--sm btn--link btn--icon btn--plain lu-tooltip '];
    protected $icon  = 'icon-in-button lu-zmdi lu-zmdi-download';

    public function initContent()
    {
        $params = sl('request')->query->all();
        unset($params['mg-page']);
        $this->setRawUrl(BuildUrl::getUrl('backups', '', $params) . '&ajax=1&mgFormAction=read&download=file')
                ->setRedirectParams([
                    'name' => ':id',
                    'namespace' => str_replace('\\', '_', Forms\Delete::class)
        ]);
        parent::initContent();
    }
}
