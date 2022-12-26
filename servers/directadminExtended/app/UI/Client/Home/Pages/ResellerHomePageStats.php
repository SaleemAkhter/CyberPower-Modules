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


class ResellerHomePageStats extends BaseContainer implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,WhmcsParams;

    protected $name = 'resellerHomePageStats';
    protected $id = 'resellerHomePageStats';
    protected $title = 'resellerHomePageStats';

    public function initContent()
    {
        $this->addSection($this->getUsageGraphSection())
                ->addSection($this->getAccountDetailsSection());
    }
    public function getUsageGraphSection()
    {

    }
    public function getAccountDetailsSection()
    {
        return AccountDetails::class;
    }
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

}
