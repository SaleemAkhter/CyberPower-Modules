<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Http\Admin;

use ModulesGarden\OvhVpsAndDedicated\Core\Helper;
use ModulesGarden\OvhVpsAndDedicated\Core\Http\AbstractController;
/**
 * Description of Clientsservices
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class WhmcsServerAdditionalOptions extends AbstractController
{
    public function index()
    {
        return Helper\view()
            ->addElement(\ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Whmcs\Server\Pages\ServerAdditionalOptions::class);
    }
}
