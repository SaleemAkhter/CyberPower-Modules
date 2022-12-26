<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\Http\Admin;

use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;

class PageNotFound extends AbstractController
{
    public function index()
    {
        $pageControler = new \ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Http\PageNotFound();

        return $pageControler->execute();
    }
}
