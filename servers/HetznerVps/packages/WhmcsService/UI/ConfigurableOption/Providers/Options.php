<?php

namespace ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\UI\ConfigurableOption\Providers;

use ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api;
use ModulesGarden\Servers\HetznerVps\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\HetznerVps\Packages\WhmcsService\Product;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

class Options extends BaseDataProvider
{
    public function read()
    {
        $service = new \ModulesGarden\Servers\HetznerVps\App\Config\Packages\WhmcsService();
        $this->data['optionName'] = $service->getConfigurableOptions();
    }

    public function create()
    {
        $this->api = new Api(sl('whmcsParams')->getWhmcsParams());
        $optionsName = $this->formData['configOptions'];
        $product = new Product($this->getRequestValue('id'));
        foreach ($optionsName as $optionName => $isOn) {
            if ($isOn !== 'on') {
                continue;
            }

            $product->addConfigurableOption($optionName);
        }

        $response = new HtmlDataJsonResponse();
        $response->setCallBackFunction('redirectToConfigurableOptions');

        if ($product->isConfigurableOptionsGroupCreated()) {
            return $response->setMessageAndTranslate('configurableOptionsCreated');
        }

        return $response->setMessageAndTranslate('configurableOptionsUpdated');
    }

    public function update()
    {

    }
}