<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\Traits;

/**
 * Submit Button Elements
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait SubmitButton
{
    protected $formId = null;

    public function setFormId($id)
    {
        $this->formId = $id;

        return $this;
    }

    public function getFormId()
    {
        return $this->formId;
    }
}
