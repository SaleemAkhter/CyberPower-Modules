<?php

namespace ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances;

use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Http\PageNotFound;
use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Interfaces\DefaultController;
use ModulesGarden\Servers\HetznerVps\Core\Helper;
use ModulesGarden\Servers\HetznerVps\Core\Http\JsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\ResponseResolver;
use ModulesGarden\Servers\HetznerVps\Core\UI\View;
use ModulesGarden\Servers\HetznerVps\Core\UI\ViewAjax;
use ModulesGarden\Servers\HetznerVps\Core\UI\ViewIntegrationAddon;

abstract class AddonController implements DefaultController
{
    use \ModulesGarden\Servers\HetznerVps\Core\Traits\Lang;
    use \ModulesGarden\Servers\HetznerVps\Core\Traits\OutputBuffer;
    use \ModulesGarden\Servers\HetznerVps\Core\Traits\IsAdmin;
    use \ModulesGarden\Servers\HetznerVps\Core\UI\Traits\RequestObjectHandler;
    use \ModulesGarden\Servers\HetznerVps\Core\Traits\ErrorCodesLibrary;
    use \ModulesGarden\Servers\HetznerVps\Core\Traits\AppParams;

    public function runExecuteProcess($params = null)
    {
        $this->loadLangContext();

        $result = $this->execute($params);
        if ($result instanceof JsonResponse)
        {
            $resolver = new ResponseResolver($result);

            $resolver->resolve();
        }

        if ($this->isValidIntegrationCallback($result))
        {
            $this->setAppParam('IntegrationControlerName', $result[0]);
            $this->setAppParam('IntegrationControlerMethod', $result[1]);

            //to do catch exceptions
            $result = Helper\di($result[0], $result[1]);
        }

        if ($result instanceof ViewAjax)
        {
            $this->resolveAjax($result);
        }

        if (!$result instanceof ViewIntegrationAddon)
        {
            return $result;
        }

        if (!$result instanceof View)
        {
            return $result;
        }

        if ($result instanceof JsonResponse)
        {
            $resolver = new ResponseResolver($result);

            $resolver->resolve();
        }

        $addonIntegration = $this->getIntegrationControler($params['action']);

        return $addonIntegration->runExecuteProcess($result);

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

        $resolver = new ResponseResolver($ajaxResponse);

        $resolver->resolve();
    }

    protected function getIntegrationControler($action = null)
    {
        switch ($action)
        {
            case 'ConfigOptions':
                return Helper\di(\ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\Http\ConfigOptionsIntegration::class);
                break;
            case 'AdminServicesTabFields':
                return Helper\di(\ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\Http\AdminServicesTabFieldsIntegration::class);
                break;
            default:
                return Helper\di(\ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\Http\AddonIntegration::class);
        }
    }

    public function loadLangContext()
    {
        $this->loadLang();

        if ($this->getAppParam('IntegrationControlerName'))
        {
            $parts = explode('\\', $this->getAppParam('IntegrationControlerName'));

            $controller = end($parts);
        }
        else
        {
            $parts = explode('\\', get_class($this));

            $controller = end($parts);
        }

        $this->lang->setContext(($this->getAppParam('moduleAppType') . ($this->isAdmin() ? 'AA' : 'CA')), lcfirst($controller));
    }
}
