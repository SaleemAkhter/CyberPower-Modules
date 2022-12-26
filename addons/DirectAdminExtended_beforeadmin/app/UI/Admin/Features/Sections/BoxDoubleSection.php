<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Sections;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BaseSection;

class BoxDoubleSection extends BaseSection
{
    protected $id      = 'boxDoubleSection';
    protected $name    = 'boxDoubleSection';
    protected $isEqual = false;
    protected $hidden  = false;

    public function sectionEquals($param = false)
    {
        $this->isEqual = $param;
        return $this;
    }

    public function getEquals()
    {
        return $this->isEqual;
    }

    public function setHidden($hidden = true)
    {
        $this->hidden = $hidden;
        return $this;
    }

    public function getHidden()
    {
        return $this->hidden;
    }
}
