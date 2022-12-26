<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Client\Home\Buttons\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Base\BaseControlButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\BuildUrl;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use const DS;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Repository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates;

/**
 * Class Disk
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class IPs extends BaseControlButton implements AdminArea, ClientArea
{
    use WhmcsParamsApp;

    protected $id               = 'ipsManagementButton';
    protected $name             = 'ipsManagementButton';
    protected $icon             = 'networkButton';
    protected $title            = 'ipsManagementButton';
    protected $htmlAttributes = [
        'data-toggle' => 'lu-tooltip',
    ];

    protected $page = 'ips';

    public function initContent()
    {
        $id = $this->getRequestValue('id');
        $this->htmlAttributes['href'] = "clientarea.php?action=productdetails&id={$id}&modop=custom&a=management&mg-page=" . $this->page;
    }


    public function getImage()
    {
        return BuildUrl::getAppAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->icon . '.png';
    }
}
