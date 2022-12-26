<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Abstracts\ConfigBase;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\ServiceLocator;

/**
 * Description of Config
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Config extends ConfigBase
{
    public function setBasics()
    {
        $this->productInfo = $this->api->dedicated->installationTemplate()->allWithKey();
    }

    public function getLanguages($installationTemplate = false)
    {
        if($installationTemplate === false)
        {
            $installationTemplate = reset($this->productInfo);
        }

        return  $this->api->dedicated->installationTemplate()->one($installationTemplate)->model()->getAvailableLanguages(true);
    }

    public function getSystemTemplates()
    {
        return Data::prepareTemplates($this->productInfo);
    }

}
