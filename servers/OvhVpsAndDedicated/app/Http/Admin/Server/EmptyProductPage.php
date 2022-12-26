<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Admin\Server;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages\ConfigOptions\NoActiveServerGroup;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Product\Pages\NoActiveMachine;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http\AbstractController;

/**
 * Description of Clientsservices
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class EmptyProductPage extends AbstractController
{
    public function index()
    {
        return Helper\viewIntegrationAddon()
            ->addElement(NoActiveMachine::class);

    }

    public function configOptions()
    {
        return Helper\viewIntegrationAddon()
            ->addElement(NoActiveServerGroup::class);
    }
}
