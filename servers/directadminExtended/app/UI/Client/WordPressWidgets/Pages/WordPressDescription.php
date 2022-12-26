<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Pages;


use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;

class WordPressDescription extends BaseContainer implements ClientArea
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

    public function isVueComponent()
    {
        return false;
    }

    public function setMainContainer(&$mainContainer)
    {
        $this->mainContainer = &$mainContainer;
        foreach ($this->elements as $element)
        {
            $element->setMainContainer($mainContainer);
        }

        if (self::$findItemContext === false)
        {
            $this->initContent();
        }

        return $this;
    }
}