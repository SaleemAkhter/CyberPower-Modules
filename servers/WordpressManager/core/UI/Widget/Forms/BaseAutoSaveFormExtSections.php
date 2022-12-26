<?php

namespace ModulesGarden\WordpressManager\Core\UI\Widget\Forms;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\FormInterface;

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
