<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:02
 */
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\ErrorHandler\Exceptions;

class ErrorParser
{
    protected $response;
    protected $throwException;
    protected $errorMessage = null;

    public function __construct($response, $throwException = true)
    {
        $this->response         = $response;
        if(is_bool($throwException))
        {
            $this->throwException   = $throwException;
        }
    }

    public function parse()
    {
        if(is_array($this->response))
        {
            if(isset($this->response['error']) && (int)$this->response['error'] === 1)
            {
                $this->errorMessage = $this->response['text'] . ' : ' . $this->response['details'];
            }
        }

        if($this->throwException === true && $this->errorMessage !== null)
        {
            $this->removeLinksFromErrorResponse();
            throw new Exceptions\ApiException($this->errorMessage);
        }
    }

    public function getResponse()
    {
        $this->parse();
        return $this->response;
    }

    public function removeLinksFromErrorResponse()
    {
        $searchLinks = explode(PHP_EOL, $this->errorMessage);

        foreach($searchLinks as $key => $inputLine)
        {
            preg_match('/<a\s+(?:[^"\'>]+|"[^"]*"|\'[^\']*\')*href=("[^"]+"|\'[^\']+\'|[^<>\s]+)/', $inputLine, $outputArray);
            if(!empty($outputArray))
            {
                unset($searchLinks[$key]);
            }
        }

        $this->errorMessage = implode(PHP_EOL, $searchLinks);
    }
}