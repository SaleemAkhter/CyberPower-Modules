<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Model;

class Directory extends Main
{
    public $type  = 'dir';
    public $name;
    public $user;
    public $password;

    protected $files = [];

    public function getFiles($method = null, $separated = null)
    {
        if ($method)
        {
            $out = null;
            foreach ($this->files as $file)
            {
                if (!method_exists($file, $method))
                {
                    throw new \Exception($method . 'method in File does not exist!.');
                }
                $out[$file->getName()] = $file->{$method}();
            }
            if ($separated)
            {
                return implode(',', $out);
            }
            return $out;
        }

        return $this->files;
    }

    public function setFiles(array $files)
    {
        $this->files = $files;
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

    public function setNewFiles(array $newFiles)
    {
        $this->newFiles = $newFiles;
        return $this;
    }

    public function addFile(File $file)
    {
        $this->files[] = $file;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }


    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}
