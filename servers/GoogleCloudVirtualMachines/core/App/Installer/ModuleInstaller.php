<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Installer;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\Packages\PackageManager;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\DependencyInjection;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\ModuleConstants;

class ModuleInstaller
{
    protected $databaseInstaller = null;

    public function __construct()
    {
        $this->databaseInstaller = DependencyInjection::get(DatabaseInstaller::class);
    }

    public function makeInstall()
    {
        $this->installModule();
        $this->installPackages();
    }

    public function installModule()
    {
        array_map(function ($value){
            $this->databaseInstaller->performQueryFromFile($value);
        }, $this->getModuleQueriesPaths());
    }

    public function getModuleQueriesPaths()
    {
        return [
            ModuleConstants::getFullPath('core', 'Database', 'schema.sql'),
            ModuleConstants::getFullPath('app', 'Database', 'schema.sql'),
            ModuleConstants::getFullPath('core', 'Database', 'data.sql'),
            ModuleConstants::getFullPath('app', 'Database', 'data.sql')
        ];
    }

    public function installPackages()
    {
        $packageManager = DependencyInjection::get(PackageManager::class);

        array_map(function ($package){
            $packageName = $package->getName();

            $packageSchemaPath =  ModuleConstants::getFullPath('packages', $packageName, 'Database', 'schema.sql');
            if (file_exists($packageSchemaPath) && is_readable($packageSchemaPath))
            {
                $this->databaseInstaller->performQueryFromFile($packageSchemaPath);
            }

            $packageDataPath =  ModuleConstants::getFullPath('packages', $packageName, 'Database', 'schema.sql');
            if (file_exists($packageDataPath) && is_readable($packageDataPath))
            {
                $this->databaseInstaller->performQueryFromFile($packageDataPath);
            }
        }, $packageManager->getPackagesConfiguration());
    }

    public function isInstallCorrect()
    {
        return $this->databaseInstaller->isInstallCorrect();
    }

    public function getFailedQueries()
    {
        return $this->databaseInstaller->getFailedQueries();
    }

    public function getQueriesResults()
    {
        return $this->databaseInstaller->getQueriesResults();
    }
}
