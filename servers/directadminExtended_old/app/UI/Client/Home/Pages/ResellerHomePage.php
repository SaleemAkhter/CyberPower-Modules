<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\DirectAdmin;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\PhpMyAdmin;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\WebMail;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons\SitePad;
class ResellerHomePage extends BaseContainer implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;

   protected $name = 'resellerHomePage';
   protected $id = 'resellerHomePage';
   protected $title = 'resellerHomePage';


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
