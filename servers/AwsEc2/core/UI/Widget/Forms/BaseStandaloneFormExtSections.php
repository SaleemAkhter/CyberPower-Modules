<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms;

/**
 * BaseForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseStandaloneFormExtSections extends BaseStandaloneForm 
    implements \ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AjaxElementInterface,
        \ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\FormInterface
{
    protected $id   = 'baseStandaloneFormExtSections';
    protected $name = 'baseStandaloneFormExtSections';

}
