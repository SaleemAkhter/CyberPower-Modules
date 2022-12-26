<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Home\Providers;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\ServiceManager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class PasswordReset extends BaseDataProvider implements ClientArea, AdminArea
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
            $vmHelper = new ServiceManager($this->getWhmcsParams());
            $vmHelper->passwordReset();
            return (new HtmlDataJsonResponse())
                            ->setStatusSuccess()
                            ->setMessageAndTranslate('passwordResetStarted')
                            ->addData('refreshState', 'serverinformationTable')
                            ->setCallBackFunction('refreshTable');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

}
