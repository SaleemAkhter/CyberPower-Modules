<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models;

class Instance extends AbstractModel
{
    public $id;
    public $version;
    public $time;
    public $domain;
    protected $file;
    protected $fileList;
    protected $path;
    protected $url;
    protected $title;
    protected $category;
    protected $categoryId;
    public $image;
    public $name;
    public $staging = false;
    protected $isCloneable = false;
    public $canStaging;
    //database
    protected $dbName;
    protected $dbUser;
    protected $dbPassword;
    protected $dbHost;
    protected $dbPrefix;
    //edit
    public $editDbName;
    public $editDbUser;
    public $editDbPassword;
    public $editDbHost;
    public $adminUsername;
    public $adminPassword;
    public $autoUpgradeWordpress;
    public $autoUpgradePlugins;
    public $autoUpgradeThemes;
    public $noEmail;

    public function getId()
    {
        return $this->id;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getFileList()
    {
        return $this->fileList;
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

    public function getPath()
    {
        return $this->path;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setPath($path)
    {
        $this->path = $path;
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

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
        return $this;
    }
    
    public function getDomain() {
        return $this->domain;
    }

    public function setDomain($domain) {
        $this->domain = $domain;
        return $this;
    }

    public function isStaging()
    {
        return $this->staging;
    }

    public function setStaging($staging)
    {
        $this->staging = $staging;
    }

    public function isCloneable()
    {
        return $this->isCloneable;
    }

    public function setIsCloneable($isCloneable)
    {
        $this->isCloneable = $isCloneable;
    }

    /**
     * @return mixed
     */
    public function getCanStaging()
    {
        return $this->canStaging;
    }

    /**
     * @param mixed $canStaging
     */
    public function setCanStaging($canStaging)
    {
        $this->canStaging = $canStaging;
    }

    /**
     * @return mixed
     */
    public function getEditDbName()
    {
        return $this->editDbName;
    }

    /**
     * @param mixed $editDbName
     */
    public function setEditDbName($editDbName)
    {
        $this->editDbName = $editDbName;
    }

    /**
     * @return mixed
     */
    public function getEditDbUser()
    {
        return $this->editDbUser;
    }

    /**
     * @param mixed $editDbUser
     */
    public function setEditDbUser($editDbUser)
    {
        $this->editDbUser = $editDbUser;
    }

    /**
     * @return mixed
     */
    public function getEditDbPassword()
    {
        return $this->editDbPassword;
    }

    /**
     * @param mixed $editDbPassword
     */
    public function setEditDbPassword($editDbPassword)
    {
        $this->editDbPassword = $editDbPassword;
    }

    /**
     * @return mixed
     */
    public function getEditDbHost()
    {
        return $this->editDbHost;
    }

    /**
     * @param mixed $editDbHost
     */
    public function setEditDbHost($editDbHost)
    {
        $this->editDbHost = $editDbHost;
    }

    /**
     * @return mixed
     */
    public function getAdminUsername()
    {
        return $this->adminUsername;
    }

    /**
     * @param mixed $adminUsername
     */
    public function setAdminUsername($adminUsername)
    {
        $this->adminUsername = $adminUsername;
    }

    /**
     * @return mixed
     */
    public function getAdminPassword()
    {
        return $this->adminPassword;
    }

    /**
     * @param mixed $adminPassword
     */
    public function setAdminPassword($adminPassword)
    {
        $this->adminPassword = $adminPassword;
    }

    /**
     * @return mixed
     */
    public function getAutoUpgradeWordpress()
    {
        return $this->autoUpgradeWordpress;
    }

    /**
     * @param mixed $autoUpgradeWordpress
     */
    public function setAutoUpgradeWordpress($autoUpgradeWordpress)
    {
        $this->autoUpgradeWordpress = $autoUpgradeWordpress;
    }

    /**
     * @return mixed
     */
    public function getAutoUpgradePlugins()
    {
        return $this->autoUpgradePlugins;
    }

    /**
     * @param mixed $autoUpgradePlugins
     */
    public function setAutoUpgradePlugins($autoUpgradePlugins)
    {
        $this->autoUpgradePlugins = $autoUpgradePlugins;
    }

    /**
     * @return mixed
     */
    public function getAutoUpgradeThemes()
    {
        return $this->autoUpgradeThemes;
    }

    /**
     * @param mixed $autoUpgradeThemes
     */
    public function setAutoUpgradeThemes($autoUpgradeThemes)
    {
        $this->autoUpgradeThemes = $autoUpgradeThemes;
    }

    /**
     * @return mixed
     */
    public function getNoEmail()
    {
        return $this->noEmail;
    }

    /**
     * @param mixed $noEmail
     */
    public function setNoEmail($noEmail)
    {
        $this->noEmail = $noEmail;
    }
}
