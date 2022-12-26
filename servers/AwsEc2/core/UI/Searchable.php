<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI;

use ModulesGarden\Servers\AwsEc2\Core\UI\Builder\BaseContainer;

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
