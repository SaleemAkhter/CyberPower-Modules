<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Base\BaseControlButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\HandlerError\Exceptions\Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\BuildUrl;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use const DS;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Repository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates;


/**
 * Description of Reboot
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConsoleBaseModalButton extends BaseControlButton implements AdminArea, ClientArea
{
    use WhmcsParamsApp;

    protected $id               = 'consoleButton';
    protected $name             = 'consoleButton';
    protected $icon             = 'console';
    protected $title            = 'consoleButton';
    protected $htmlAttributes = [
        'data-toggle' => 'lu-tooltip',
    ];

    public function getImage()
    {
        return BuildUrl::getAppAssetsURL() . DS . 'img' . DS . 'servers' . DS . $this->icon . '.png';
    }

    public function returnAjaxData()
    {
        try
        {
            $manager = (new Repository($this->getWhmcsEssentialParams()))->get();
            $url = $manager->getConsoleUrl();
            return (new ResponseTemplates\RawDataJsonResponse(['data' => ['url' => $url]]))->setCallBackFunction('redirectToUrlCustom');
        }
        catch (\Exception $exception)
        {
            return (new ResponseTemplates\RawDataJsonResponse())->setMessage($exception->getMessage())->setStatusError();
        }
    }

}
