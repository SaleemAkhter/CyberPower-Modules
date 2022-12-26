<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jun 6, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Fields;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Validators\BaseValidator;

/**
 * Description of ConfigNameValidator
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class TextValidator extends BaseValidator
{
    private $pattern;

    public function __construct($pattern = null)
    {
        $this->pattern = $pattern;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    protected function validate($data, $additionalData = null)
    {
        if ($this->pattern && !preg_match($this->pattern, $data))
        {
            $this->addValidationError('Only alphabetical characters and number are allowed');
            
            return false;
        }
        if (is_string($data) && strlen($data) <= 0)
        {
            $this->addValidationError('thisFieldCannotBeEmpty');
            return false;
        }
        return true;
    }
}
