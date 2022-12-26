<?php

namespace ModulesGarden\Servers\HetznerVps\Core\App\Packages;

abstract class BasePackageConfiguration
{
    protected $configuration = null;
    protected $appConfigFound = false;
    protected $appConfigInstance = null;


    static $protectedConfigOptions = [
        PackageConfigurationConst::VERSION,
        PackageConfigurationConst::PACKAGE_NAME
    ];

    public function __get($key)
    {
        $this->loadConfiguration();

        if(isset($this->configuration[$key]))
        {
            return $this->configuration[$key];
        }

        return null;
    }

    public function getConfig()
    {
        $this->loadConfiguration();

        return $this->configuration;
    }

    public function getName()
    {
        $this->loadConfiguration();

        return $this->configuration[PackageConfigurationConst::PACKAGE_NAME];
    }

    public function loadConfiguration($forceReload = false)
    {
        if (!($this->configuration === null || $forceReload))
        {
            return;
        }

        $config = $this::CONFIGURATION;
        $packageName = $config[PackageConfigurationConst::PACKAGE_NAME];

        $appPackageConfig = $this->getAppPackageConfig($packageName);

        $merged = array_merge($config, $appPackageConfig);
        foreach (self::$protectedConfigOptions as $protectedOption)
        {
            $merged[$protectedOption] = $config[$protectedOption];
        }

        $this->configuration = $merged;
    }

    public function getAppPackageConfig($packageName = null)
    {
        $appConfigClassName = '\ModulesGarden\Servers\HetznerVps\App\Config\Packages\\' . $packageName;
        if (!class_exists($appConfigClassName) || !is_subclass_of($appConfigClassName, AppPackageConfiguration::class)
            || !defined($appConfigClassName . '::APP_CONFIGURATION'))
        {

            return [];
        }

        $this->appConfigFound = true;
        $this->appConfigInstance = new $appConfigClassName();

        return $appConfigClassName::APP_CONFIGURATION;
    }

    /**
     * @return null
     */
    public function getAppConfigInstance()
    {
        return $this->appConfigInstance;
    }

}
