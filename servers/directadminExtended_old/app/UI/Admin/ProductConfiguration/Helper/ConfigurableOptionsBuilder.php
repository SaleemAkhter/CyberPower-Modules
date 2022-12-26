<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Helper;

use ModulesGarden\Servers\DirectAdminExtended\App\Services\ConfigurableOptions\ConfigurableOptions;
use ModulesGarden\Servers\DirectAdminExtended\App\Services\ConfigurableOptions\Helper\TypeConstans;
use ModulesGarden\Servers\DirectAdminExtended\App\Services\ConfigurableOptions\Models\Option;
use ModulesGarden\Servers\DirectAdminExtended\App\Services\ConfigurableOptions\Models\SubOption;

/**
 * Description of Config
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ConfigurableOptionsBuilder
{

    public static function build(ConfigurableOptions $configurableOptions, $fieldsStatus = [])
    {

        foreach ($fieldsStatus as $key => $field)
        {
            if ($field == "on")
            {

                $option = self::convertToCamelCase($key, ' ');
                $configurableOptions->addOption(self::{$option}());
            }
        }
        return $configurableOptions;
    }

    public static function buildAll(ConfigurableOptions $configurableOptions)
    {
        $configurableOptions->addOption(self::diskSpace())
                            ->addOption(self::bandwidth())
                            ->addOption(self::inode())
                            ->addOption(self::fTPAccounts())
                            ->addOption(self::emailAccounts())
                            ->addOption(self::mySQLDatabases())
                            ->addOption(self::subdomains())
                            ->addOption(self::parkedDomains())
                            ->addOption(self::addonDomains())
                            ->addOption(self::mailingLists())
                            ->addOption(self::autoResponders())
                            ->addOption(self::emailForwards())
                            ->addOption(self::dedicatedIP())
                            ->addOption(self::suspendAtLimit())
                            ->addOption(self::cGIAccess())
                            ->addOption(self::shellAccess())
                            ->addOption(self::pHP())
                            ->addOption(self::sSL())
                            ->addOption(self::systemInfo())
                            ->addOption(self::dNSControl())
                            ->addOption(self::cronJobs())
                            ->addOption(self::catchAll())
                            ->addOption(self::spamAssassin())
                            ->addOption(self::anonFTP());



        return $configurableOptions;
    }

    private static function diskSpace()
    {
        $option  = new Option('Disk Space', 'Disk Space [MB]', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', 'MB'));
        return $option;
    }
    private static function bandwidth()
    {
        $option  = new Option('Bandwidth', 'Bandwidth [MB]', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', 'MB'));
        return $option;
    }
    private static function fTPAccounts()
    {
        $option  = new Option('FTP Accounts', 'FTP Accounts', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', '1'));
        return $option;
    }
    private static function emailAccounts()
    {
        $option  = new Option('Email Accounts', 'Email Accounts', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', '1'));
        return $option;
    }
    private static function mySQLDatabases()
    {
        $option  = new Option('MySQL Databases', 'MySQL Databases', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', '1'));
        return $option;
    }
    private static function subdomains()
    {
        $option  = new Option('Subdomains', 'Subdomains', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', '1'));
        return $option;
    }
    private static function parkedDomains()
    {
        $option  = new Option('Parked Domains', 'Parked Domains', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', '1'));
        return $option;
    }
    private static function addonDomains()
    {
        $option  = new Option('Addon Domains', 'Addon Domains', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', '1'));
        return $option;
    }

    private static function cGIAccess()
    {
        $option = new Option('CGI Access', 'CGI Access', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('cGIAccess', 'Enable CGI Access'));
        return $option;
    }

    private static function shellAccess()
    {
        $option = new Option('Shell Access', 'SSH Access', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('shellAccess', 'Enable Shell Access'));
        return $option;
    }

    private static function pHP()
    {
        $option = new Option('PHP', 'PHP Access', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('pHP', 'Enable PHP'));
        return $option;
    }

    private static function sSL()
    {
        $option = new Option('SSL', 'SSL Access', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('sSL', 'Enable SSL'));
        return $option;
    }

    private static function systemInfo()
    {
        $option = new Option('System Info', 'System Information', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('systemInfo', 'Enable System Info'));
        return $option;
    }

    private static function dNSControl()
    {
        $option = new Option('DNS Control', 'DNS Control', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('dNSControl', 'Enable DNS Control'));
        return $option;
    }

    private static function cronJobs()
    {
        $option = new Option('Cron Jobs', 'Cron Jobs', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('cronJobs', 'Enable Cron Jobs'));
        return $option;
    }

    private static function catchAll()
    {
        $option = new Option('Catch All', 'Catch All Email', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('catchAll', 'Enable Catch All'));
        return $option;
    }

    private static function dedicatedIP()
    {
        $option = new Option('Dedicated IP', 'Dedicated IP Address', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('dedicatedIP', 'Enable Dedicated IP'));
        return $option;
    }

    private static function suspendAtLimit()
    {
        $option = new Option('Suspend At Limit', 'Suspend At Limit', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('suspendAtLimit', 'Enable Suspend At Limit'));
        return $option;
    }

    private static function spamAssassin()
    {
        $option = new Option('Spam Assassin', 'SpamAssassin', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('spamAssassin', 'Enable Spam Assassin'));
        return $option;
    }

    private static function anonFTP()
    {
        $option = new Option('Anon FTP', 'Anonymous FTP Accounts', TypeConstans::CHECKBOX);
        $option->addSubOption(new SubOption('anonFTP', 'Enable Anon FTP'));
        return $option;
    }
    private static function emailForwards()
    {
        $option = new Option('Email Forwards', 'Email Forwarders', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', '1'));
        return $option;
    }

    private static function mailingLists()
    {
        $option  = new Option('Mailing Lists', 'Mailing Lists', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', '1'));
        return $option;
    }

    private static function autoResponders()
    {
        $option  = new Option('Auto Responders', 'Autoresponders', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', '1'));
        return $option;
    }

    private static function inode()
    {
        $option  = new Option('Inode', 'Inode Limit', TypeConstans::QUANTITY);
        $option->addSubOption(new SubOption('1', '1'));
        return $option;
    }

    private static function convertToCamelCase($string, $delimiter = "_", $addPrefix = ""){
        $explodeString = explode($delimiter, $string);
        $newString = "";

        foreach($explodeString as $value){
            if(empty($newString) && $addPrefix != ""){
                $newString = lcfirst($addPrefix);
            }elseif(empty($newString) && $addPrefix == ""){
                $newString = lcfirst($value);
                continue;
            }
            $newString .= ucfirst(($value));
        }
        return $newString;
    }


}
