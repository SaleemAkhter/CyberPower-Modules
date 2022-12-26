<?php

namespace ModulesGarden\Servers\VultrVps\Core\UI\Widget\Forms;

/**
 * BaseForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseStandaloneFormExtSections extends BaseStandaloneForm 
    implements \ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AjaxElementInterface,
        \ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\FormInterface
{
    protected $id   = 'baseStandaloneFormExtSections';
    protected $name = 'baseStandaloneFormExtSections';

}
