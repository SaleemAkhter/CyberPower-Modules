<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Api;



use GuzzleHttp\Message\Response;

class ResponseParser
{

    protected $response;
    protected $json;


    public function validate(Response $response){

        $this->response = $response;
        //to do validate
        $this->json = json_decode($this->response->getBody());
        if($this->json->errorMessage){
            throw  new \Exception((string) $this->json->errorMessage);
        }
        $errors=[];
        foreach ($this->json->errors as $error){
            $errors[] = sprintf("%s: %s", $error->message, $error->target->name);
        }
        if($errors){
            throw  new \Exception(implode(",", $errors));
        }
    }

    /**
     * @return mixed
     */
    public function getJson()
    {
        return $this->json;
    }



}