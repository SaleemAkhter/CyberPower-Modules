<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Providers;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\UI\Rebuild\Helpers\ImageManager;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class Rebuild extends BaseDataProvider implements ClientArea, AdminArea
{

    public function read()
    {
        $this->data['id'] = $this->actionElementId;
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
            $imageManager = new ImageManager($this->getWhmcsParams());

            $password = $imageManager->rebuildMachine($this->formData['id']);

            $response = new HtmlDataJsonResponse();
            $response ->setStatusSuccess()
                ->setMessageAndTranslate('rebuildFromImageStart')
                ->setCallBackFunction('setVMPassword');
            if($password){
                 $response ->addData('password', $password);
            }else{
                $response ->addData('sshKeyInfo', true);
            }
            return $response;



        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

}
