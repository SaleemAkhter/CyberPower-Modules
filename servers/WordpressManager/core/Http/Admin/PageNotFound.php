<?php

namespace ModulesGarden\WordpressManager\Core\Http\Admin;

use ModulesGarden\WordpressManager\Core\Http\AbstractController;

class PageNotFound extends AbstractController
{
    public function index()
    {
        $a = new \ModulesGarden\WordpressManager\Core\App\Controllers\Http\PageNotFound();

        return $a->execute();
    }
}
