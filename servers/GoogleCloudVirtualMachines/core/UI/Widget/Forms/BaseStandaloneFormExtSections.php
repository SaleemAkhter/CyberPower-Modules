<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Forms;

/**
 * BaseForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseStandaloneFormExtSections extends BaseStandaloneForm 
    implements \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AjaxElementInterface,
        \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\FormInterface
{
    protected $id   = 'baseStandaloneFormExtSections';
    protected $name = 'baseStandaloneFormExtSections';

}
