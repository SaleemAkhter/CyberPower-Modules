<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Traits;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\ErrorCodes\ErrorCodesLib;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\ErrorCodes\ErrorCodes;

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
            $this->errorCodesAppHandler = new \ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers\ErrorCodesLib();
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
