<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Custom\AutoinstallerOnCreate;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ApplicationInstaller;

class Installer
{
    private $appInstaller;
    private $installationModel;
    private $applicationName;

    public static function init(array $params, $applicationName)
    {
        return new self($params, $applicationName);
    }

    public function __construct(array $params, $applicationName)
    {
        if ($applicationName)
        {
            $this->applicationName = $applicationName;
        }
        $this->appInstaller      = new ApplicationInstaller($params);
        $this->installationModel = (new CustomFieldsModel())->getByParams($params, $this->appInstaller->getInstallerName());
    }

    public function setApplicationName($applicationName)
    {
        $this->applicationName = $applicationName;

        return $this;
    }

    public function run()
    {
        $script = $this->appInstaller->getScriptByName($this->applicationName);
        if (!$script)
        {
            throw new \Exception(sprintf('Installation script for %s cannot be found', $this->applicationName));
        }
        $this->installationModel->setVersion($script->getVersion());
        $this->installationModel->setApplication($script->getSid());
        $this->appInstaller->getInstaller()->installationCreate($script->getSid(), $this->installationModel);
    }
}
