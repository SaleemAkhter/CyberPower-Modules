<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Sections;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\BaseSection;

/**
 * Base Form Section controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class RowSection extends BaseSection
{
    protected $id   = 'rowSection';
    protected $name = 'rowSection';
    protected $additionalClass=[];
    public function getAdditionalClass()
    {
        return implode(" ",$this->additionalClass);
    }
    public function setAdditionalClass($class):string
    {
        array_push($this->additionalClass,$class);
        $this;
    }
}
