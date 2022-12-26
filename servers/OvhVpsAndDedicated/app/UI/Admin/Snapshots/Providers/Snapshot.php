<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Snapshots\Providers;

use Exception;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Snapshot\Repository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Repository as VpsRepository;

/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Snapshot extends BaseDataProvider implements ClientArea, AdminArea
{
    private $snapshot = null;
    private $vps = null;
    use WhmcsParamsApp;

    use Lang;

    public function __construct()
    {
        parent::__construct();

        $this->loadLang();
        $this->vps      = new VpsRepository($this->getWhmcsEssentialParams());
        $this->snapshot = new Repository(false, $this->getWhmcsEssentialParams());

    }

    public function read()
    {

        $this->loadFormDataFromRequest();
        $this->data['id'] = $this->actionElementId;

        if ($this->getRequestValue('index') == 'editButton')
        {
            $this->data += $this->snapshot->get()->model()->toArray();
        }
    }

    public function create()
    {
        try
        {
            $this->vps->get()->createSnapshot($this->formData);
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('creatingSnapshot');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function delete()
    {
        try
        {
            $this->snapshot->get()->remove();
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('deleteSnapshot');
        }
        catch (Exception $ex)
        {
            if($ex->getMessage()=="Conflict, action impossible"){
                return (new HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate("Snapshot is creating");
            }
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function update()
    {
        try
        {
            $this->snapshot->get()->update($this->formData);
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('updateSnapshot');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function restore()
    {
        try
        {
            $this->snapshot->get()->revert();
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('restoreSnapshot');
        }
        catch (Exception $ex)
        {
            if($ex->getMessage()=="Conflict, action impossible"){
                return (new HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate("Snapshot is creating");
            }
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

}
