<?php

namespace ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\UI\ConfigurableOption\Providers;

use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\AwsEc2\Packages\WhmcsService\Product;

class Options extends BaseDataProvider
{
    public function read()
    {
        $this->data['optionName'] = $this->getRequestValue('actionElementId');
    }

    public function create()
    {
        $optionsName = $this->formData['configOptions'];
        $product = new Product($this->getRequestValue('id'));
        foreach ($optionsName as $optionName => $isOn)
        {
            if ($isOn !== 'on')
            {
                continue;
            }

            $product->addConfigurableOption($optionName);
        }

        $response = new HtmlDataJsonResponse();
        $response->setCallBackFunction('redirectToConfigurableOptions');

        if ($product->isConfigurableOptionsGroupCreated())
        {
            return $response->setMessageAndTranslate('configurableOptionsCreated');
        }

        return $response->setMessageAndTranslate('configurableOptionsUpdated');
    }

    public function update()
    {

    }
}
