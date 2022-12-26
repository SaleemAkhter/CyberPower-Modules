<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\Widget\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\FormInterface;

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
