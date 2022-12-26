<?php

namespace ModulesGarden\DirectAdminExtended\App\Http\Admin;

use ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Pages\ProductsPage;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Pages\ServersPage;
use ModulesGarden\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\DirectAdminExtended\Core\Helper;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs\Server;
use ModulesGarden\DirectAdminExtended\Core\ModuleConstants;
use function ModulesGarden\DirectAdminExtended\Core\Helper\sl;


class Dashboard extends AbstractController
{

    public function index()
    {
        return Helper\view()
                ->addElement(ProductsPage::class)
                ->addElement(ServersPage::class);

    }

    public function loginToDirectAdmin()
    {
        $id         = (int)$this->getRequestValue('id');
        $server     = Server::findOrFail($id);
        $protocol   = $server->secure ? 'https' : 'http';
        $user       = $server->username;
        $pass       = decrypt($server->password);
        $port       = $server->port ?: 2222;
        $host       = $server->hostname?: $server->ipaddress;
        $url        = $protocol.'://'. $host .':'.$port.'/CMD_LOGIN';

        $form = (sl('smarty'))
            ->view('loginToDA', [
                'url' => $url,
                'username' => $user,
                'password' => $pass,
            ], ModuleConstants::getFullPath('app', 'UI', 'Admin', 'Dashboard', 'Templates'));

        echo $form;
        exit;
    }
}
