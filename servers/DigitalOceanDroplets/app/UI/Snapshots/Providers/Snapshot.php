<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Providers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Helpers\SnapshotManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Snapshot extends BaseDataProvider implements ClientArea, AdminArea
{

    public function read()
    {
        $this->data['id'] = $this->actionElementId;
    }

    public function create()
    {
        try
        {
            $snapshotManager = new SnapshotManager($this->whmcsParams);
            $snapshotManager->createSnapshot(html_entity_decode($this->formData['snapshotName'], ENT_QUOTES));
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('creatingSnapshotStart');
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
            
            $multipleDelete = $this->getRequestValue('massActions', []);
            if(!empty($multipleDelete))
            {
               
                foreach($multipleDelete as $snapshotID)
                {
                    $this->deleteOneSnapshot($snapshotID);
                }

                return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('massDeleteSnapshot');
            }
            else
            {
                $this->deleteOneSnapshot($this->formData['id']);

                return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('massDeleteSnapshot');
            }  
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }
    
    private function deleteOneSnapshot($snapshotID){
        $snapshotManager = new SnapshotManager($this->whmcsParams);
        $snapshotManager->deleteSnapshot($snapshotID);
        
        return true;
    }

    public function update()
    {

        try
        {
            $snapshotManager = new SnapshotManager($this->whmcsParams);
            $snapshotManager->restoreFromSnapshot($this->formData['id']);
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate('restoreFromSnapshotStart');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

}
