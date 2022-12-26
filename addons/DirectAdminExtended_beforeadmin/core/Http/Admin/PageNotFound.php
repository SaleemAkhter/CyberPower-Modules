<?php

namespace ModulesGarden\DirectAdminExtended\Core\Http\Admin;

use ModulesGarden\DirectAdminExtended\Core\Http\AbstractController;

class PageNotFound extends AbstractController
{
    public function index()
    {
        $pageControler = new \ModulesGarden\DirectAdminExtended\Core\App\Controllers\Http\PageNotFound();

        return $pageControler->execute();
    }
}
