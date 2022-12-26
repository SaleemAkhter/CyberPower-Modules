<?php

namespace ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces;

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
