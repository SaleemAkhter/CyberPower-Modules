<?php

namespace ModulesGarden\Servers\HetznerVps\Core\UI\Widget\Forms;

/**
 * BaseForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseStandaloneFormExtSections extends BaseStandaloneForm 
    implements \ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\AjaxElementInterface,
        \ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\FormInterface
{
    protected $id   = 'baseStandaloneFormExtSections';
    protected $name = 'baseStandaloneFormExtSections';

}
