<?php

namespace ModulesGarden\WordpressManager\Core\App\Controllers\Instances\Http;

use \ModulesGarden\WordpressManager\Core\App\Controllers\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\App\Controllers\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\App\Controllers\Instances\HttpController;

class Integration extends HttpController implements AdminArea, ClientArea
{
    protected $templateName = 'integration';

    public function execute($params = null)
    {
        $this->setParams($params);
        
        if (!$this->controllerResult)
        {
            return '';
        }

        return $this->resolveResponse();
    }

    public function resolveResponse()
    {
        if ($this->controllerResult instanceof \ModulesGarden\WordpressManager\Core\Http\Response)
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
