<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Providers;

use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Helpers\SnapshotManager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class SnapshotProvider extends BaseDataProvider implements ClientArea, AdminArea
{

    public function read()
    {
        if(!$this->actionElementId){
            return;
        }
        $this->data['id'] = (int) $this->actionElementId;
        if ($this->data['id'] != 0) {
            $manager = new SnapshotManager($this->getWhmcsParams());
            $entity =  $manager->read($this->data['id']);
            $this->data['description'] =  $entity->description ;
        }
    }

    public function create()
    {
        
    }

    public function delete()
    {
        try
        {
            $manager = new SnapshotManager($this->getWhmcsParams());
            $manager->read($this->formData['id']);
            $manager->delete($this->formData['id']);
            sl("lang")->addReplacementConstant("description", $this->formData['description']);
            return (new HtmlDataJsonResponse())
                ->setStatusSuccess()
                ->setMessageAndTranslate('deleteSnapshot')
                ->setData(['createButton' => $manager->isSizeLimit()])
                ->setCallBackFunction('hrToggleCreateButton');
        }
        catch (\Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }

    }

    public function deleteMass()
    {
        try
        {
            $manager = new SnapshotManager($this->getWhmcsParams());
            foreach ($this->getRequestValue('massActions') as $id)
            {
                $manager->read($id);
                $manager->delete($id);
            }
            return (new HtmlDataJsonResponse())
                ->setStatusSuccess()
                ->setMessageAndTranslate('deleteMassSnapshot')
                ->setData(['createButton' => $manager->isSizeLimit()])
                ->setCallBackFunction('hrToggleCreateButton');
        }
        catch (\Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }

    }

    public  function update()
    {
        try{
            $manager = new SnapshotManager($this->getWhmcsParams());
            $entity = $manager->read($this->formData['id']);
            $entity->update([
                'description' => $this->formData['description']
            ]);
            sl("lang")->addReplacementConstant("description", $this->formData['description']);
            return (new HtmlDataJsonResponse())
                ->setStatusSuccess()
                ->setMessageAndTranslate('updateSnapshot');
        }
        catch (\Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    public function restore()
    {
        try
        {
            $manager = new SnapshotManager($this->getWhmcsParams());
            $manager->restore($this->formData['id']);
            sl("lang")->addReplacementConstant("description", $this->formData['description']);
            return (new HtmlDataJsonResponse())
                ->setStatusSuccess()
                ->setMessageAndTranslate('restoreSnapshot')
                ->setData(['createButton' => true])
                ->setCallBackFunction('hrToggleCreateButton');
        }
        catch (\Exception $ex)
        {
            $msg = $ex->getMessage();
            if(preg_match('/Locked/',$msg))
            {
                return (new HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate('errorSnapshotIsRestoring');
            }
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($msg);
        }
    }
}
