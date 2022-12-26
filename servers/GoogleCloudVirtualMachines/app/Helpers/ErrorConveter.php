<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api\GoogleCloudVirtualMachinesException;

class ErrorConveter
{
    protected $error;

    /**
     * ErrorConveter constructor.
     * @param $error
     */
    public function __construct($error)
    {
        $this->error = $error;
    }

    /**
     *
     */
    public function convertToExceptionAndThrow(){
        if(!method_exists($this->error, 'getErrors')){
            return;
        }
        $errors  = [];
        foreach ($this->error->getErrors() as $error){
            $errors[] = $error['message'];
        }
        $message = implode(", ", $errors );
        throw new GoogleCloudVirtualMachinesException($message);
    }

}