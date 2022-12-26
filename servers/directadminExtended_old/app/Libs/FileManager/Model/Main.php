<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model;

class Main extends MAbstract
{
    public $id;
    public $name;
    public $path;
    public $type;
    protected $newName;
    protected $fullPath;
    protected $permissions;
    protected $newPath;
    protected $fullNewPath;
    protected $types;
    public $size;
    protected $humanSize;
    protected $creationTime;
    protected $modificationTime;
    protected $additionalFields = [];

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function getNewPath()
    {
        return $this->newPath;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
        return $this;
    }

    public function setNewPath($newPath)
    {
        $this->newPath = $newPath;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getFullPath()
    {
        return $this->fullPath;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getCreationTime()
    {
        return $this->creationTime;
    }

    public function getModificationTime()
    {
        return $this->modificationTime;
    }

    public function getAdditionalFields()
    {
        return $this->additionalFields;
    }

    public function setFullPath($fullPath)
    {
        $this->fullPath = $fullPath;
        return $this;
    }

    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;
        return $this;
    }

    public function setModificationTime($modificationTime)
    {
        $this->modificationTime = $modificationTime;
        return $this;
    }

    public function setAdditionalFields($additionalFields)
    {
        $this->additionalFields = $additionalFields;
        return $this;
    }

    public function getFullNewPath()
    {
        return $this->fullNewPath;
    }

    public function setFullNewPath($fullNewPath)
    {
        $this->fullNewPath = $fullNewPath;
        return $this;
    }

    public function getTypes()
    {
        return $this->types;
    }

    public function setTypes($types)
    {
        $this->types = $types;
        return $this;
    }

    public function getHumanSize()
    {
        return $this->humanSize;
    }

    public function setHumanSize($humanSize)
    {
        $this->humanSize = $humanSize;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewName()
    {
        return $this->newName;
    }

    /**
     * @param mixed $newName
     * @return Main
     */
    public function setNewName($newName)
    {
        $this->newName = $newName;
        return $this;
    }


}
