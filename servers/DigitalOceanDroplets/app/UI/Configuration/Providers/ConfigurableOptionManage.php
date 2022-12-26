<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Providers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Service\ConfigurableOptions\ConfigurableOptions;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Helpers\ConfigurableOptionsBuilder;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ConfigurableOptionManage extends BaseDataProvider implements AdminArea
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
        catch (Exception $ex)
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
