<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Copyable;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Deleteable;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Permissionable;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Compressible;

class FileList extends Main implements Copyable, Deleteable, Permissionable, Compressible
{
    public $type       = 'list';
    protected $list    = [];
    protected $newList = [];
    protected $compressType;

    public function setList(array $list)
    {
        $this->list = $list;
        return $this;
    }

    public function setNewList(array $newList)
    {
        $this->newList = $newList;
        return $this;
    }

    public function getList()
    {
        return $this->list;
    }

    public function getNewlist()
    {
        return $this->newList;
    }

    public function addElementToList($element)
    {
        $this->list[] = $element;
        return $this;
    }

    public function addElementToNewList($element)
    {
        $this->newList[] = $element;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getCompressType()
    {
        return $this->compressType;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setCompressType($compressType)
    {
        $this->compressType = $compressType;
        return $this;
    }
}
