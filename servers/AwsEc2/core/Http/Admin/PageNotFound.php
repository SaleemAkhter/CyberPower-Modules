<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Http\Admin;

use ModulesGarden\Servers\AwsEc2\Core\Http\AbstractController;

class PageNotFound extends AbstractController
{
    public function index()
    {
        $pageControler = new \ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Http\PageNotFound();

        return $pageControler->execute();
    }
}
