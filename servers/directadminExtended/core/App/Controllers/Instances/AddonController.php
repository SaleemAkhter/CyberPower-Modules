<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances;

use ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Http\PageNotFound;
use ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Interfaces\DefaultController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;

abstract class AddonController implements DefaultController
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\OutputBuffer;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\IsAdmin;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\ErrorCodesLibrary;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\AppParams;

    public function runExecuteProcess($params = null)
    {
        $resault = $this->execute($params);
        if ($this->isValidIntegrationCallback($resault))
        {
            $this->setAppParam('IntegrationControlerName', $resault[0]);
            $this->setAppParam('IntegrationControlerMethod', $resault[1]);

            $this->loadLang();

            $this->lang->setContext(($this->isAdmin() ? 'addonAA' : 'addonCA'));

            $resault = Helper\di($resault[0], $resault[1]);
        }

        if ($resault instanceof \ModulesGarden\Servers\DirectAdminExtended\Core\UI\ViewAjax)
        {
            $this->resolveAjax($resault);
        }

        if (!$resault instanceof \ModulesGarden\Servers\DirectAdminExtended\Core\UI\ViewIntegrationAddon)
        {
            return $resault;
        }

        $addonIntegration = $this->getIntegrationControler($params['action']);

        return $addonIntegration->runExecuteProcess($resault);

    }

    public function isValidIntegrationCallback($callback = null)
    {
        if (is_callable($callback))
        {
            return true;
        }

        return false;
    }

    public function resolveAjax($resault)
    {
        $ajaxResponse = $resault->getResponse();

        $resolver = new \ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\ResponseResolver($ajaxResponse);

        $resolver->resolve();
    }

    protected function getIntegrationControler($action = null)
    {
        switch ($action)
        {
            case 'ConfigOptions':
                return Helper\di(\ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\Http\ConfigOptionsIntegration::class);
                break;
            case 'AdminServicesTabFields':
                return Helper\di(\ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\Http\AdminServicesTabFieldsIntegration::class);
                break;
            default:
                return Helper\di(\ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\Http\AddonIntegration::class);
        }
    }
}
