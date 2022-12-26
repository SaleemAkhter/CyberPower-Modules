<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\Http\Admin;

use ModulesGarden\OvhVpsAndDedicated\Core\Http\AbstractController;

class PageNotFound extends AbstractController
{
    public function index()
    {
        $pageControler = new \ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers\Http\PageNotFound();

        return $pageControler->execute();
    }
}
