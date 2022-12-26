<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use \ModulesGarden\Servers\DirectAdminExtended\App\Models\FunctionsSettings;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Factory;

class ApplicationInstaller
{
    protected $params;

    /**
     *
     * @var \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\ApplicationInstaller
     */
    protected $installer;

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function getInstaller()
    {

        if ($this->installer)
        {
            return $this->installer;
        }

        if ($this->getInstallerName() == 1)
        {
            $installerName = 'Softaculous';
        }
        else
        {
            $installerName = 'Installatron';
        }

        $factory         = new Factory;
        $this->installer = $factory->build($installerName, 'directAdmin', $this->params);
        return $this->installer;
    }

    /**
     * This method returns a number an installer, not an installer name...!!!
     *
     * @return integer
     */
    public function getInstallerName()
    {
        $productId = $this->params['pid'];
        $type      = FunctionsSettings::where('product_id', '=', $productId)->first()->apps_installer_type;

        return $type;
    }

    public function getScriptByName($scriptName)
    {
        if (!is_string($scriptName))
        {
            return false;
        }
        if (is_null($this->installer))
        {
            $this->getInstaller();
        }

        $scripts = $this->installer->getInstallationScripts();

        if ($this->getInstallerName() == 'default')
        {
            return $this->getScriptByNameDefault($scripts, $scriptName);
        }

        $new = [];
        foreach ($scripts as $category => $installScripts)
        {
            foreach ($installScripts as $each)
            {
                $new[$category][$each->getSid()] = $each;
            }
        }

        foreach ($new as $category => $installScripts)
        {
            foreach ($installScripts as $sid => $obj)
            {

                if ($obj->getName() . ' ' . $obj->getVersion() == $scriptName)
                {
                    return $new[$category][$sid];
                }
            }
        }

        foreach ($new as $category => $installScripts)
        {
            foreach ($installScripts as $sid => $obj)
            {
                if ($obj->getName() == $scriptName)
                {
                    return $new[$category][$sid];
                }
            }
        }

        return null;
    }

    public function getScriptByNameDefault(array $data, $scriptName)
    {
        foreach ($data as $key => $installScripts)
        {
            if ($installScripts->getName() . ' ' . $installScripts->getVersion() == $scriptName)
            {
                return $data[$key];
            }
        }

        foreach ($data as $key => $installScripts)
        {
            if ($installScripts->getName() == $scriptName)
            {
                return $data[$key];
            }
        }

        return null;
    }
}
