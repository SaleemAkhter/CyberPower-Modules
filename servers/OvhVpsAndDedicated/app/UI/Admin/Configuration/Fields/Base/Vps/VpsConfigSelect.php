<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Fields\Base\ConfigSelect;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Config;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Fields;

/**
 * Class DedicatedConfigSelect
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class VpsConfigSelect extends ConfigSelect implements AdminArea
{
    protected $optionPrefix = "vps";

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