<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Http\Admin;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Http\AbstractController;

class PageNotFound extends AbstractController
{
    public function index()
    {
        $pageControler = new \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Controllers\Http\PageNotFound();

        return $pageControler->execute();
    }
}
