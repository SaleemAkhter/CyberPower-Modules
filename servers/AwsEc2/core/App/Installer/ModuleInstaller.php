<?php

namespace ModulesGarden\Servers\AwsEc2\Core\App\Installer;

use ModulesGarden\Servers\AwsEc2\Core\App\Packages\PackageManager;
use ModulesGarden\Servers\AwsEc2\Core\DependencyInjection;
use ModulesGarden\Servers\AwsEc2\Core\ModuleConstants;
use ModulesGarden\Servers\AwsEc2\Packages\Provisioning\Database\Update;

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

            $packageDataPath =  ModuleConstants::getFullPath('packages', $packageName, 'Database', 'data.sql');
            if (file_exists($packageDataPath) && is_readable($packageDataPath))
            {
                if(class_exists('\ModulesGarden\Servers\AwsEc2\Packages\Provisioning\Database\Update'))
                {
                    $update = new Update();
                    if(!$update->update()) {
                        $this->databaseInstaller->performQueryFromFile($packageDataPath);
                        $packageDataConstraintsPath =  ModuleConstants::getFullPath('packages', $packageName, 'Database', 'data_constraints.sql');
                        if (file_exists($packageDataConstraintsPath) && is_readable($packageDataConstraintsPath))
                        {
                            if(class_exists('\ModulesGarden\Servers\AwsEc2\Packages\Provisioning\Database\Update'))
                                if((new Update())->prepareForConstraints())
                                    $this->databaseInstaller->performQueryFromFile($packageDataConstraintsPath);
                        }
                    }
                }

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
