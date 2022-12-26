<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Http\Admin;

use ModulesGarden\OvhVpsAndDedicated\Core\Http\AbstractController;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper;

/**
 * Example admin home page controler
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Home extends AbstractController
{

    /**
     * Example of static page
     * @return \ModulesGarden\OvhVpsAndDedicated\Core\UI\View
     */
    public function index()
    {
        return Helper\view()
            ->addElement(\ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Pages\Servers::class);
    }

    public function vps()
    {
        return Helper\view()
            ->addElement(\ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Pages\Vps::class)
            ;
    }

    public function dedicated()
    {
        return Helper\view()
            ->addElement(\ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Pages\Dedicated::class);
    }
}
