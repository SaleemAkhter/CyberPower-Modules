<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers;

use Exception;
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
class Rescue extends BaseDataProvider implements ClientArea, AdminArea
{
    use WhmcsParamsApp;
    use Lang;

    public function __construct()
    {
        parent::__construct();
        $this->loadLang();
    }

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

    }

    public function rescue()
    {
        try
        {
            $repo = new Repository($this->getWhmcsEssentialParams());
            $vps = $repo->get();
            $vps->rescue();
            return (new HtmlDataJsonResponse())
                ->setStatusSuccess()
                ->setMessage($this->lang->absoluteTranslate('machine', 'mode', 'rescue', 'started'))
                ->addData('refreshState', 'serverinformationTable')
                ->setCallBackFunction('refreshTable');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function unrescue()
    {
        try
        {
            $repo = new Repository($this->getWhmcsEssentialParams());
            $vps = $repo->get();
            $vps->unrescue();
            return (new HtmlDataJsonResponse())
                ->setStatusSuccess()
                ->setMessage($this->lang->absoluteTranslate('machine', 'mode', 'unrescue', 'started'))
                ->addData('refreshState', 'serverinformationTable')
                ->setCallBackFunction('refreshTable');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

}
