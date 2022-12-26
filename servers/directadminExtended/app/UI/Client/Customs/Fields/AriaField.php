<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\BaseField;

class AriaField extends BaseField
{
    protected $name  = 'ariaField';
    protected $id    = 'ariaField';
    protected $title = 'ariaField';
    protected $class = ['info'];
    protected $list  = [];
    protected $url   = null;
    protected $installer;

    public function getList()
    {
        return $this->list;
    }

    public function getName()
    {
        return str_replace(' ', '-', $this->name);
    }

    public function getInstaller()
    {
        return $this->installer;
    }

    public function setInstaller($installer)
    {
        $this->installer = $installer;
        return $this;
    }

    public function setList(array $list = [])
    {
        $this->list = $list;
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
