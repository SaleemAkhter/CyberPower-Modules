<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\ServiceManager;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Repository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class PowerOn extends BaseDataProvider implements ClientArea, AdminArea
{
    use WhmcsParamsApp;
    use Lang;

    public function read()
    {
        
    }

    public function create()
    {
        
    }

    public function delete()
    {
        
    }

    public function update()
    {
        try
        {
            $repo = new Repository($this->getWhmcsEssentialParams());
            $vps = $repo->get();
            $vps->start();
            return (new HtmlDataJsonResponse())
                            ->setStatusSuccess()
                            ->setMessageAndTranslate('powerOnStarted')
                            ->addData('refreshState', 'serverinformationTable')
                            ->setCallBackFunction('refreshTable');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

}
