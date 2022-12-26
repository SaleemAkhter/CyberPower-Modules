<?php

namespace ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms\Sections;

use ModulesGarden\Servers\HetznerVps\Core\UI\Traits\Toggler;

/**
 * Base Form Section controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BoxSection extends BaseSection
{
    use Toggler;

    protected $id   = 'boxSection';
    protected $name = 'boxSection';
}
