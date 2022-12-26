<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\ApplicationInstaller;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\InstallerProvider;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Exceptions\InvalidInstallerException;

/**
 * Description of Factory
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class Factory
{
    private $installer;

    /**
     * 
     * @param String $installer
     * @param String $provider
     * @param array params
     * @return \ModulesGarden\Servers\DirectAdminExtended\App\Interfaces\ApplicationsProvider
     */
    public function build($installer, $provider, array $params)
    {
        // cannot use 'Default' for class name
        if($installer === 'default')
        {
            $installer .= 'I';
        }
        $installerClass = __NAMESPACE__ . '\Installers\\' . ucfirst($installer);
        $providerClass  = __NAMESPACE__ . '\Providers\\' . ucfirst($provider);
        
        if (!class_exists($installerClass))
        {
            throw new InvalidInstallerException('Installer: ' . $installer . ' cannot be found.');
        }

        if (!class_exists($providerClass))
        {
            throw new \Exception('Provider: ' . $provider . ' cannot be found.');
        }

        $provider        = new $providerClass(ucfirst($installer), $params);
        $this->checkProviderType($provider);
        $this->installer = new $installerClass($provider);
        $this->checkInstanceType();

        return $this->installer;
    }

    private function isAppInterfaceType()
    {
        return ($this->installer instanceof ApplicationInstaller);
    }

    private function checkInstanceType()
    {
        if ($this->isAppInterfaceType() === false)
        {
            throw new \Exception('App Factory: Invalid type of instance.');
        }
    }

    private function isProviderInterfaceType($provider)
    {
        return ($provider instanceof InstallerProvider);
    }

    private function checkProviderType($provider)
    {
        if ($this->isProviderInterfaceType($provider) === false)
        {
            throw new \Exception('App Factory: Invalid type of provider.');
        }
    }
}
