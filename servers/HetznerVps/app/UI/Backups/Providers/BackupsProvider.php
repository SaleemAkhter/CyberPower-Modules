<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Backups\Providers;

use LKDev\HetznerCloud\APIException;
use LKDev\HetznerCloud\HetznerAPIClient;
use ModulesGarden\Servers\HetznerVps\App\UI\Backups\Helpers\BackupsManager;
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
class BackupsProvider extends BaseDataProvider implements ClientArea, AdminArea
{

    public function read()
    {
        if(!$this->actionElementId){
            return;
        }
        $this->data['id'] = $this->actionElementId;
        $manager = new BackupsManager($this->getWhmcsParams());
        $entity = $manager->read($this->data['id']);
        $this->data['description'] =  $entity->description;
    }

    public function create()
    {
        
    }

    public function delete()
    {

    }

    public function deleteMass()
    {

    }

    public  function update()
    {

    }

    public function restore()
    {
        try
        {
            $manager = new BackupsManager($this->getWhmcsParams());
            $entity = $manager->read($this->formData['id']);
            if($entity->status !== 'available' && $entity->status !== 'Available')
                return (new HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate('errorBackupIsCreating');
            $manager->restore($this->formData['id']);
            sl("lang")->addReplacementConstant("description", $this->formData['description']);
            return (new HtmlDataJsonResponse())
                ->setStatusSuccess()
                ->setMessageAndTranslate('restoreBackup')
                ->setData(['createButton' => true])
                ->setCallBackFunction('hrToggleCreateButton');
        }
        catch (\Exception $ex)
        {
            $msg = $ex->getMessage();
            if(preg_match('/Locked/',$msg))
            {
                return (new HtmlDataJsonResponse())->setStatusError()->setMessageAndTranslate('errorBackupIsRestoring');
            }
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($msg);
        }
    }



}
