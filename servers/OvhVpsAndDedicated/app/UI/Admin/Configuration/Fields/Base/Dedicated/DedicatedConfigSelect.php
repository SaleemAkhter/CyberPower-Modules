<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\ConfigSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Dedicated\Config;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;

/**
 * Class DedicatedConfigSelect
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class DedicatedConfigSelect extends ConfigSelect implements AdminArea
{
    protected $optionPrefix = "dedicated";

    /**
     * @var Config
     */
    protected $config;


    public function __construct($baseId = null)
    {
        $this->config = new Config($this->getRequestValue('id'));

        parent::__construct($baseId);
    }
}