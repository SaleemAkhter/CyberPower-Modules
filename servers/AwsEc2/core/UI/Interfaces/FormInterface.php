<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces;

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
