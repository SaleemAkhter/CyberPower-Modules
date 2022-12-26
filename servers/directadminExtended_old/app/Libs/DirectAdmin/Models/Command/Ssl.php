<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 16:37
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class Ssl extends AbstractModel implements ResponseLoad
{
    public $domain;
    protected $country;
    protected $city;
    protected $company;
    protected $division;
    protected $email;
    protected $name;
    protected $keysize;
    protected $province;
    protected $type;
    public $certificate;
    public $key;
    protected $server;
    protected $signed;
    protected $sslOn;
    protected $request;
    protected $encryption;
    protected $entries;
    protected $encryptOptions;
    protected $wildcard;


    public function loadResponse($response, $function = null)
    {
        if ($response && !$function)
        {
            $response['sslOn'] = $response['ssl_on'];
            $this->addResponseElement(new self($response));

            return $this;
        }
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return Ssl
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return Ssl
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     * @return Ssl
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * @param mixed $division
     * @return Ssl
     */
    public function setDivision($division)
    {
        $this->division = $division;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Ssl
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Ssl
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKeysize()
    {
        return $this->keysize;
    }

    /**
     * @param mixed $keysize
     * @return Ssl
     */
    public function setKeysize($keysize)
    {
        $this->keysize = $keysize;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param mixed $province
     * @return Ssl
     */
    public function setProvince($province)
    {
        $this->province = $province;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return Ssl
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     * @return Ssl
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * @param mixed $certificate
     * @return Ssl
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     * @return Ssl
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param mixed $server
     * @return Ssl
     */
    public function setServer($server)
    {
        $this->server = $server;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSigned()
    {
        return $this->signed;
    }

    /**
     * @param mixed $signed
     * @return Ssl
     */
    public function setSigned($signed)
    {
        $this->signed = $signed;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSslOn()
    {
        return $this->sslOn;
    }

    /**
     * @param mixed $sslOn
     * @return Ssl
     */
    public function setSslOn($sslOn)
    {
        $this->sslOn = $sslOn;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     * @return Ssl
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEncryption()
    {
        return $this->encryption;
    }

    /**
     * @param mixed $encryption
     * @return Ssl
     */
    public function setEncryption($encryption)
    {
        $this->encryption = $encryption;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntries()
    {
        return $this->formatList('le_select', $this->entries);
    }

    /**
     * @param mixed $entries
     * @return Ssl
     */
    public function setEntries($entries)
    {
        $this->entries = $entries;
        return $this;
    }

    protected function formatList($name, $list){
        $startNumber = 0;
        $newList = [];

        foreach($list as $element){
            $newList[$name.$startNumber++] = $element;
        }


        return $newList;

    }

    public function setEncryptOptions($options)
    {
        if(is_object($options) || is_array($options))
        {
            foreach($options as $key => $value)
            {
                $this->encryptOptions[] = $value;
            }
            return $this;
        }
    }

    public function getEncryptOptions()
    {
        return $this->encryptOptions;
    }

    public function setWildcard($wildcard)
    {
        $this->wildcard = $wildcard;
        return $this;
    }

    public function getWildcard()
    {
        return $this->wildcard;
    }




}