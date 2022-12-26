<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\Http;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Interfaces\AdminArea;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Interfaces\ClientArea;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\HttpController;

class PageNotFound extends HttpController implements AdminArea, ClientArea
{
    protected $templateName = 'pageNotFound';

    public function execute ($params = null)
    {
        $this->setParams($params);
        
        return $this->resolveResponse();
    }

    public function resolveResponse()
    {
        return $this->responseResolver->setResponse(\ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\view())
            ->setTemplateName($this->getTemplateName())
            ->setTemplateDir($this->getTemplateDir())
            ->setPageController($this)
            ->resolve();
    }
}
