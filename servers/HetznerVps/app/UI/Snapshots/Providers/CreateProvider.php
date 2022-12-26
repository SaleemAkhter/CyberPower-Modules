<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Providers;

use ModulesGarden\Servers\HetznerVps\App\UI\Snapshots\Helpers\SnapshotManager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CreateProvider extends BaseDataProvider implements ClientArea, AdminArea
{

    public function read()
    {

    }

    public function create()
    {
        try
        {
            $manager = new SnapshotManager($this->getWhmcsParams());
            $manager->sizeLimit();
            $manager->create($this->formData['description']);
            return (new HtmlDataJsonResponse())
                ->setStatusSuccess()
                ->setMessageAndTranslate('createSnapshot')
                ->setData(['createButton' => $manager->isSizeLimit()])
                ->setCallBackFunction('hrToggleCreateButton');
        }
        catch (\Exception $ex)
        {
            $msg = $ex->getMessage();
            if(preg_match('/Locked/',$msg)){
                $msg = "errorSnapshotIsCreating";
            }
            return (new HtmlDataJsonResponse())
                ->setStatusError()->setMessageAndTranslate($msg)
                ->setData(['createButton' => $manager->isSizeLimit()])
                ->setCallBackFunction('hrToggleCreateButton');
        }
    }

    public function delete()
    {
        
    }

    public  function update()
    {

    }

}
