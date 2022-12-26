<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Providers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\ServiceManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class BackupsDisable extends BaseDataProvider implements AdminArea
{

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
            $vmHelper = new ServiceManager($this->whmcsParams);
            $vmHelper->disableBackups();
            return (new HtmlDataJsonResponse())
                            ->setStatusSuccess()
                            ->setMessageAndTranslate('backupDisable')
                            ->addData('refreshState', ['serverinformationTable', 'adminPanelAdditinalOpt'])
                            ->setCallBackFunction('refreshTable');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

}
