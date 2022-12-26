<?php

namespace ModulesGarden\DirectAdminExtended\App\Http\Admin;

use ModulesGarden\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\DirectAdminExtended\Core\Helper;

/**
 * Example admin home page controler
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Home extends AbstractController
{
    
    /**
     * Example of static page
     * @return type
     */
    public function index()
    {
        return Helper\redirect('dashboard','', []);
    }
}
