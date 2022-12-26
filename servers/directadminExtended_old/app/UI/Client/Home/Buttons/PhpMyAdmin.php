<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\SSO;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;

class PhpMyAdmin extends ButtonRedirect implements ClientArea
{
    protected $id    = 'phpMyAdmin';
    protected $name  = 'phpMyAdmin';
    protected $title = 'phpmyadmin';

    protected $icon = 'phpmyadmin';

    protected $mailClient;

    public function initContent()
    {
        $sso =  new SSO(sl('request')->get('id'));
        $this->setRawUrl($sso->getLocalLink('phpmyadmin'));

        parent::initContent();
    }

    public function getImage()
    {
        return BuildUrl::getAppAssetsURL() . "/img/directadmin/icon-{$this->icon}.png";
    }

    public function getRawUrl(){
        return $this->rawUrl;
    }
}

