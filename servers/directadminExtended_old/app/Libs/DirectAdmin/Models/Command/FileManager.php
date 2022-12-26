<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 16:37
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class FileManager extends AbstractModel implements ResponseLoad
{
    public $name;
    public $path;
    public $directory;
    public $permission;
    public $size;
    public $date;
    public $gid;
    public $uid;
    public $type;
    public $password;
    public $user;
    public $showsize;
    public $truepath;
    protected $text;
    protected $file;
    protected $chmod;
    protected $overwrite;
    protected $old;


    public function loadResponse($response, $function = null)
    {
        if ($function === 'view') {
            $data = [
                'text'  => $response
            ];
            $this->addResponseElement(new self($data));

            return $this;
        }

        // If only previous dir info exist
        if (count($response) === 1 && !is_object($response)) {
            return $this;
        }

        $this->parseObject($response);

        return $this;
    }
    private function parseObject($response)
    {


        foreach ($response as $name => $data) {
            if ((int)$data->islink === 1 || $name === '/') {
                continue;
            }

            if ($name[0] !== '/') {
                continue;
            }
            $this->addResponseElement(new self($data));
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return FileManager
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     * @return FileManager
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param mixed $directory
     * @return FileManager
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @param mixed $permission
     * @return FileManager
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChmod()
    {
        return $this->chmod;
    }

    /**
     * @param mixed $chmod
     * @return FileManager
     */
    public function setChmod($chmod)
    {
        $this->chmod = $chmod;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOverwrite()
    {
        return $this->overwrite;
    }

    /**
     * @param mixed $overwrite
     * @return FileManager
     */
    public function setOverwrite($overwrite)
    {
        $this->overwrite = $overwrite;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOld()
    {
        return $this->old;
    }

    /**
     * @param mixed $old
     * @return FileManager
     */
    public function setOld($old)
    {
        $this->old = $old;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     * @return FileManager
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return FileManager
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGid()
    {
        return $this->gid;
    }

    /**
     * @param mixed $gid
     * @return FileManager
     */
    public function setGid($gid)
    {
        $this->gid = $gid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     * @return FileManager
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShowsize()
    {
        return $this->showsize;
    }

    /**
     * @param mixed $showsize
     * @return FileManager
     */
    public function setShowsize($showsize)
    {
        $this->showsize = $showsize;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return FileManager
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTruepath()
    {
        return $this->truepath;
    }

    /**
     * @param mixed $truepath
     * @return FileManager
     */
    public function setTruepath($truepath)
    {
        $this->truepath = $truepath;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return FileManager
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return FileManager
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return FileManager
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return FileManager
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
}
