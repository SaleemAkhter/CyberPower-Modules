<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\Servers\AwsEc2\Core\UI\Traits\HideButtonByColumnValue;

/**
 * BaseField controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class Text extends BaseField
{
    use HideButtonByColumnValue;
    protected $id   = 'text';
    protected $name = 'text';
}
