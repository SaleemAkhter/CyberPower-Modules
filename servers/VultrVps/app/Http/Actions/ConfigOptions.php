<?php

namespace ModulesGarden\Servers\VultrVps\App\Http\Actions;

use ModulesGarden\Servers\VultrVps\App\UI\Admin\ProductConfig\Pages\ConfigForm;
use ModulesGarden\Servers\VultrVps\Core\App\Controllers\Instances\AddonController;

/**
 * Class ConfigOptions
 *
 * @author <slawomir@modulesgarden.com>
 */
class ConfigOptions extends AddonController
{
    public function execute($params = null)
    {
        if (($this->getRequestValue('action') === 'module-settings' || ($this->getRequestValue('loadData') && $this->getRequestValue('ajax') == '1')))
        {
            return [\ModulesGarden\Servers\VultrVps\App\Http\Admin\ProductConfig::class, 'index'];
        }
        else
        {
            if ($this->getRequestValue('action') === 'save')
            {
                $form = new ConfigForm();
                $form->runInitContentProcess();
                $form->returnAjaxData();
            }
        }
    }
}
