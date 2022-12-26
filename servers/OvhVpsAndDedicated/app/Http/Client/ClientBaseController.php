<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Http\Client;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\PageController;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Http\AbstractController;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server;

/**
 * Class ClientBaseController
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ClientBaseController extends AbstractController
{
    use WhmcsParamsApp;
    /**
     * @var PageController
     */
    private $pageController;

    protected function redirectToMainServicePage()
    {
        return \ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\redirectByUrl('clientarea.php', [
            'action' => 'productdetails',
            'id'     => $this->getRequestValue('id'),
        ]);
    }


    protected function getPageController()
    {
        if(!$this->pageController)
        {
            return new PageController($this->getWhmcsEssentialParams());
        }
        return $this->pageController;
    }

    protected function getServerType()
    {
        return Server\Details::getOvhServerTypeByServiceId($this->getRequestValue('id'));
    }
}