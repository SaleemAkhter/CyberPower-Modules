<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

class ResellersFeaturesWithControllers
{

    protected static $resellersFeaturesWithControllers           = [
        FeaturesSettingsConstants::USER_MANAGER    => 'Users',
        FeaturesSettingsConstants::PACKAGE_MANAGER        => 'Packages',
        FeaturesSettingsConstants::IP_MANAGER        => 'IpManagement',
        FeaturesSettingsConstants::CHANGE_PASSWORDS        => 'ChangePasswords',
        FeaturesSettingsConstants::NAMESERVERS        => 'Nameservers'
    ];
    protected static $oneClickLogin           = [
        FeaturesSettingsConstants::DIRECTADMIN_LOGIN    => 'directAdmin',
        FeaturesSettingsConstants::WEBMAIL_LOGIN        => 'webmail',
        FeaturesSettingsConstants::PHPMYADMIN_LOGIN     => 'phpmyadmin',
        FeaturesSettingsConstants::SITEPAD_LOGIN        => 'sitepad'
    ];

    public static function get()
    {
        return self::$resellersFeaturesWithControllers;
    }

    public static function getOneClickLogin()
    {
        return self::$oneClickLogin;
    }
}
