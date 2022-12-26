<?php

namespace ModulesGarden\Servers\AwsEc2\App\Http\Actions;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Pages\ConfigForm;
use ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Instances\AddonController;

/**
 * Class ConfigOptions
 *
 * @author <slawomir@modulesgarden.com>
 */
class ConfigOptions extends AddonController
{
    public function execute($params = null)
    {
        try
        {
            $productId = $this->getRequestValue('id');

            //test connection
            $api = new ClientWrapper($this->getRequestValue('id'));
            $api->getRegions();

            if (($this->getRequestValue('action') === 'module-settings' || ($this->getRequestValue('loadData') && $this->getRequestValue('ajax') == '1')))
            {
                return [\ModulesGarden\Servers\AwsEc2\App\Http\Admin\ProductConfig::class, 'index'];
            }
            else if ($this->getRequestValue('action') === 'save')
            {
                $form = new ConfigForm();
                $form->runInitContentProcess();
                $form->returnAjaxData();
            }
        }
        catch (\Aws\Exception\AwsException $exc)
        {
            throw new \Exception($exc->getAwsErrorMessage());
        }
    }
}
