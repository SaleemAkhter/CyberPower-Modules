<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;

class Description extends BaseContainer implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\Alerts;
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
