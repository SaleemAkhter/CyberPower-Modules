<?php


namespace ModulesGarden\Servers\VultrVps\App\Api;


use GuzzleHttp\Message\Response;

class ResponseParser
{

    protected $response;
    protected $json;


    public function validate($response)
    {

        $this->response = $response;
        $this->json = json_decode($this->response->getBody());
        if (isset($this->json->error) && $this->json->error)
        {
            throw  new \Exception((string)$this->json->error, (int)$this->json->status);
        }
        if (isset($this->json->message) && $this->json->message && $this->json->status >= 400)
        {
            throw  new \Exception((string)$this->json->message, (int)$this->json->status);
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