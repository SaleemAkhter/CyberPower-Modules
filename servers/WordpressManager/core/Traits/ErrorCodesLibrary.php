<?php

namespace ModulesGarden\WordpressManager\Core\Traits;

use \ModulesGarden\WordpressManager\Core\HandlerError\ErrorCodes\ErrorCodesLib;
use \ModulesGarden\WordpressManager\Core\HandlerError\ErrorCodes\ErrorCodes;

trait ErrorCodesLibrary
{
    /** 
     * @var ErrorCodesLib
     */
    protected $errorCodesCoreHandler = null;

    /** 
     * @var ErrorCodesLib
     */
    protected $errorCodesAppHandler = null;
    
    public function loadErrorCodes()
    {
        if ($this->errorCodesCoreHandler === null)
        {
            $this->errorCodesCoreHandler = new ErrorCodesLib();
        }
        
        if ($this->errorCodesAppHandler === null)
        {
            $this->errorCodesAppHandler = new \ModulesGarden\WordpressManager\App\Helper\ErrorCodesLib();
        }        
    }

    public function genErrorCode($code = null)
    {
        $this->loadErrorCodes();

        if ($this->errorCodesAppHandler->errorCodeExists($code[ErrorCodes::CODE]))
        {
            return $this->errorCodesAppHandler->getErrorMessageByCode($code[ErrorCodes::CODE]);
        }
       
        return $this->errorCodesCoreHandler->getErrorMessageByCode($code[ErrorCodes::CODE]);
    }
}
