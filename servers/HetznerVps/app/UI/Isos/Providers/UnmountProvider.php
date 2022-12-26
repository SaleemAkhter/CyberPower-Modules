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
class UnmountProvider extends BaseDataProvider implements ClientArea, AdminArea
{

    public function read()
    {
        $isoManager = new IsoManager($this->getWhmcsParams());
        $iso = $isoManager->getIsoMounted();
        if(is_null($iso)){
            return;
        }
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

    public function unmount()
    {
        try
        {
            $isoManager = new IsoManager($this->getWhmcsParams());
            $isoManager ->unmount();
            sl("lang")->addReplacementConstant("description", $this->formData['description']);
            return (new HtmlDataJsonResponse())
                ->setStatusSuccess()
                ->setMessageAndTranslate('unmountIso')
                ->setData(['unmountButton' => false])
                ->setCallBackFunction('hrToggleUnmountButton');
        }
        catch (\Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

}
