<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models;

class Backup extends AbstractModel
{
    public $id;
    public $name;
    public $domain;
    protected $path;
    public $version;
    protected $time;
    protected $note;
    protected $file;
    protected $fileList;
    protected $url;
    protected $title;
    protected $email;
    //database
    protected $dbName;
    protected $dbUser;
    protected $dbPassword;
    protected $dbHost;
    protected $dbPrefix;
    protected $additionalOptions = [];

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getNote()
    {
        return $this->note;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getFileList()
    {
        return $this->fileList;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getDbName()
    {
        return $this->dbName;
    }

    public function getDbUser()
    {
        return $this->dbUser;
    }

    public function getDbPassword()
    {
        return $this->dbPassword;
    }

    public function getDbHost()
    {
        return $this->dbHost;
    }

    public function getDbPrefix()
    {
        return $this->dbPrefix;
    }

    public function getAdditionalOptions()
    {
        return $this->additionalOptions;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
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

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function setFileList($fileList)
    {
        $this->fileList = $fileList;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setDbName($dbName)
    {
        $this->dbName = $dbName;
        return $this;
    }

    public function setDbUser($dbUser)
    {
        $this->dbUser = $dbUser;
        return $this;
    }

    public function setDbPassword($dbPassword)
    {
        $this->dbPassword = $dbPassword;
        return $this;
    }

    public function setDbHost($dbHost)
    {
        $this->dbHost = $dbHost;
        return $this;
    }

    public function setDbPrefix($dbPrefix)
    {
        $this->dbPrefix = $dbPrefix;
        return $this;
    }

    public function setAdditionalOptions($additionalOptions)
    {
        $this->additionalOptions = $additionalOptions;
        return $this;
    }
    
    public function getDomain() {
        return $this->domain;
    }

    public function setDomain($domain) {
        $this->domain = $domain;
        return $this;
    }
}
