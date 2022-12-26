<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http\Admin;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http\AbstractController;

class PageNotFound extends AbstractController
{
    public function index()
    {
        $a = new \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Http\PageNotFound();

        return $a->execute();
    }
}
