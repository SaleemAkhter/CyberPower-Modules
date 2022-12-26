<?php

namespace ModulesGarden\Servers\HetznerVps\Core\UI;

use ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer;

/**
 * Search window element
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Searchable extends BaseContainer
{
    protected $name                = 'searchable';
    protected $id                  = 'searchable';
    protected $defaultTemplateName = 'searchable';
    protected $templateName        = 'searchable';
}
