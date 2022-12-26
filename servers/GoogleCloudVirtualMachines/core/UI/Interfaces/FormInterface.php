<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces;

/**
 * Validator Interface
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
interface FormInterface
{
    public function getField($fieldId);

    public function getFields();
}
