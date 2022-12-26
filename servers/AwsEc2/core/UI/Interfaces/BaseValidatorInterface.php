<?php

namespace ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces;

/**
 * Validator Interface
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
interface BaseValidatorInterface
{

    public function isValid($data, $additionalData = null);
    
    public function getErrorsList();
}
