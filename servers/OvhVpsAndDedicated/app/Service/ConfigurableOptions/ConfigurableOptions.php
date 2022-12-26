<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Models\Option;

/**
 * Description of ConfigurableOptions
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfigurableOptions extends Abstracts\AbstractConfigurableOptions
{
    /**
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

    /**
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

    /**
     * Show Options
     *
     * @return array
     */
    public function show()
    {
        return $this->showOptions();
    }

    public function showForPage()
    {
        $options =  $this->showOptions();


        $rest = (count($options) % $this->dividerForPage);

        if($rest == 0)
        {
            return $options;
        }

        //initializeEmptyFieldsForPage
        for ($i = 0; $i <  $this->dividerForPage - $rest; $i++)
        {
            $options['initForPageOnly'. $i] = null;
        }

        return $options;
    }

    /**
     *  Add Option to group 
     * 
     * @param \ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Models\Option $option
     *
     * @return $this
     */
    public function addOption(Option $option)
    {
        $this->options[] = $option;

        return $this;
    }

}
