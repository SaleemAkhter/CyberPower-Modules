<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers\Instances;

use ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers\Http\PageNotFound;
use ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers\Interfaces\DefaultController;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper;

abstract class AddonController implements DefaultController
{
    use \ModulesGarden\OvhVpsAndDedicated\Core\Traits\Lang;
    use \ModulesGarden\OvhVpsAndDedicated\Core\Traits\OutputBuffer;
    use \ModulesGarden\OvhVpsAndDedicated\Core\Traits\IsAdmin;
    use \ModulesGarden\OvhVpsAndDedicated\Core\UI\Traits\RequestObjectHandler;
    use \ModulesGarden\OvhVpsAndDedicated\Core\Traits\ErrorCodesLibrary;
    use \ModulesGarden\OvhVpsAndDedicated\Core\Traits\AppParams;

    public function runExecuteProcess($params = null)
    {
        $resault = $this->execute($params);
        if ($this->isValidIntegrationCallback($resault))
        {
            $this->setAppParam('IntegrationControlerName', $resault[0]);
            $this->setAppParam('IntegrationControlerMethod', $resault[1]);

            $resault = Helper\di($resault[0], $resault[1]);
        }

        if ($resault instanceof \ModulesGarden\OvhVpsAndDedicated\Core\UI\ViewAjax)
        {
            $this->resolveAjax($resault);
        }

        if (!$resault instanceof \ModulesGarden\OvhVpsAndDedicated\Core\UI\ViewIntegrationAddon)
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

        $resolver = new \ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers\ResponseResolver($ajaxResponse);

        $resolver->resolve();
    }

    protected function getIntegrationControler($action = null)
    {
        switch ($action)
        {
            case 'ConfigOptions':
                return Helper\di(\ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers\Instances\Http\ConfigOptionsIntegration::class);
                break;
            case 'AdminServicesTabFields':
                return Helper\di(\ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers\Instances\Http\AdminServicesTabFieldsIntegration::class);
                break;
            default:
                return Helper\di(\ModulesGarden\OvhVpsAndDedicated\Core\App\Controllers\Instances\Http\AddonIntegration::class);
        }
    }
}
