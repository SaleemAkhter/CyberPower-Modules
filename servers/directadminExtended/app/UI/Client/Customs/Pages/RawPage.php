<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;

class RawPage extends BaseContainer implements ClientArea
{
    protected $id    = 'rawPage';
    protected $name  = 'rawPage';
    protected $title = 'rawPage';
    protected $description;

    public function __construct($baseId = null, $hasAlert = false,$type = null)
    {
        parent::__construct($baseId);
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
