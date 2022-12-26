<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Http\Admin;

use ModulesGarden\Servers\HetznerVps\Core\Http\AbstractController;

class PageNotFound extends AbstractController
{
    public function index()
    {
        $pageControler = new \ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Http\PageNotFound();

        return $pageControler->execute();
    }
}
