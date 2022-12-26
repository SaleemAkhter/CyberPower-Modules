<?php

namespace ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Instances\Http;

use \ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Interfaces\AdminArea;
use \ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Interfaces\ClientArea;
use \ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Instances\HttpController;

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
        $view = \ModulesGarden\Servers\AwsEc2\Core\Helper\view();
        $view->replaceBreadcrumbTitle('1', 'pageNotFound');

        return $this->responseResolver->setResponse($view)
            ->setTemplateName($this->getTemplateName())
            ->setTemplateDir($this->getTemplateDir())
            ->setPageController($this)
            ->resolve();
    }
}
