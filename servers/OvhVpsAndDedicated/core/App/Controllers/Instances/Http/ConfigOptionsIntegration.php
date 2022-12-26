<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\Http;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Interfaces\AdminArea;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\App\Controllers\Instances\HttpController;

class ConfigOptionsIntegration extends HttpController implements AdminArea
{
    protected $templateName = 'configOptionsIntegration';
    protected $templateDir = null;

    public function execute($response = null)
    {
        $this->setControllerResult($response);

        if (!$this->controllerResult)
        {
            return '';
        }

        $result = $this->resolveResponse();

        $data = [
            'content' => $result,
            'mode' => 'advanced'
        ];

        $enc = \json_encode($data);

        $this->cleanOutputBuffer();
        echo $enc;
        exit;
    }

    public function resolveResponse()
    {
        if ($this->controllerResult instanceof \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http\Response)
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
