<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Copyable;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Interfaces\Deleteable;

class File extends Main implements Deleteable,Copyable
{
    public $type = 'file';
    protected $content;

    public function getType()
    {
        return $this->type;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getList()
    {
        // TODO: Implement getList() method.
    }

    public function getNewlist()
    {
        // TODO: Implement getNewlist() method.
    }
}
