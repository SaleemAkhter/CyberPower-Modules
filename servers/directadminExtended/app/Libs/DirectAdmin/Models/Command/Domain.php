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

class Domain extends AbstractModel implements ResponseLoad
{
    public $name;
    protected $bandwidth;
    protected $quota;
    protected $uquota;
    protected $ssl;
    protected $forceSsl;
    protected $cgi;
    protected $php;
    protected $active;
    protected $defaultdomain;
    protected $hasMultipleIps;
    public $hasPhpSelector;
    protected $ip;
    protected $openBasedir;
    protected $privateHtml;
    protected $safemode;
    protected $suspended;
    public $php1Info;
    public $php1Select;
    public $php2Info;
    public $php2Select;
    public $localMail;
    protected $redirect;
    public $message;

    public function loadResponse($response, $function = null)
    {
        if(isset($response['list']))
        {
            foreach ($response['list'] as $domain)
            {
                $self = new self(['name' => $domain]);
                $this->addResponseElement($self);
            }
        }

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
     * @return Domain
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return Domain
     */
    public function setBandwidth($bandwidth)
    {
        $this->bandwidth = $bandwidth;
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
     * @return Domain
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;
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
     * @return Domain
     */
    public function setSsl($ssl)
    {
        $this->ssl = $ssl;
        return $this;
    }
    public function getForceSsl()
    {
        return $this->forceSsl;
    }

    public function setForceSsl($forceSsl)
    {
        $this->forceSsl = $forceSsl;
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
     * @return Domain
     */
    public function setCgi($cgi)
    {
        $this->cgi = $cgi;
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
     * @return Domain
     */
    public function setPhp($php)
    {
        $this->php = $php;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUquota()
    {
        return $this->uquota;
    }

    /**
     * @param mixed $uquota
     * @return Domain
     */
    public function setUquota($uquota)
    {
        $this->uquota = $uquota;
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
     * @return Domain
     */
    public function setActive($active)
    {
        $this->active = $active;
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
     * @return Domain
     */
    public function setDefaultdomain($defaultdomain)
    {
        $this->defaultdomain = $defaultdomain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasMultipleIps()
    {
        return $this->hasMultipleIps;
    }

    /**
     * @param mixed $hasMultipleIps
     * @return Domain
     */
    public function setHasMultipleIps($hasMultipleIps)
    {
        $this->hasMultipleIps = $hasMultipleIps;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasPhpSelector()
    {
        return $this->hasPhpSelector;
    }

    /**
     * @param mixed $hasPhpSelector
     * @return Domain
     */
    public function setHasPhpSelector($hasPhpSelector)
    {
        $this->hasPhpSelector = $hasPhpSelector;
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
     * @return Domain
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
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
     * @return Domain
     */
    public function setOpenBasedir($openBasedir)
    {
        $this->openBasedir = $openBasedir;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrivateHtml()
    {
        return $this->privateHtml;
    }

    /**
     * @param mixed $privateHtml
     * @return Domain
     */
    public function setPrivateHtml($privateHtml)
    {
        $this->privateHtml = $privateHtml;
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
     * @return Domain
     */
    public function setSafemode($safemode)
    {
        $this->safemode = $safemode;
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
     * @return Domain
     */
    public function setSuspended($suspended)
    {
        $this->suspended = $suspended;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhp1Info()
    {
        return $this->php1Info;
    }

    /**
     * @return null
     */
    public function getPhp2Info()
    {
        return $this->php2Select ? $this->php2Info : 0;
    }

    /**
     * @return array
     */
    public function getPhpInfo()
    {
        return [
            'php1'  => $this->php1Info,
            'php2'  => $this->getPhp2Info()
        ];;
    }
    /**
     * @param mixed $php1Info
     * @return Domain
     */
    public function setPhp1Info($php1Info)
    {
        $this->php1Info = $php1Info;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhp1Select()
    {
        return $this->php1Select;
    }

    /**
     * @return mixed
     */
    public function getPhp2Select()
    {
        return $this->php2Select;
    }

    /**
     * @return mixed
     */
    public function getLocalMail()
    {
        return ($this->localMail == "on") ? "yes":  "no";
    }

    /**
     * @param mixed $localMail
     */
    public function setLocalMail($localMail)
    {
        $this->localMail = $localMail;
    }

    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    public function getRedirect()
    {
        return $this->redirect;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }
}