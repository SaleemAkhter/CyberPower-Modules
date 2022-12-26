<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Providers;

use Exception;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\UI\Client\Snapshots\Helpers\SnapshotManager;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Lang\Lang;

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
            
            $snapshotName = $this->formData['snapshotName'];
            
            if(empty($snapshotName)){
                return (new HtmlDataJsonResponse())->setStatusError()->setMessage(Lang::getInstance()->T('snapshotNameEmpty'));
            }
            if($this->checkIfNameMatchRegex($snapshotName) != $snapshotName){
                return (new HtmlDataJsonResponse())->setStatusError()->setMessage(Lang::getInstance()->T('snapshotNotMatchRegex'));
            }
            $snapshotManager = new SnapshotManager();
            $snapshotManager->createSnapshot($snapshotName);
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessageAndTranslate(Lang::getInstance()->T('creatingSnapshotStart'));
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }
    
    private function checkIfNameMatchRegex($name){
        $regex = '/(?:[a-z](?:[-a-z0-9]{0,61}[a-z0-9])?)/';
        preg_match($regex, $name, $match);
        return $match[0];
    }

    public function delete()
    {
        try
        {
            
            $multipleDelete = $this->getRequestValue('massActions', []);
            if(!empty($multipleDelete))
            {
               
                foreach($multipleDelete as $snapshotName)
                {
                    $this->deleteOneSnapshot($snapshotName);
                }

                return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessage(Lang::getInstance()->T('massDeleteSnapshot'));
            }
            else
            {
                $this->deleteOneSnapshot($this->formData['id']);

                return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessage(Lang::getInstance()->T('deleteSnapshot'));
            }  
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }
    
    private function deleteOneSnapshot($snapshotName){
        $snapshotManager = new SnapshotManager();
        $snapshotManager->deleteSnapshot($snapshotName);
        
        return true;
    }

    public function update()
    {
        try
        {
            $snapshotManager = new SnapshotManager();
            $snapshotManager->restoreSnapshot($this->formData['id']);
            return (new HtmlDataJsonResponse())->setStatusSuccess()->setMessage(Lang::getInstance()->T('restoreFromSnapshotStart'));
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

}
