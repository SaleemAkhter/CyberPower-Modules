<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Helpers;

/**
 * Description of Script Helper
 * Provides more details from InstallationScript Model
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class Script
{
    protected $installer;
    protected $scripts;
    protected $categoryId;

    /**
     * @var \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\InstallationScript
     */
    protected $installationScript;
    protected $name;
    protected $categoryString;
    protected $img;

    public function __construct($scripts)
    {
        $this->scripts = $scripts;
    }

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
        $this->loadData();

        return $this;
    }

    public function setInstallerType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function loadData()
    {
        foreach ($this->scripts as $categoryString => $installScripts)
        {
            if (array_key_exists($this->categoryId, $installScripts))
            {
                $this->installationScript = $installScripts[$this->categoryId];
                $this->categoryString     = $categoryString;
                $this->name               = $this->installationScript->getName();
                $this->img                = $this->getImageByInstaller($this->type);

                break;
            }
        }
    }

    private function getImageByInstaller($type)
    {
        $method = $type . 'Image';
        if (!method_exists($this, $method))
        {
            throw new \Exception('Installer Script Helper: ' . $method . ' method does not exist!');
        }

        return $this->{$method}();
    }

    private function softaculousImage()
    {
        return 'https://www.softaculous.net/images/softimages/' . $this->categoryId . '__logo.gif';
    }

    private function installatronImage()
    {
        if(strpos($this->installationScript->getImage(), 'https://') !== false)
        {
            return $this->installationScript->getImage();
        }
        return 'https://installatron.com/images/remote/' . $this->installationScript->getImage();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCategoryString()
    {
        return $this->categoryString;
    }

    public function getImage()
    {
        return $this->img;
    }

    public function getInstallationScript()
    {
        return $this->installationScript;
    }
}
