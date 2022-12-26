<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Client\Others\Pages;


use ModulesGarden\Servers\AwsEc2\Core\Helper\BuildUrl;
use ModulesGarden\Servers\AwsEc2\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\Traits\Alerts;

class Description extends BaseContainer implements ClientArea
{
    use Alerts;
    protected $id    = 'description';
    protected $name  = 'description';
    protected $title = 'description';
    protected $description;

    public function __construct($baseId = null, $hasAlert = false,$type = null)
    {
        parent::__construct($baseId);
        $this->setTitle($baseId . 'PageTitle');
        $this->setDescription($baseId . 'PageDesc');
        if($hasAlert)
        {
            $this->addInternalAllert($baseId . 'AlertDesc',$type);
        }
    }

    public function getDescription()
    {
        return $this->description;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getAssetsUrl()
    {
        return BuildUrl::getAssetsURL();
    }
}
