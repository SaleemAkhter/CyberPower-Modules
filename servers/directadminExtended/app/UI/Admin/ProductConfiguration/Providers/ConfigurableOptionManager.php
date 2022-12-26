<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Providers;


use ModulesGarden\Servers\DirectAdminExtended\App\Services\ConfigurableOptions\ConfigurableOptions;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Helper\ConfigurableOptionsBuilder;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ConfigurableOptionManager extends BaseDataProvider implements AdminArea
{

    public function read()
    {
        $configurableOption = new ConfigurableOptions($this->getRequestValue('id', 0));
        ConfigurableOptionsBuilder::buildAll($configurableOption);
        $this->data         = [
            'fields' => $configurableOption->show()
        ];
    }

    public function create()
    {
        try
        {
            $configurableOption = new ConfigurableOptions($this->getRequestValue('id', 0));
            ConfigurableOptionsBuilder::build($configurableOption, $this->formData);
            $status             = $configurableOption->createOrUpdate();

            $msg                = ($status) ? 'configurableOptionsCreate' : 'configurableOptionsUpdate';
            return (new HtmlDataJsonResponse())
                            ->setStatusSuccess()
                            ->setCallBackFunction('redirectToConfigurableOptionsTab')
                            ->setMessageAndTranslate($msg);
        }
        catch (\Exception $ex)
        {
            return (new HtmlDataJsonResponse())
                            ->setStatusError()
                            ->setMessage($ex->getMessage());
        }
    }

    public function delete()
    {
        
    }

    public function update()
    {
        
    }

}
