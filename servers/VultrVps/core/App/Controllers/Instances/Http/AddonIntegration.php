<?php

namespace ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\Http;

use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\HttpController;
use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Interfaces\AdminArea;
use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Interfaces\ClientArea;

class AddonIntegration extends HttpController implements AdminArea, ClientArea
{
    protected $templateName = 'addonIntegration';
    protected $templateDir = null;

    public function execute($response = null)
    {
        $this->loadLangContext();
        $this->setControllerResult($response);
        
        if (!$this->controllerResult)
        {
            return '';
        }

        return $this->resolveResponse();
    }

    public function resolveResponse()
    {
        if ($this->controllerResult instanceof \ModulesGarden\Servers\VultrVps\Core\Http\Response)
        {
            $this->controllerResult->setForceHtml();
        }

        return $this->responseResolver->setResponse($this->controllerResult)
            ->setTemplateName($this->getTemplateName())
            ->setTemplateDir($this->getTemplateDir())
            ->setPageController($this)
            ->resolve();
    }
}
