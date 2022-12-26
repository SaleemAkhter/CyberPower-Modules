<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms;

/**
 * BaseForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseStandaloneFormExtSections extends BaseStandaloneForm 
    implements \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AjaxElementInterface,
        \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\FormInterface
{
    protected $id   = 'baseStandaloneFormExtSections';
    protected $name = 'baseStandaloneFormExtSections';

}
