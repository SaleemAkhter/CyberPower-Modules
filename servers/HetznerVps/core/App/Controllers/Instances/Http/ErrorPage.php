<?php

namespace ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\Http;

use \ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Interfaces\AdminArea;
use \ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Interfaces\ClientArea;
use \ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\HttpController;

class ErrorPage extends HttpController implements AdminArea, ClientArea
{
    protected $templateName = 'errorPage';

    public function execute ($params = null)
    {
        $this->setParams($params);
        
        $this->parseError();
        
        return $this->resolveResponse();
    }

    public function resolveResponse()
    {
        if ($this->getRequestValue('ajax') == '1')
        {
            return $this->resolveResponseAjax();
        }

        return $this->responseResolver->setResponse(\ModulesGarden\Servers\HetznerVps\Core\Helper\view())
            ->setTemplateName($this->getTemplateName())
            ->setTemplateDir($this->getTemplateDir())
            ->setPageController($this)
            ->resolve();
    }
    
    public function parseError()
    {
        $err = $this->getParam('mgErrorDetails');
        if (!$err)
        {
            return null;
        }

        if (!($err instanceof \ModulesGarden\Servers\HetznerVps\Core\HandlerError\Exceptions\Exception))
        {
            $nErr = new \ModulesGarden\Servers\HetznerVps\Core\HandlerError\Exceptions\Exception(null, null, null, $err);

            $this->setParam('mgErrorDetails', $nErr);
        }

        $this->logError();
    }

    public function logError()
    {
        $err = $this->getParam('mgErrorDetails');

        /**
         * \ModulesGarden\Servers\HetznerVps\Core\HandlerError\WhmcsLogsHandler
         */
        $logger = \ModulesGarden\Servers\HetznerVps\Core\ServiceLocator::call('whmcsLogger');

        $logger->addModuleLogError($err);
    }

    public function resolveResponseAjax()
    {
        $err = $this->getParam('mgErrorDetails');
        if (!$err)
        {
            return null;
        }

        $ajaxData = (new \ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\DataJsonResponse($err->getDetailsToDisplay()))->setStatusError();

        return $this->responseResolver->setResponse($ajaxData->getFormatedResponse())
            ->setTemplateName($this->getTemplateName())
            ->setTemplateDir($this->getTemplateDir())
            ->setPageController($this)
            ->resolve();
    }
}
