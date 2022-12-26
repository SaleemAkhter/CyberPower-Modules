<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Admin\Server;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http\AbstractController;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\ServerSettingsManage;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server\Details;
/**
 * Description of Clientsservices
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServerAdditionalOptions extends AbstractController
{
    public function index()
    {
        return Helper\view()
            ->addElement(\ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Server\Pages\ServerAdditionalOptions::class);
    }
}
