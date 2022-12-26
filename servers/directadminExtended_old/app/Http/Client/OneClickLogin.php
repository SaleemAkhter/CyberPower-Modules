<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Redirect\Pages\RedirectPage;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Models\ServerSettings;

class OneClickLogin extends AbstractController
{

    public function index(){
        return Helper\view()->addElement(RedirectPage::class);
    }

}
