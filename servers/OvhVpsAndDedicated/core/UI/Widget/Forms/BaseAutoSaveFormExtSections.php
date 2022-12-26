<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\FormInterface;

/**
 * BaseForm controler
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseAutoSaveFormExtSections extends BaseStandaloneForm implements AjaxElementInterface, FormInterface
{
    protected $id   = 'baseAutoSaveFormExtSections';
    protected $name = 'baseAutoSaveFormExtSections';

}
