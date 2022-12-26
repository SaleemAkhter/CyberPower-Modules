<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models;

class InstallationScript extends AbstractModel
{
    protected $sid;
    protected $name;
    protected $softname;
    protected $description;
    protected $category;
    protected $categoryString;
    protected $type;
    protected $version;
    protected $image;
    protected $fields = [];

    public function getSid()
    {
        return $this->sid;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSoftname()
    {
        return $this->softname;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setSid($sid)
    {
        $this->sid = $sid;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setSoftname($softname)
    {
        $this->softname = $softname;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function getCategoryString()
    {
        return $this->categoryString;
    }

    public function setCategoryString($categoryString)
    {
        $this->categoryString = $categoryString;
        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
        return $this;
    }
}
