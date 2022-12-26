<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

class FeaturesWithControllers
{
    protected static $featuresWithControllers = [
        FeaturesSettingsConstants::ADDON_DOMAIN     => 'AddonDomains',
        FeaturesSettingsConstants::AUTORESPONDERS   => 'Autoresponders',
        FeaturesSettingsConstants::INSTALL_APPS     => 'Applications',
        FeaturesSettingsConstants::BACKUP           => 'Backups',
        FeaturesSettingsConstants::CATCH_EMAILS     => 'CatchEmails',
        FeaturesSettingsConstants::DATABASES        => 'Databases',
        FeaturesSettingsConstants::DNS              => 'DnsSettings',
        FeaturesSettingsConstants::DNS_MANAGE       => 'DnsManage',
        FeaturesSettingsConstants::DOMAIN_POINTERS  => 'DomainPointers',
        FeaturesSettingsConstants::EMAILS           => 'Emails',
        FeaturesSettingsConstants::EMAIL_FORWARDERS => 'EmailForwarders',
        FeaturesSettingsConstants::FILE_MANAGER     => 'FileManager',
        FeaturesSettingsConstants::FTP              => 'Ftp',
        FeaturesSettingsConstants::MAILING_LISTS    => 'MailingLists',
        FeaturesSettingsConstants::PERL_MODULES     => 'PerlModules',
        FeaturesSettingsConstants::SITE_SUMMARY     => 'SiteSummary',
        FeaturesSettingsConstants::SPAM_FILTERS     => 'SpamFilters',
        FeaturesSettingsConstants::SSH              => 'Ssh',
        FeaturesSettingsConstants::SSL              => 'Ssl',
        FeaturesSettingsConstants::SUBDOMAINS       => 'Subdomains',
        FeaturesSettingsConstants::VACATION         => 'Vacation',
        FeaturesSettingsConstants::APACHE_HANDLERS  => 'ApacheHandlers',
        FeaturesSettingsConstants::CRON             => 'Cron',
        FeaturesSettingsConstants::SITE_REDIRECTION => 'SiteRedirection',
        FeaturesSettingsConstants::ERROR_PAGES      => 'ErrorPages',
        FeaturesSettingsConstants::SPAM_ASSASIN     => 'SpamAssasin',
        FeaturesSettingsConstants::HOTLINK_PROTECTION     => 'HotlinkProtection',
        FeaturesSettingsConstants::PROTECTED_DIRECTORIES     => 'ProtectedDirectories',
        FeaturesSettingsConstants::WORDPRESS_MANAGER     => 'WordPressManager',

    ];
    protected static $oneClickLogin           = [
        FeaturesSettingsConstants::DIRECTADMIN_LOGIN    => 'directAdmin',
        FeaturesSettingsConstants::WEBMAIL_LOGIN        => 'webmail',
        FeaturesSettingsConstants::PHPMYADMIN_LOGIN     => 'phpmyadmin',
        FeaturesSettingsConstants::SITEPAD_LOGIN        => 'sitepad'
    ];

    public static function get()
    {
        return self::$featuresWithControllers;
    }

    public static function getOneClickLogin()
    {
        return self::$oneClickLogin;
    }
}
