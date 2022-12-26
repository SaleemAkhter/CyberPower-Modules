<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

class AdminFeaturesWithControllers
{

    protected static $adminFeaturesWithControllers           = [
        FeaturesSettingsConstants::ADMIN_ADD_USER     => 'AddNewUser',
        FeaturesSettingsConstants::ADMIN_USER_MANGER     => 'UserManager',
        FeaturesSettingsConstants::ADMIN_MY_USERS     => 'MyUsers',
        FeaturesSettingsConstants::ADMIN_MANAGE_USER_PACKAGES     => 'UserPackages',
        FeaturesSettingsConstants::ADMIN_MOVE_USERS_BETWEEN_RESELLERS     => 'MoveBetweenResellers',
        FeaturesSettingsConstants::ADMIN_EDIT_USER_MESSAGE     => 'EditUserMessage',
        FeaturesSettingsConstants::ADMIN_CHANGE_PASSWORD     => 'ChangePasswords',
        FeaturesSettingsConstants::ADMIN_CREATE_RESELLER     => 'CreateReseller',
        FeaturesSettingsConstants::ADMIN_LIST_RESELLER     => 'ListReseller',
        FeaturesSettingsConstants::ADMIN_MANAGE_RESELLER_PACKAGE     => 'ResellerPackages',
        FeaturesSettingsConstants::ADMIN_SUSPENSION_MESSAGE     => 'SuspensionMessage',

        FeaturesSettingsConstants::ADMIN_SETTINGS        => 'Settings',
        FeaturesSettingsConstants::ADMIN_HTTPD_CONFIG        => 'HttpdConfig',
        FeaturesSettingsConstants::ADMIN_DNS_MANAGER        => 'DnsManager',
        FeaturesSettingsConstants::ADMIN_IP_MANAGER        => 'IpManager',
        FeaturesSettingsConstants::ADMIN_NAMESERVERS        => 'Nameservers',

        FeaturesSettingsConstants::ADMIN_MULTISERVER_SETUP        => 'MultiserverSetup',
        FeaturesSettingsConstants::ADMIN_PHP_CONFIG        => 'PhpConfig',
        FeaturesSettingsConstants::ADMIN_SSH_KEYS        => 'SshKey',
        FeaturesSettingsConstants::ADMIN_BACKUP_TRANSFER        => 'BackupTransfer',
        FeaturesSettingsConstants::ADMIN_BRUTEFORCE_MONITOR        => 'BruteForceMonitor',
        FeaturesSettingsConstants::ADMIN_PROCESS_MONITOR        => 'ProcessMonitor',
        FeaturesSettingsConstants::ADMIN_MAIL_QUEUE        => 'MailQueue',
        FeaturesSettingsConstants::ADMIN_SERVICE_MONITOR        => 'ServiceMonitor',
        FeaturesSettingsConstants::ADMIN_SYSTEM_BACKUP        => 'SystemBackup',
        FeaturesSettingsConstants::ADMIN_MESSAGE_ALL_USERS        => 'Message',
        FeaturesSettingsConstants::ADMIN_ALL_CRONS        => 'Cron',
        FeaturesSettingsConstants::ADMIN_FILE_MANAGER        => 'FileManager',
        FeaturesSettingsConstants::ADMIN_FILE_EDITOR        => 'FileEditor',
        FeaturesSettingsConstants::ADMIN_SYSTEM_INFO        => 'SysInfo',
        FeaturesSettingsConstants::ADMIN_LOG_VIEWER        => 'LogViewer',
        FeaturesSettingsConstants::ADMIN_COMPLETE_USAGE_STATS        => 'UsageStats',
        FeaturesSettingsConstants::ADMIN_PLUGIN_MANAGER        => 'PluginManager',
        FeaturesSettingsConstants::ADMIN_FIREWALL        => 'Firewall',
        FeaturesSettingsConstants::ADMIN_CUSTOM_BUILD        => 'CustomBuild',


    ];
    protected static $oneClickLogin           = [
        FeaturesSettingsConstants::DIRECTADMIN_LOGIN    => 'directAdmin',
        FeaturesSettingsConstants::WEBMAIL_LOGIN        => 'webmail',
        FeaturesSettingsConstants::PHPMYADMIN_LOGIN     => 'phpmyadmin',
        FeaturesSettingsConstants::SITEPAD_LOGIN        => 'sitepad'
    ];

    public static function get()
    {
        return self::$adminFeaturesWithControllers;
    }

    public static function getOneClickLogin()
    {
        return self::$oneClickLogin;
    }
}
