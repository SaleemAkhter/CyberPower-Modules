<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Fields;

use ModulesGarden\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\AjaxFields\Select;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\DirectAdminExtended\App\Models\ProductConfigGroupOpt;
use ModulesGarden\DirectAdminExtended\App\Models\FunctionsSettings;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ServerParams;
use ModulesGarden\DirectAdminExtended\App\Services\InstallScriptsService;

/**
 * Select field controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class RenderSelect extends Select implements AdminArea
{
    use Lang;

    protected $id   = 'appsConfigOpt';
    protected $name = 'appsConfigOpt';

    public function prepareAjaxData()
    {
        $this->loadRequestObj();
        if ($this->getRequestValue('apps_installer_type')== 1)
        {
            $installer = 'Softaculous';
        }
        else
        {
            $installer = 'Installatron';
        }
        try
        {
            if ($this->getRequestValue('apps_order_assign') == 'off')
            {
                $data = $this->getInstallScripts($installer);
            }
            else
            {
                $data = $this->getConfigurableGroups();
            }
        } 
        catch (\Exception $ex) 
        {
            \logModuleCall('DirectAdminExtended','getInstallationScripts','Get Installation Scripts - ' . $installer,$ex->getMessage());
            return (new ResponseTemplates\HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }


        $availableData = [];
        /* parse available apps */
        foreach ($data as $key => $value)
        {
            $availableData[] = [
                'key'   => $key,
                'value' => $value
            ];
        }
        /* sort apps */
        asort($availableData);

        /* set app list */
        $this->setAvailableValues($availableData);

        /* load selected app */
        if (FunctionsSettings::where('product_id', '=', $this->getRequestValue('pid'))->first())
        {
            $functionSettings = FunctionsSettings::where('product_id', '=', $this->getRequestValue('pid'))->first();
            if ($functionSettings->apps_app_name)
            {
                /* set selected app */
                $this->setSelectedValue($functionSettings->apps_app_name);
            }
        }


     //parent::returnAjaxData();

       // return (new ResponseTemplates\RawDataJsonResponse($returnData));
    }
    
    protected function getInstallScripts($installer)
    {
        $this->loadLang();

        $data           = [];
        $serverParams   = ServerParams::getServerParamsByProductId($this->getRequestValue('pid'));
        $result         = InstallScriptsService::init($serverParams)->setInstaller($installer)->getScripts();
        foreach($result as $script)
        {
            $data[$script['name']] = $script['name'];
        }
        asort($data);
        array_unshift($data, $this->lang->absoluteTranslate('selectApplication'));

        return $data;
    }
    
    protected function getConfigurableGroups()
    {
        $data  = [];
        $CO    = ProductConfigGroupOpt::all();
        foreach ($CO as $each)
        {
            $data[$each->name] = $each->name;
        }
        
        return $data;
    }
}
