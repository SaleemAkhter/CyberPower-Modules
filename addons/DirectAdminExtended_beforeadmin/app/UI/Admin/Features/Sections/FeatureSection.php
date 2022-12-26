<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Sections;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BaseSection;


class FeatureSection extends BaseSection
{
    protected $id   = 'featureSection';
    protected $name = 'featureSection';
    protected $tooltip = null;

    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;
        
        return $this;
    }
    
    public function getTooltip()
    {
        return $this->tooltip;
    }


}
