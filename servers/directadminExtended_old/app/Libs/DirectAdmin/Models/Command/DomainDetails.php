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

class DomainDetails extends AbstractModel implements ResponseLoad
{
    protected $useCanonicalName;
    protected $active;
    protected $aliasPointers;
    protected $bandwidth;
    protected $bandwidthLimit;
    protected $cgi;
    protected $defaultdomain;
    protected $domain;
    protected $ip;
    protected $localMail;
    protected $openBasedir;
    protected $php;
    protected $pointers;
    protected $quota;
    protected $quotaLimit;
    protected $safemode;
    protected $ssl;
    protected $subdomain;
    protected $suspended;
    protected $username;
    protected $wwwPointers;
    protected $php1Select;
    protected $php2Select;


    public function loadResponse($response, $function = null)
    {

        if(is_array($response))
        {
            parse_str(reset($response), $details);

            foreach ($details as $key => $value)
            {
                $functionName = $this->convertToCamelCase($key, "_", "set");

                if (method_exists($this, $functionName))
                {
                    $this->{$functionName}($value);
                }
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUseCanonicalName()
    {
        return $this->useCanonicalName;
    }

    /**
     * @param mixed $useCanonicalName
     * @return DomainDetails
     */
    public function setUseCanonicalName($useCanonicalName)
    {
        $this->useCanonicalName = $useCanonicalName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     * @return DomainDetails
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAliasPointers()
    {
        return $this->aliasPointers;
    }

    /**
     * @param mixed $aliasPointers
     * @return DomainDetails
     */
    public function setAliasPointers($aliasPointers)
    {
        $this->aliasPointers = $aliasPointers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBandwidth()
    {
        return $this->bandwidth;
    }

    /**
     * @param mixed $bandwidth
     * @return DomainDetails
     */
    public function setBandwidth($bandwidth)
    {
        $this->bandwidth = $bandwidth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBandwidthLimit()
    {
        return $this->bandwidthLimit;
    }

    /**
     * @param mixed $bandwidthLimit
     * @return DomainDetails
     */
    public function setBandwidthLimit($bandwidthLimit)
    {
        $this->bandwidthLimit = $bandwidthLimit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCgi()
    {
        return $this->cgi;
    }

    /**
     * @param mixed $cgi
     * @return DomainDetails
     */
    public function setCgi($cgi)
    {
        $this->cgi = $cgi;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefaultdomain()
    {
        return $this->defaultdomain;
    }

    /**
     * @param mixed $defaultdomain
     * @return DomainDetails
     */
    public function setDefaultdomain($defaultdomain)
    {
        $this->defaultdomain = $defaultdomain;
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
     * @return DomainDetails
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     * @return DomainDetails
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocalMail()
    {
        return $this->localMail;
    }

    /**
     * @param mixed $localMail
     * @return DomainDetails
     */
    public function setLocalMail($localMail)
    {
        $this->localMail = $localMail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOpenBasedir()
    {
        return $this->openBasedir;
    }

    /**
     * @param mixed $openBasedir
     * @return DomainDetails
     */
    public function setOpenBasedir($openBasedir)
    {
        $this->openBasedir = $openBasedir;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhp()
    {
        return $this->php;
    }

    /**
     * @param mixed $php
     * @return DomainDetails
     */
    public function setPhp($php)
    {
        $this->php = $php;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPointers()
    {
        return $this->pointers;
    }

    /**
     * @param mixed $pointers
     * @return DomainDetails
     */
    public function setPointers($pointers)
    {
        $this->pointers = $pointers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * @param mixed $quota
     * @return DomainDetails
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuotaLimit()
    {
        return $this->quotaLimit;
    }

    /**
     * @param mixed $quotaLimit
     * @return DomainDetails
     */
    public function setQuotaLimit($quotaLimit)
    {
        $this->quotaLimit = $quotaLimit;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSafemode()
    {
        return $this->safemode;
    }

    /**
     * @param mixed $safemode
     * @return DomainDetails
     */
    public function setSafemode($safemode)
    {
        $this->safemode = $safemode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSsl()
    {
        return $this->ssl;
    }

    /**
     * @param mixed $ssl
     * @return DomainDetails
     */
    public function setSsl($ssl)
    {
        $this->ssl = $ssl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * @param mixed $subdomain
     * @return DomainDetails
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSuspended()
    {
        return $this->suspended;
    }

    /**
     * @param mixed $suspended
     * @return DomainDetails
     */
    public function setSuspended($suspended)
    {
        $this->suspended = $suspended;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return DomainDetails
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWwwPointers()
    {
        return $this->wwwPointers;
    }

    /**
     * @param mixed $wwwPointers
     * @return DomainDetails
     */
    public function setWwwPointers($wwwPointers)
    {
        $this->wwwPointers = $wwwPointers;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhp1Select()
    {
        return (int) $this->php1Select;
    }

    /**
     * @param mixed $php1Select
     */
    public function setPhp1Select($php1Select)
    {
        $this->php1Select = $php1Select;
    }

    /**
     * @return mixed
     */
    public function getPhp2Select()
    {
        return (int) $this->php2Select;
    }

    /**
     * @param mixed $php2Select
     */
    public function setPhp2Select($php2Select)
    {
        $this->php2Select = $php2Select;
    }






}