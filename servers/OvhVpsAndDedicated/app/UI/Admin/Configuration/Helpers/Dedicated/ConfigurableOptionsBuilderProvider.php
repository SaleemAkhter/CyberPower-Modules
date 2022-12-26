<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Helper\TypeConstans;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Models\Option;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Service\ConfigurableOptions\Models\SubOption;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Configuration\Helpers\Abstracts\ConfigurableOptionsBuilderProviderBase;

/**
 * Description of Config
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ConfigurableOptionsBuilderProvider  extends ConfigurableOptionsBuilderProviderBase
{
    /**
     * @var Config
     */
    protected $config;

    public function __construct($productId = null)
    {
        parent::__construct($productId);

        $this->config = new Config($this->productId);
    }

    public function dedicatedSystemTemplate()
    {
        $templates = $this->config->getSystemTemplates();
        $option   = new Option('dedicatedSystemTemplate', 'System Template', TypeConstans::DROPDOWN);

        foreach ($templates as $key => $name)
        {
            $option->addSubOption(new SubOption($key, $name));
        }

        return $option;
    }

    public function dedicatedLanguage()
    {
        $languages = $this->config->getLanguages();
        $option   = new Option('dedicatedLanguage', 'Language', TypeConstans::DROPDOWN);
        foreach ($languages as $key => $name)
        {
            $option->addSubOption(new SubOption($key, $name));
        }

        return $option;

    }
}
