<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Modals\InstallNewApp;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonModal;

class AppBox extends ButtonModal implements ClientArea
{
    protected $name  = 'appBox';
    protected $id    = 'appBox';
    protected $title = 'appBox';
    protected $image;
    protected $sid;
    protected $version;
    protected $appDescription;
    protected $appName;
    protected $url   = null;

    public function returnAjaxData()
    {
        $this->setModal(new InstallNewApp());

        return parent::returnAjaxData();
    }

    public function getAppName()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getSid()
    {
        return $this->sid;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getAppDescription()
    {
        return $this->appDescription;
    }

    public function setAppName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function setSid($sid)
    {
        $this->sid = $sid;
        return $this;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function setAppDescription($appDescription)
    {
        $this->appDescription = $appDescription;
        return $this;
    }
    
    public function setRawUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getRawUrl()
    {
        return $this->url;
    }

    public function getRawUrlFixed($id, $ver)
    {
        $baseUrl = $this->getRawUrl() . "&sid=$id&ver=$ver";
        $fixed   = str_replace(' ', '_', $baseUrl);

        return $fixed;
    }    
}
