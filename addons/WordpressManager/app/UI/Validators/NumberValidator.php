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
 * Description of NumberValidator
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class NumberValidator extends BaseValidator
{
    protected $minValue = 0;
    protected $maxValue = 0;
    protected $required = false;

    public function __construct($min = 0, $max = 0, $required = false)
    {
        $this->minValue = (int) $min;
        $this->maxValue = (int) $max;
        $this->required = $required;
    }

    protected function validate($data, $additionalData = null)
    {
        if(!$this->required && empty($data)){
            return true;
        }
        if (is_numeric($data) && $this->minValue === 0 && $this->maxValue === 0)
        {
            return true;
        }
        
        if (is_numeric($data) && $this->minValue <= ((int) $data) && ((int) $data) <= $this->maxValue)
        {
            return true;
        }


        if ($this->minValue === $this->maxValue)
        {
            $this->addValidationError('PleaseProvideANumericValue');

            return false;
        }

        $this->addValidationError('PleaseProvideANumericValueBetween', false, ['minValue' => $this->minValue, 'maxValue' => $this->maxValue]);

        return false;
    }
}
