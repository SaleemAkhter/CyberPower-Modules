<?php

namespace ModulesGarden\Servers\VultrVps\Core\Traits;

use ModulesGarden\Servers\VultrVps\Core\HandlerError\ErrorCodes\ErrorCodes;
use ModulesGarden\Servers\VultrVps\Core\HandlerError\ErrorCodes\ErrorCodesLib;

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
            $this->errorCodesAppHandler = new \ModulesGarden\Servers\VultrVps\App\Helpers\ErrorCodesLib();
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
