<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Softaculous\Helper;


class AutoId
{
    public function generateAutoId()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 32; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}