<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces;


interface CanClone
{
    public function installationClone($id, $post);
}