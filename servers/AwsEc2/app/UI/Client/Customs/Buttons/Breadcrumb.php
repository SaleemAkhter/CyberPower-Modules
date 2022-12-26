<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Customs\Buttons;

use \ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\AwsEc2\Core\UI\Builder\BaseContainer;
use \ModulesGarden\Servers\AwsEc2\Core\Helper\BuildUrl;

use function \ModulesGarden\Servers\AwsEc2\Core\Helper\sl;

class Breadcrumb extends BaseContainer implements ClientArea
{
    protected $controller;
    protected $pages = [];

    public function __construct($controller, array $pages = [])
    {
        parent::__construct('breadcrumb');
        $this->controller = $controller;
        $this->pages      = $pages;
    }

    public function getCurrentAction()
    {
        return sl('request')->get('mg-action');
    }

    public function getPages()
    {
        $currentAction = $this->getCurrentAction();
        if($currentAction === null)
        {
            $keyAction = 1;
        }else{
            $keyAction = array_search($currentAction, $this->pages) + 1;
        }

        return array_slice($this->pages, 0,$keyAction);
    }

    public function getUrl($method)
    {
        $params = sl('request')->query->all();
        unset($params['mg-action']);
        return BuildUrl::getUrl($this->controller, $method, $params);
    }

}
