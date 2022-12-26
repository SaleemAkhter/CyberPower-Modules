<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers;

/**
 * Class WhmcsParams
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class WhmcsParams
{
    const SERVICE_ID     = 'serviceid';
    const USER_ID        = 'userid';
    const PACKAGE_ID     = 'packageid';
    const SERVER_ID      = 'serverid';
    const CUSTOM_FIELDS  = 'customfields';
    const CONFIG_OPTIONS = 'configoptions';
    const DOMAIN         = 'domain';

    public static function getEssentialsKeys()
    {
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}