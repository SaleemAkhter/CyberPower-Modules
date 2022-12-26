<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Forms;

use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\FormInterface;

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
