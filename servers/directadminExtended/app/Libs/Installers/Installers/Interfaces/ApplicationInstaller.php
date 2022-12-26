<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces;

use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Installation;
use \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Backup;

/**
 * Description of ApplicationInstaller
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
interface ApplicationInstaller
{

    public function getInstallations($showupdates = false);

    public function getInstallationScripts();

    public function installationCreate($installationId, Installation $model);

    public function installationDelete($installationId, Installation $model);

    public function getBackups();

    public function backupCreate($installationId, Backup $model);

    public function backupDelete($fileName);

    public function backupRestore($fileName);
    
    public function getScriptBySid($installationId);
    
}
