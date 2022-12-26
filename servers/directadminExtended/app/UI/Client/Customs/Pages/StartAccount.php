<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;

class StartAccount extends BaseContainer implements ClientArea
{
    protected $id    = 'startAccountSection';
    protected $name  = 'startAccountSection';
    protected $title = 'startAccountSection';
    protected $description;
    protected $class=['col-lg-6'];

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
    public function setClass($class)
    {
        $this->class=$class;
        return $this;
    }
    public function getClass()
    {
        if(is_array($this->class)){
            $this->class=implode(" ",$this->class);
        }
        return $this->class;
    }
    public function isImpersonated()
    {
        if ((isset($_SESSION['resellerloginas'],$_SESSION['resellerloginas'][$this->getWhmcsParamByKey('serviceid')]) &&  !empty($_SESSION['resellerloginas'])) || (isset($_SESSION['adminloginas'],$_SESSION['adminloginas'][$this->getWhmcsParamByKey('serviceid')]) &&  !empty($_SESSION['adminloginas'][$this->getWhmcsParamByKey('serviceid')]))){
            $oldsessionsrole=Request::build()->getSession('adminloginasrole');
            return $oldsessionsrole[$this->getWhmcsParamByKey('serviceid')];
        }

        return false;
    }
    public function getMessagesCount()
    {
        return (isset($_SESSION['unreadmessagecount']))?$_SESSION['unreadmessagecount']:"";
    }
    public function mainUrl()
    {
        return BuildUrl::getUrl();
    }
}
