<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use ModulesGarden\Servers\DirectAdminExtended\App\Models\Hosting;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\DirectAdmin;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\PhpMyAdmin;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\WebMail;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\SitePad;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesWithControllers;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use function \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;

class HomePage extends BaseContainer implements ClientArea
{

    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,
        WhmcsParams;
    const ACCOUNT_SECTION               = 'accountSection';
    const EMAIL_MANAGEMENT_SECTION      = 'emailManagementSection';
    const ADVANCED_FEATURES_SECTION     = 'advancedFeaturesSection';
    const ONECLICK_LOGIN_SECTION        = 'oneclickLoginSection';

    protected $accountSection           = [
        FeaturesSettingsConstants::ADDON_DOMAIN     => 'AddonDomains',
        FeaturesSettingsConstants::INSTALL_APPS     => 'Applications',
        FeaturesSettingsConstants::BACKUP           => 'Backups',
        FeaturesSettingsConstants::DATABASES        => 'Databases',
        FeaturesSettingsConstants::DNS_MANAGE        => 'DnsManage',
        FeaturesSettingsConstants::FILE_MANAGER     => 'FileManager',
        FeaturesSettingsConstants::FTP              => 'Ftp',
        FeaturesSettingsConstants::HOTLINK_PROTECTION => 'HotlinkProtection',
        FeaturesSettingsConstants::PERL_MODULES     => 'PerlModules',
        FeaturesSettingsConstants::SITE_SUMMARY     => 'SiteSummary',
        FeaturesSettingsConstants::SUBDOMAINS       => 'Subdomains',
    ];
    protected $emailManagementSection   = [
        FeaturesSettingsConstants::AUTORESPONDERS   => 'Autoresponders',
        FeaturesSettingsConstants::CATCH_EMAILS     => 'CatchEmails',
        FeaturesSettingsConstants::EMAILS           => 'Emails',
        FeaturesSettingsConstants::EMAIL_FORWARDERS => 'EmailForwarders',
        FeaturesSettingsConstants::MAILING_LISTS    => 'MailingLists',
        FeaturesSettingsConstants::SPAM_FILTERS     => 'SpamFilters',
        FeaturesSettingsConstants::SPAM_ASSASIN     => 'SpamAssasin',
        FeaturesSettingsConstants::VACATION         => 'Vacation',
        FeaturesSettingsConstants::MX_RECORDS       => 'MxRecords'
    ];
    protected $advancedFeaturesSection  = [
        FeaturesSettingsConstants::APACHE_HANDLERS  => 'ApacheHandlers',
        FeaturesSettingsConstants::CRON             => 'Cron',
        FeaturesSettingsConstants::ERROR_PAGES      => 'ErrorPages',
        FeaturesSettingsConstants::DOMAIN_POINTERS  => 'DomainPointers',
        FeaturesSettingsConstants::PROTECTED_DIRECTORIES => 'ProtectedDirectories',
        FeaturesSettingsConstants::SITE_REDIRECTION => 'SiteRedirection',
        FeaturesSettingsConstants::SSH              => 'Ssh',
        FeaturesSettingsConstants::SSL              => 'Ssl',
        FeaturesSettingsConstants::WORDPRESS_MANAGER => 'WordPressManager'
    ];
    protected $sectionHeaders           = [
        self::ACCOUNT_SECTION               => 'yourAccount',
        self::EMAIL_MANAGEMENT_SECTION      => 'emailManagement',
        self::ADVANCED_FEATURES_SECTION     => 'advancedFeatures',
    ];

    public function getAssetsUrl()
    {
        return BuildUrl::getAppAssetsURL();
    }


    public function getRedirectLinks()
    {
        $enabledRedirects = [];

        foreach (FeaturesWithControllers::getOneClickLogin() as $setting => $name)
        {
            if ($this->isFeatureEnabled($setting) === false 
                    || ($setting === FeaturesSettingsConstants::WEBMAIL_LOGIN && !ServerSettings::factory($this->whmcsParams['serverid'])->getWebmailLink()))
            {
                continue;
            }
            $enabledRedirects[$name] = BuildUrl::getUrl('OneClickLogin', $name);
        }

        return $enabledRedirects;
    }

    public function getRedirectUrl($contoller)
    {
        $params          = sl('request')->query->all();
        $params['modop'] = 'custom';
        $params['a']     = 'management';

        if($contoller === 'WordPressManager'){
            unset($params['action'], $params['id']);
            $contoller = lcfirst($contoller);
        }elseif($contoller === 'Applications'){
            global $CONFIG;
            return  $CONFIG['SystemURL']."/index.php?m=WordpressManager&id=".$params['id'];
        }

        return BuildUrl::getUrl($contoller, null, $params);
    }

    public function getRedirectUrlOneClick($contoller)
    {
        return BuildUrl::getUrl('OneClickLogin', $contoller, sl('request')->query->all());
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

    public function isEnabledShowIP()
    {
        return $this->isFeatureEnabled(FeaturesSettingsConstants::SHOW_IP);
    }

    public function getDedicatedIp()
    {
        return Hosting::where('id', sl('request')->get('id'))->first()->dedicatedip;
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
