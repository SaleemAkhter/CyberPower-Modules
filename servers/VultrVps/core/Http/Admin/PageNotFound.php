<?php

namespace ModulesGarden\Servers\VultrVps\Core\Http\Admin;

use ModulesGarden\Servers\VultrVps\Core\Http\AbstractController;

class PageNotFound extends AbstractController
{
    public function index()
    {
        $pageControler = new \ModulesGarden\Servers\VultrVps\Core\App\Controllers\Http\PageNotFound();

        return $pageControler->execute();
    }
}
