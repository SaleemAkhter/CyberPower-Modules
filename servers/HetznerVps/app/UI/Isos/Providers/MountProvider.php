<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Isos\Providers;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\ServiceManager;
use ModulesGarden\Servers\HetznerVps\App\UI\Isos\Helpers\IsoManager;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class MountProvider extends BaseDataProvider implements ClientArea, AdminArea
{

    public function read()
    {
        if(!$this->actionElementId){
            return;
        }
        $this->data['id'] = $this->actionElementId;
        $isoManager = new IsoManager($this->getWhmcsParams());
        $iso = $isoManager->read(   $this->data['id']);
        $this->data['description'] = sl("lang")->absoluteT( 'iso',    $iso->description );
    }

    public function create()
    {
        
    }

    public function delete()
    {
        
    }

    public  function update()
    {

    }

    public function mount()
    {
        try
        {
            $isoManager = new IsoManager($this->getWhmcsParams());
            $isoManager ->attachIso($this->formData['id']);
            sl("lang")->addReplacementConstant("description", $this->formData['description']);
            return (new HtmlDataJsonResponse())
                ->setStatusSuccess()
                ->setMessageAndTranslate('mountIso')
                ->setData(['unmountButton' => true])
                ->setCallBackFunction('hrToggleUnmountButton');
        }
        catch (\Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

}
