<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api;


use Psr\Http\Message\RequestInterface;

class GoogleClient extends \Google_Client
{
    public function execute(RequestInterface $request, $expectedClass = null)
    {
        try{
            return parent::execute($request, $expectedClass);
        }catch (\Google_Service_Exception $ex){
            $errors  = [];
            foreach ($ex->getErrors() as $error){
                $errors[] = $error['message'];
            }
            $message = implode(", ", $errors );
            throw new GoogleCloudVirtualMachinesException($message, $ex->getCode(), $ex);
        }

    }


}