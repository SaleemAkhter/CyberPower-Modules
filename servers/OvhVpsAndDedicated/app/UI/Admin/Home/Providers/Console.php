<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Repository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
/**
 * Class Console
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Console extends BaseDataProvider implements AdminArea
{
    use WhmcsParamsApp;

    public function open()
    {
        try
        {
            $manager = (new Repository($this->getAppWhmcsParams(WhmcsParams::getEssentialsKeys())))->get();
        
            $url = $manager->getConsoleUrl();

            return (new HtmlDataJsonResponse())
                ->setStatusSuccess()
                ->setMessageAndTranslate('consoleOpen')
                ->addData('url', $url)
                ->setCallBackFunction('redirectToUrl');
        }
        catch (\Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function read()
    {

    }

    public function update()
    {

    }


}