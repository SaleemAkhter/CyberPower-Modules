<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Providers;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\ConfigurableOptions;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\ConfigurableOptionsBuilder;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfigurableOptionManage extends BaseDataProvider implements AdminArea
{

    public function read()
    {
        $configurableOption = new ConfigurableOptions($this->getRequestValue('id', 0));
        ConfigurableOptionsBuilder::buildAll($configurableOption, true);
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
