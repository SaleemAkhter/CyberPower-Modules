<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Api\AbstractApi;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\Api\AbstractApi\Curl\Response;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\DependencyInjection;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\ApiException;

/**
 * Description of Curl
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
abstract class Curl
{
    private $curl;
    private $options = [
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HEADER         => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLINFO_HEADER_OUT    => true
    ];
    protected $curlParser;

    public function setCurlParser($curlParser)
    {
        $this->curlParser = $curlParser;

        return $this;
    }

    public function setOptions($options, $value)
    {
        $this->options[$options] = $value;
        return $this;
    }

    protected function open()
    {
        $this->curl = curl_init();

        return $this;
    }

    protected function close()
    {
        curl_close($this->curl);

        return $this;
    }

    protected function unsetOptions($options)
    {
        if (is_array($options))
        {
            foreach ($options as $option)
            {
                if (isset($this->options[$option]))
                {
                    unset($this->options[$option]);
                }
            }
        }
        else
        {
            unset($this->options[$options]);
        }

        return $this;
    }

    /**
     * @return Response
     * @throws ApiException
     */
    protected function send()
    {
        $this->includeOptions();

        if (($head = $this->execute()) === false)
        {
            throw new ApiException(self::class, $this->getLastErrorWithCurl());
        }

        if ($errno = $this->getLastErrorNumber())
        {
            throw new ApiException(self::class, $this->getLastError($errno));
        }

        list($header, $body) = $this->curlParser->rebuild($head, $this->getHeaderSize());

        return DependencyInjection::create(Response::class)
                        ->setRequest($this->getHeaderOut())
                        ->setHeader($header)
                        ->setCode($this->getHttpCode())
                        ->setBody($body);
    }

    private function execute()
    {
        return curl_exec($this->curl);
    }

    private function getLastErrorNumber()
    {
        return curl_errno($this->curl);
    }

    private function getLastError($errmo)
    {
        return curl_strerror($errmo);
    }

    private function getLastErrorWithCurl()
    {
        return curl_error($this->curl);
    }

    private function getHeaderSize()
    {
        return curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
    }

    private function getHeaderOut()
    {
        return curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
    }

    private function getHttpCode()
    {
        return curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    }

    private function includeOptions()
    {
        curl_setopt_array($this->curl, $this->options);

        return $this;
    }
}
