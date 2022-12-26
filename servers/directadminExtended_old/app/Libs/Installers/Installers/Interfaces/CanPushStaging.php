<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces;


interface CanPushStaging
{
    public function installationStaging($aid , $data = []);

    public function installationPushToLive($aid);
}