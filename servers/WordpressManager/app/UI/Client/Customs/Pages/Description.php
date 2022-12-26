<?php

namespace ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages;

use \ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;

class Description extends BaseContainer implements ClientArea
{
    use \ModulesGarden\WordpressManager\Core\UI\Traits\Alerts;
    protected $id    = 'description';
    protected $name  = 'description';
    protected $title = 'description';
    protected $description;

    public function __construct($baseId = null, $hasAlert = false,$type = null)
    {
        parent::__construct($baseId);
        $this->setTitle($baseId . 'PageTitle');
        $this->setDescription($baseId . 'PageDescription');
        if($hasAlert)
        {
            $this->addInternalAlert($baseId . 'AlertDescription',$type);
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
        return BuildUrl::getAppAssetsURL();
    }
}
