<?php

namespace ModulesGarden\WordpressManager\Core\UI\Widget\Forms;

/**
 * BaseForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseStandaloneFormExtSections extends BaseStandaloneForm 
    implements \ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface,
        \ModulesGarden\WordpressManager\Core\UI\Interfaces\FormInterface
{
    protected $id   = 'baseStandaloneFormExtSections';
    protected $name = 'baseStandaloneFormExtSections';

}
