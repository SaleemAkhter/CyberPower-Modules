<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Others;

use ModulesGarden\Servers\AwsEc2\Core\UI\Builder\BaseContainer;

/**
 * ModuleDescription
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ClientAreaPageDescription extends BaseContainer
{
    use \ModulesGarden\Servers\AwsEc2\Core\UI\Traits\Description;

    protected $name  = 'pageDescription';
    protected $id    = 'pageDescription';
    protected $title = 'pageDescription';
    protected $class = ['info'];

}
