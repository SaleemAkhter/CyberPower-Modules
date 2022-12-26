<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Helpers;


use ModulesGarden\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\WordPressManager\WordPressManager;


class FeaturesHelper
{


    private static $features = [
        'emails'        => [
            'autoresponders',
            'catch_emails',
            'emails',
            'email_forwarding',
            'mailinglists',
            'spamassasin',
            'emailfilters',
            'vacation',
            'mxrecords'
        ],
        'login'         => [
            'directadmin_login',
            'login_phpmyadmin',
            'sitepad_login',
            'webmail_login'
        ],
        'domains'       => [
            'addon_domains',
            'manage_dns',
            'parked_domains',
            'domain_forwarders',
            'subdomains'
        ],
        'others'        => [
            'apachehandlers',
            'change_password',
            'cron',
            'errorpages',
            'databases',
            'filemanager',
            'ftp',
            'hotlink_protection',
            'perl_modules',
            'protected_directories',
            'stats',
            'ssh',
            'ssl',
            'wordpress_manager'
        ],
        'backups'       => [
            'backups',
            'recreate'
        ],
        'accountUsage'  => [
            'usage_bandwidth',
            'usage_database',
            'usage_disk',
            'usage_email',
            'usage_ftp',
            'usage_domain',
            'usage_user',
        ],
        'accountManager'  => [
            'manage_users',
            'add_user',
            'add_package',
            'manage_packages',
            'manage_ips',
            'change_passwords',
            'nameservers'
        ],
        'adminAccountManager'  => [
            'admin_add_user',
            'user_manager',
            'my_users',
            'user_packages',
            'move_between_resellers',
            'create_reseller',
            'list_reseller',
            'reseller_packages',
        ],
        'serverManager'  => [
            'settings',
            'httpd_config',
            'dns_manager',
            'ip_manager',
            'php_config',
            'ssh_key'
        ],
        'adminTools'  => [
            'backup_transfer',
            'brute_force_monitor',
            'process_monitor',
            'mail_queue',
            'service_monitor',
            'system_backup',
        ],
        'sysInfo'  => [
            'file_manager',
            'file_editor',
            'sys_info',
            'log_viewer',
        ],
        'advancedFeatures'  => [
            'plugin_manager',
            'firewall',
            'custom_build'
        ]

    ];

    private static $featuresName = [
        'emails'        => [
            'Email',
            'Autoresponders',
            'Catch All Email',
            'Emails',
            'EmailForwarders',
            'MailingLists',
            'SpamassasinSetup',
            'SPAMFilters',
            'VacationMessages',
            'MxRecords'
        ],
        'login'         => [
            'OneClickLogin',
            'DirectAdmin',
            'phpMyAdmin',
            'SitePad',
            'Webmail',
        ],
        'domains'       => [
            'Domains',
            'AddonDomains',
            'DNS Management',
            'DomainsPointers',
            'SiteRedirections',
            'Subdomains'

        ],
        'others'        => [
            'Others',
            'ApacheHandlers',
            'ChangePassword',
            'CronJobs',
            'CustomErrorPages',
            'Databases',
            'FileManager',
            'FTPAccounts',
            'HotlinkProtection',
            'PerlModules',
            'ProtectedDirectories',
            'SiteSummary',
            'SSHKeys',
            'SSLCertificates',
            'WordPressManager'
        ],
        'backups'       => [
            'Backups',
            'Backups',
            'Recreate'
        ],
        'accountUsage'  => [
            'AccountUsage',
            'Bandwidth',
            'Databases',
            'DiskSpace',
            'E-Mails',
            'FTPAccounts',
            'DomainAccounts',
            'UserAccounts',
        ],
        'accountManager'  => [
            'Account Management',
            'List Users',
            'Add User',
            'List Packages',
            'Add Package',
            'Manage IPs',
            'Change Passwords',
            'Nameservers',
        ],
        'adminAccountManager'  => [
            'Admin Account Management',
            'Add User',
            'User Manager',
            'My Users',
            'User Packages',
            'Move Between Resellers',
            'Create Reseller',
            'List Reseller',
            'Reseller Packages'
        ],
        'serverManager'  => [
            'Server Manager',
            'Settings',
            'Custom HTTPD Configurations',
            'Dns Manager',
            'Ip Manager',

            'Php Configuration',
            'Ssh Keys'
        ],
        'adminTools'  => [
            'Admin Tools',
            'Backup Transfer',
            'Brute Force Monitor',
            'Process Monitor',
            'Mail Queue',
            'Service Monitor',
            'System Backup',
            'Message'
        ],
        'sysInfo'  => [
            'SysInfoAll',
            'File Manager',
            'File Editor',
            'Sys Info',
            'Log Viewer',
        ],
        'advancedFeatures'  => [
            'Advanced Features',
            'Plugin Manager',
            'Firewall',
            'Custom Build'
        ],

    ];

    public static function countEnabledFeatures($row)
    {
        $counter = 0;
        foreach (self::$features as $feat) {
            foreach ($feat as $item) {
                if ($row->$item == 'on') {
                    $counter++;
                }
            }
        }
        return $counter;
    }

    public static function getFeaturesNames()
    {
        if(WordPressManager::isActive())
        {
            self::$features['others'][] = 'wordpress_manager';
        }

        return self::$features;

    }

    public static function getName($indexName, $second = FALSE)
    {
        if (self::$featuresName[$indexName] && !$second) {
            return ServiceLocator::call('lang')->translate(self::$featuresName[$indexName][0]);
        }

        if ($second) {
            $key = array_search($indexName, self::$features[$second]);
            return ServiceLocator::call('lang')->translate(self::$featuresName[$second][$key + 1]);
        }
    }

    public static function parseInstallerType($number)
    {
        switch ($number) {
            case 1:
                return 'Softaculous';
            case 2:
                return 'Installatron';
            default:
                return '-';
        }
    }

    public static function listAvailableTemplates()
    {
        $directory = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))) . DS;
        if (!file_exists($directory)) {
            return array();
        }
        $di  = new \DirectoryIterator($directory);
        $out = array();
        foreach (new \IteratorIterator($di) as $filename => $file) {
            if ($file->isDir() && !$file->isDot() && $file->getFilename() != 'errors') {
                $out[$file->getFilename()] = $file->getFilename();
            }
        }
        return $out;
    }
}
