<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jul 30, 2018)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\UI\Validators;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Validators\BaseValidator;

/**
 * Description of DomainValidator
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class DomainValidator extends BaseValidator
{
    protected $required = true;

    public function __construct($required = true)
    {
        $this->required = $required;
    }

    protected function validate($data, $additionalData = null)
    {
        if (!$this->required && empty($data))
        {
            return true;
        }
        if (gethostbyname($data))
        {
            return true;
        }
        $this->addValidationError('Domain is not valid');
        return false;
    }
}
