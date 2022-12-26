<?php

/* * ********************************************************************
 * WordPress Manager product developed. (May 8, 2018)
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

namespace ModulesGarden\WordpressManager\App\Helper;

use function \ModulesGarden\WordpressManager\Core\Helper\sl;

/**
 * Description of LangException
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class LangException extends \Exception
{
    private $langContext = 'api';
    
    public function translate(){
       $this->message =  strip_tags($this->message);
       $this->message = sl('lang')->absoluteT($this->langContext, $this->message);
       return $this;
    }
    
    public function getLangContext()
    {
        return $this->langContext;
    }

    public function setLangContext($langContext)
    {
        $this->langContext = $langContext;
        return $this;
    }


}
