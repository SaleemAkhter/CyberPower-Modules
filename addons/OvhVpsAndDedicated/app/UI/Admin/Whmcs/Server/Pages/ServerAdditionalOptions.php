<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Whmcs\Server\Pages;

use \ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Server\Providers\ServerAdditionalOptionsProvider;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;

/**
 * Class ServerAdditionalOptionsPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ServerAdditionalOptions extends BaseContainer implements AdminArea
{
    protected $id = 'serverOptions';

    public function initContent()
    {
        $additionalProvider = new ServerAdditionalOptionsProvider();
        $this->customTplVars = $additionalProvider->getAdditionalServerData();
    }
}