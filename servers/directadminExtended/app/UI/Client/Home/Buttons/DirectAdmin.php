<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\SSO;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;

class DirectAdmin extends ButtonRedirect implements ClientArea
{
    use WhmcsParams, RequestObjectHandler;
    protected $id    = 'directAdmin';
    protected $name  = 'directAdmin';
    protected $title = 'DirectAdmin';

    protected $icon = 'DirectAdmin';

    protected $mailClient;

    public function initContent()
    {
        $this->initWhmcsParams();
        $sso =  new SSO($this->getRequestValue('id'));
        $this->setRawUrl($sso->getLocalLink('directAdmin'));

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
