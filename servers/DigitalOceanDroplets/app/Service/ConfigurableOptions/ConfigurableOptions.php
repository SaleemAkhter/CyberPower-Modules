<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Service\ConfigurableOptions;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Service\ConfigurableOptions\Models\Option;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Product;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\ProductConfigGroups;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\ProductConfigLinks;

/**
 * Description of ConfigurableOptions
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ConfigurableOptions extends Abstracts\AbstractConfigurableOptions
{
    /*
     * Create New Configurable Optoions group
     * 
     * @throw \Exception
     */

    public function create()
    {
        if ($this->checkExistAssignedOptionsGroup())
        {
            throw new Exception('Configurable options already exist.');
        }
        $this->addGroup();
        $this->buildOptions();
    }

    /*
     * Save additional fields 
     * 
     * 
     * Return true, if mmethod create a new configurable group;
     * 
     * @return boolean $group;
     * 
     */

    public function createOrUpdate()
    {
        $group = $this->addGroup();
        $this->buildOptions();
        return $group;
    }

    public function show()
    {
        return $this->showOptions();
    }

    /*
     *  Add Option to group 
     * 
     * @param \ModulesGarden\Servers\DigitalOceanDroplets\App\Service\ConfigurableOptions\Models\Option $option
     */

    public function addOption(Option $option)
    {
        $this->options[] = $option;

        return $this;
    }

}
