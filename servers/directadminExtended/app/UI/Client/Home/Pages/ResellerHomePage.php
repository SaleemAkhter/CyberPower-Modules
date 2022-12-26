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


class ResellerHomePage extends BaseContainer implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,WhmcsParams;

    protected $name = 'resellerHomePage';
    protected $id = 'resellerHomePage';
    protected $title = 'resellerHomePage';
    const ACCOUNT_SECTION               = 'accountSection';
    const ADVANCED_FEATURES_SECTION     = 'advancedFeaturesSection';

    const USER_MANAGER = 'manage_users';
    const PACKAGE_MANAGER = 'manage_packages';
    const IP_MANAGER ='manage_ips';
    const CHANGE_PASSWORDS ='change_passwords';
    const NAMESERVERS ='nameservers';

    protected $accountSection           = [
        FeaturesSettingsConstants::USER_MANAGER     => 'Users',
        FeaturesSettingsConstants::PACKAGE_MANAGER     => 'Packages',
        FeaturesSettingsConstants::IP_MANAGER     => 'IpManagement',
        FeaturesSettingsConstants::CHANGE_PASSWORDS     => 'ChangePasswords',
        FeaturesSettingsConstants::NAMESERVERS     => 'Nameservers',
    ];
    protected $sectionHeaders           = [
        self::ACCOUNT_SECTION               => 'accountManagement',
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
