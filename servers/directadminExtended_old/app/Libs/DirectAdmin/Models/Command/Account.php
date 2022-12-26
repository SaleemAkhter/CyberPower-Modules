<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 16:37
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class Account extends AbstractModel
{
    protected $username;
    protected $email;
    protected $password;
    protected $password2;
    protected $domain;
    protected $package;
    protected $ip;
    protected $notify;
    protected $ubandwidth;
    protected $bandwidth;
    protected $quota;
    protected $uquota;
    protected $vdomains;
    protected $uvdomains;
    protected $nsubdomains;
    protected $unsubdomains;
    protected $nemails;
    protected $unemails;
    protected $nemailf;
    protected $unemailf;
    protected $nemailml;
    protected $unemailml;
    protected $nemailr;
    protected $unemailr;
    protected $mysql;
    protected $umysql;
    protected $domainptr;
    protected $udomainptr;
    protected $ftp;
    protected $uftp;
    protected $aftp;
    protected $cgi;
    protected $php;
    protected $spam;
    protected $cron;
    protected $catchall;
    protected $ssl;
    protected $ssh;
    protected $sysinfo;
    protected $dnscontrol;
    protected $add = 'Submit';
    protected $suspend_at_limit;
    protected $inode;
    protected $uinode;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return AccountUser
     */
    public function setUsername($username)
    {
        $this->username = $username;
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
     * @return AccountUser
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return AccountUser
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword2()
    {
        return $this->password2;
    }

    /**
     * @param mixed $password2
     * @return AccountUser
     */
    public function setPassword2($password2)
    {
        $this->password2 = $password2;
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
     * @return AccountUser
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @param mixed $package
     * @return AccountUser
     */
    public function setPackage($package)
    {
        $this->package = $package;
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
     * @return AccountUser
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotify()
    {
        return $this->notify;
    }

    /**
     * @param mixed $notify
     * @return AccountUser
     */
    public function setNotify($notify)
    {
        $this->notify = $notify;
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
     * @return AccountUser
     */
    public function setBandwidth($bandwidth)
    {
        $this->bandwidth = $bandwidth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUBandwidth()
    {
        return $this->ubandwidth;
    }

    /**
     * @param mixed $bandwidth
     * @return AccountUser
     */
    public function setUBandwidth($bandwidth)
    {
        $this->ubandwidth = $bandwidth;
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
     * @return AccountUser
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;
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
     * @return AccountUser
     */
    public function setUquota($uquota)
    {
        $this->uquota = $uquota;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVdomains()
    {
        return $this->vdomains;
    }

    /**
     * @param mixed $vdomains
     * @return AccountUser
     */
    public function setVdomains($vdomains)
    {
        $this->vdomains = $vdomains;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUvdomains()
    {
        return $this->uvdomains;
    }

    /**
     * @param mixed $uvdomains
     * @return AccountUser
     */
    public function setUvdomains($uvdomains)
    {
        $this->uvdomains = $uvdomains;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNsubdomains()
    {
        return $this->nsubdomains;
    }

    /**
     * @param mixed $nsubdomains
     * @return AccountUser
     */
    public function setNsubdomains($nsubdomains)
    {
        $this->nsubdomains = $nsubdomains;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnsubdomains()
    {
        return $this->unsubdomains;
    }

    /**
     * @param mixed $unsubdomains
     * @return AccountUser
     */
    public function setUnsubdomains($unsubdomains)
    {
        $this->unsubdomains = $unsubdomains;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNemails()
    {
        return $this->nemails;
    }

    /**
     * @param mixed $nemails
     * @return AccountUser
     */
    public function setNemails($nemails)
    {
        $this->nemails = $nemails;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnemails()
    {
        return $this->unemails;
    }

    /**
     * @param mixed $unemails
     * @return AccountUser
     */
    public function setUnemails($unemails)
    {
        $this->unemails = $unemails;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNemailf()
    {
        return $this->nemailf;
    }

    /**
     * @param mixed $nemailf
     * @return AccountUser
     */
    public function setNemailf($nemailf)
    {
        $this->nemailf = $nemailf;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnemailf()
    {
        return $this->unemailf;
    }

    /**
     * @param mixed $unemailf
     * @return AccountUser
     */
    public function setUnemailf($unemailf)
    {
        $this->unemailf = $unemailf;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNemailml()
    {
        return $this->nemailml;
    }

    /**
     * @param mixed $nemailml
     * @return AccountUser
     */
    public function setNemailml($nemailml)
    {
        $this->nemailml = $nemailml;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnemailml()
    {
        return $this->unemailml;
    }

    /**
     * @param mixed $unemailml
     * @return AccountUser
     */
    public function setUnemailml($unemailml)
    {
        $this->unemailml = $unemailml;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNemailr()
    {
        return $this->nemailr;
    }

    /**
     * @param mixed $nemailr
     * @return AccountUser
     */
    public function setNemailr($nemailr)
    {
        $this->nemailr = $nemailr;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUnemailr()
    {
        return $this->unemailr;
    }

    /**
     * @param mixed $unemailr
     * @return AccountUser
     */
    public function setUnemailr($unemailr)
    {
        $this->unemailr = $unemailr;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMysql()
    {
        return $this->mysql;
    }

    /**
     * @param mixed $mysql
     * @return AccountUser
     */
    public function setMysql($mysql)
    {
        $this->mysql = $mysql;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUmysql()
    {
        return $this->umysql;
    }

    /**
     * @param mixed $umysql
     * @return AccountUser
     */
    public function setUmysql($umysql)
    {
        $this->umysql = $umysql;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomainptr()
    {
        return $this->domainptr;
    }

    /**
     * @param mixed $domainptr
     * @return AccountUser
     */
    public function setDomainptr($domainptr)
    {
        $this->domainptr = $domainptr;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUdomainptr()
    {
        return $this->udomainptr;
    }

    /**
     * @param mixed $udomainptr
     * @return AccountUser
     */
    public function setUdomainptr($udomainptr)
    {
        $this->udomainptr = $udomainptr;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFtp()
    {
        return $this->ftp;
    }

    /**
     * @param mixed $ftp
     * @return AccountUser
     */
    public function setFtp($ftp)
    {
        $this->ftp = $ftp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUftp()
    {
        return $this->uftp;
    }

    /**
     * @param mixed $uftp
     * @return AccountUser
     */
    public function setUftp($uftp)
    {
        $this->uftp = $uftp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAftp()
    {
        return $this->aftp;
    }

    /**
     * @param mixed $aftp
     * @return AccountUser
     */
    public function setAftp($aftp)
    {
        $this->aftp = $aftp;
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
     * @return AccountUser
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
     * @return AccountUser
     */
    public function setPhp($php)
    {
        $this->php = $php;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpam()
    {
        return $this->spam;
    }

    /**
     * @param mixed $spam
     * @return AccountUser
     */
    public function setSpam($spam)
    {
        $this->spam = $spam;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCron()
    {
        return $this->cron;
    }

    /**
     * @param mixed $cron
     * @return AccountUser
     */
    public function setCron($cron)
    {
        $this->cron = $cron;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCatchall()
    {
        return $this->catchall;
    }

    /**
     * @param mixed $catchall
     * @return AccountUser
     */
    public function setCatchall($catchall)
    {
        $this->catchall = $catchall;
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
     * @return AccountUser
     */
    public function setSsl($ssl)
    {
        $this->ssl = $ssl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSsh()
    {
        return $this->ssh;
    }

    /**
     * @param mixed $ssh
     * @return AccountUser
     */
    public function setSsh($ssh)
    {
        $this->ssh = $ssh;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSysinfo()
    {
        return $this->sysinfo;
    }

    /**
     * @param mixed $sysinfo
     * @return AccountUser
     */
    public function setSysinfo($sysinfo)
    {
        $this->sysinfo = $sysinfo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDnscontrol()
    {
        return $this->dnscontrol;
    }

    /**
     * @param mixed $dnscontrol
     * @return AccountUser
     */
    public function setDnscontrol($dnscontrol)
    {
        $this->dnscontrol = $dnscontrol;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdd()
    {
        return $this->add;
    }

    /**
     * @param string $add
     * @return Account
     */
    public function setAdd($add)
    {
        $this->add = $add;
        return $this;
    }

    public function getSuspendAtLimit()
    {
        return $this->suspend_at_limit;
    }

    public function setSuspendAtLimit($suspendAtLimit)
    {
        $this->suspend_at_limit = $suspendAtLimit;
        return $this;
    }

    public function getInode()
    {
        return $this->inode;
    }

    public function setInode($inode)
    {
        $this->inode = $inode;
        return $this;
    }

    public function getUinode()
    {
        return $this->uinode;
    }

    public function setUinode($inode)
    {
        $this->uinode = $inode;
        return $this;
    }


}