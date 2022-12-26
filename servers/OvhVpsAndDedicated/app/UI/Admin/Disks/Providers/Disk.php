<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Disks\Providers;

use Exception;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Disk\Repository;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Disks\Disks as DisksManager;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Disks\Disks;
/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Disk extends BaseDataProvider implements ClientArea, AdminArea
{

    private $manager;

    public function __construct()
    {
        parent::__construct();
        $this->manager = new Repository(false, $this->getWhmcsParams());
    }

    public function read()
    {
        $this->loadFormDataFromRequest();
        $actionIdValue = $this->getActionElementIdValue();
        if($actionIdValue)
        {
            $disk = $this->manager->get($this->actionElementId)->model()->toArray();
            $disk['monitoring'] = $disk['monitoring'] ? 'on' : 'off';
            $this->data += $disk;
        }
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
            $id = $this->formData['id'];
            unset($this->formData['id']);
            $this->manager->get($this->actionElementId)->update($this->formData);

            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('restoreFromBackupStart');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function getDisks()
    {
        $dataManger = new Repository(false, $this->getWhmcsParams());

        $data = $dataManger->getAllToArray();

        return Disks::formatToDatatable($data);
    }

}
