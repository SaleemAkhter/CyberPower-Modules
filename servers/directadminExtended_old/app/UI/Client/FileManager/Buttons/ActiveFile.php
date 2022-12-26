<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class ActiveFile extends ButtonCreate implements ClientArea, AjaxElementInterface
{
    protected $id    = 'activeFileButton';
    protected $name  = 'activeFileButton';
    protected $title = 'activeFileButton';

    public function returnAjaxData()
    {
        $request        = Helper\sl('request');
        $path           = $request->getSession('fileManagerPath');

        $fileName       = $path . '/' . $request->get('name');
        $params['goTo'] = '/download?skipencode=1&file=' . $fileName;

        $linkToDownloadFile = $params;
        return (new ResponseTemplates\DataJsonResponse($linkToDownloadFile))->setMessageAndTranslate('fileHasBeenDownloaded');
    }
}
