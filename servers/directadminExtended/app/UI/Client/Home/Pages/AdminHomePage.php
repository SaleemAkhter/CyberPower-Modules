<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\DirectAdmin;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\PhpMyAdmin;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\WebMail;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\SitePad;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;

use function \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;


class AdminHomePage extends BaseContainer implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,WhmcsParams;

    protected $name = 'adminHomePage';
    protected $id = 'adminHomePage';
    protected $title = 'adminHomePage';
    const ACCOUNT_SECTION               = 'accountSection';
    const SERVER_MANAGER_SECTION        ='serverManagementSection';
    const ADMIN_TOOLS_SECTION           ='adminToolsSection';
    const SYS_INFO_SECTION              ='sysInfoSection';
    const ADVANCED_FEATURES_SECTION     ='advancedFeaturesSection';
    const SUPPORT_SECTION               ='supportSection';

    const USER_MANAGER = 'manage_users';
    const PACKAGE_MANAGER = 'manage_packages';
    const IP_MANAGER ='manage_ips';
    const CHANGE_PASSWORDS ='change_passwords';
    const NAMESERVERS ='nameservers';
    const ADMIN_ADD_USER='add_new_user';
    const ADMIN_USER_MANGER='admin_user_manager';

    protected $accountSection           = [
        FeaturesSettingsConstants::ADMIN_ADD_USER     => 'AddNewUser',
        FeaturesSettingsConstants::ADMIN_USER_MANGER     => 'UserManager',
        FeaturesSettingsConstants::ADMIN_MY_USERS     => 'Users',
        FeaturesSettingsConstants::ADMIN_MANAGE_USER_PACKAGES     => 'UserPackages',
        FeaturesSettingsConstants::ADMIN_MOVE_USERS_BETWEEN_RESELLERS     => 'MoveBetweenResellers',
        FeaturesSettingsConstants::ADMIN_CHANGE_PASSWORD     => 'ChangePasswords',
        FeaturesSettingsConstants::ADMIN_CREATE_RESELLER     => 'CreateReseller',
        FeaturesSettingsConstants::ADMIN_LIST_RESELLER     => 'ListReseller',
        FeaturesSettingsConstants::ADMIN_MANAGE_RESELLER_PACKAGE     => 'ResellerPackages',
    ];
    protected $serverManagementSection           = [
        FeaturesSettingsConstants::ADMIN_SETTINGS        => 'Settings',
        // FeaturesSettingsConstants::ADMIN_HTTPD_CONFIG        => 'HttpdConfig',
        FeaturesSettingsConstants::ADMIN_DNS_MANAGER        => 'DnsManager',
        FeaturesSettingsConstants::ADMIN_IP_MANAGER        => 'IpManager',
        FeaturesSettingsConstants::ADMIN_NAMESERVERS        => 'Nameservers',
        FeaturesSettingsConstants::ADMIN_MULTISERVER_SETUP        => 'MultiserverSetup',
        FeaturesSettingsConstants::ADMIN_PHP_CONFIG        => 'PhpConfig',
        FeaturesSettingsConstants::ADMIN_SSH_KEYS        => 'SshKey',
    ];
    protected $adminToolsSection           = [
        FeaturesSettingsConstants::ADMIN_BACKUP_TRANSFER        => 'BackupTransfer',
        FeaturesSettingsConstants::ADMIN_BRUTEFORCE_MONITOR        => 'BruteForceMonitor',
        FeaturesSettingsConstants::ADMIN_PROCESS_MONITOR        => 'ProcessMonitor',
        FeaturesSettingsConstants::ADMIN_MAIL_QUEUE        => 'MailQueue',
        FeaturesSettingsConstants::ADMIN_SERVICE_MONITOR        => 'ServiceMonitor',
        FeaturesSettingsConstants::ADMIN_SYSTEM_BACKUP        => 'SystemBackup',
        FeaturesSettingsConstants::ADMIN_MESSAGE_ALL_USERS        => 'Message',
    ];
    protected $sysInfoSection           = [
        FeaturesSettingsConstants::ADMIN_ALL_CRONS        => 'Cron',
        FeaturesSettingsConstants::ADMIN_FILE_MANAGER        => 'FileManager',
        FeaturesSettingsConstants::ADMIN_FILE_EDITOR        => 'FileEditor',
        FeaturesSettingsConstants::ADMIN_SYSTEM_INFO        => 'SysInfo',
        FeaturesSettingsConstants::ADMIN_LOG_VIEWER        => 'LogViewer',
        FeaturesSettingsConstants::ADMIN_COMPLETE_USAGE_STATS        => 'UsageStats',
    ];
    protected $advancedFeaturesSection           = [
        FeaturesSettingsConstants::ADMIN_PLUGIN_MANAGER        => 'PluginManager',
        FeaturesSettingsConstants::ADMIN_FIREWALL        => 'Firewall',
        FeaturesSettingsConstants::ADMIN_CUSTOM_BUILD        => 'CustomBuild',
    ];


    protected $sectionHeaders           = [
        self::ACCOUNT_SECTION              => 'accountManagement',
        self::SERVER_MANAGER_SECTION       => 'serverManagement',
        self::ADMIN_TOOLS_SECTION          => 'adminTools',
        self::SYS_INFO_SECTION             => 'sysInfo',
        self::ADVANCED_FEATURES_SECTION    => 'advancedFeatures',
    ];

    public function getAssetsUrl()
    {
        return BuildUrl::getAppAssetsURL();
    }
    public function getRedirectUrl($contoller)
    {
        $params          = sl('request')->query->all();
        $params['modop'] = 'custom';
        $params['a']     = 'management';

        if($contoller === 'WordPressManager'){
            unset($params['action'], $params['id']);
            $contoller = lcfirst($contoller);
        }

        return BuildUrl::getUrl($contoller, null, $params);
    }

    public function getSection($section)
    {
        $return = [];
        if(!property_exists($this, $section))
        {
            return $return;
        }
        foreach($this->$section as $setting => $controller)
        {
            if ($this->isFeatureEnabled($setting) === false)
            {
                continue;
            }

            if($setting === 'wordpress_manager')
            {
                if (
                    \ModulesGarden\Servers\DirectAdminExtended\App\Libs\WordPressManager\WordPressManager::isActive() !== true
                    || !\ModulesGarden\Servers\DirectAdminExtended\App\Libs\WordPressManager\WordPressManager::activeForHosting($this->getHostingId())
                ) {
                    continue;
                }
            }

            $return[$setting] = $controller;
        }
        return $return;
    }

    public function getSectionHeaders()
    {
        $return = [];
        foreach($this->sectionHeaders as $section => $header)
        {
            if($this->getSection($section))
            {
                $return[$section] = $header;
            }
        }
        return $return;
    }
    public function getOneClickLoginButtons()
    {
        $buttons = [];
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::DIRECTADMIN_LOGIN))
        {
            $directAdmin = new DirectAdmin();
            $directAdmin->initContent();
            $buttons[] = $directAdmin;
        }
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::PHPMYADMIN_LOGIN))
        {
            $directAdmin = new PhpMyAdmin();
            $directAdmin->initContent();
            $buttons[] = $directAdmin;
        }
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::SITEPAD_LOGIN))
        {
            $sitepad = new SitePad();
            $sitepad->initContent();
            $buttons[] = $sitepad;
        }
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::WEBMAIL_LOGIN))
        {
            $directAdmin = new WebMail();
            $directAdmin->initContent();
            $buttons[] = $directAdmin;
        }
        return $buttons;
    }
}
