<?php
/**********************************************************************
 * Wordpress_Manager Product developed. (Dec 18, 2017)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\Modules\DirectAdmin\Models;

class AddonDomain
{
    /**
     * @var string
     */
    protected $sslCaCertficateFile;
    /**
     * @var
     */
    protected $sslCertificateFile;
    protected $sslCertificateKeyFile;
    protected $active;
    protected $bandiwdth;
    protected $cgi;
    protected $domain;
    protected $php;
    protected $quota;
    protected $ssl;


    /**
     * @param array $source
     * @return AddonDomain
     */
    public static function fromSource(array $source)
    {
        $new    = new self();

        $new->setDomain($source['domain']);
        $new->setSslCaCertficateFile($source['SSLCACertificateFile']);
        $new->setSslCertificateFile($source['SSLCertificateFile']);
        $new->setSslCertificateKeyFile($source['SSLCertificateKeyFile']);
        $new->setActive($source['active'] == 'yes' ? true : false);
        $new->setBandiwdth($source['bandwidth']);
        $new->setQuota($source['quota']);
        $new->setCgi($source['cgi'] == 'yes' ? true : false);
        $new->setPhp($source['php'] == 'yes' ? true : false);
        $new->setSsl($source['ssl'] == 'on' ? true : false);

        return $new;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $data   = [
            'domain'    => $this->getDomain(),
            'ssl'       => $this->getSsl() ? 'ON' : 'OFF',
            'cgi'       => $this->getCgi() ? 'ON' : 'OFF',
            'php'       => $this->getPhp() ? 'ON' : 'OFF'
        ];

        if($this->isBandwithUntlimited())
        {
            $data['ubandwidth'] = $this->getBandiwdth();
        }
        else
        {
            $data['bandwidth'] = $this->getBandiwdth();
        }

        if($this->isQuotaUnlimited())
        {
            $data['uquota'] = $this->getQuota();
        }
        else
        {
            $data['quota'] = $this->getQuota();
        }

        return $data;
    }

    /**
     * @return mixed
     */
    public function getSslCaCertficateFile()
    {
        return $this->sslCaCertficateFile;
    }

    /**
     * @param mixed $sslCaCertficateFile
     */
    public function setSslCaCertficateFile($sslCaCertficateFile)
    {
        $this->sslCaCertficateFile = $sslCaCertficateFile;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSslCertificateFile()
    {
        return $this->sslCertificateFile;
    }

    /**
     * @param mixed $sslCertificateFile
     */
    public function setSslCertificateFile($sslCertificateFile)
    {
        $this->sslCertificateFile = $sslCertificateFile;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSslCertificateKeyFile()
    {
        return $this->sslCertificateKeyFile;
    }

    /**
     * @param mixed $sslCertificateKeyFile
     */
    public function setSslCertificateKeyFile($sslCertificateKeyFile)
    {
        $this->sslCertificateKeyFile = $sslCertificateKeyFile;

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
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBandiwdth()
    {
        return $this->bandiwdth;
    }

    /**
     * @param mixed $bandiwdth
     */
    public function setBandiwdth($bandiwdth)
    {
        $this->bandiwdth = $bandiwdth;

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
     */
    public function setCgi($cgi)
    {
        $this->cgi = $cgi;

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
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

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
     */
    public function setPhp($php)
    {
        $this->php = $php;

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
     */
    public function setSsl($ssl)
    {
        $this->ssl = $ssl;

        return $this;
    }

    /**
     * @return bool
     */
    public function isQuotaUnlimited()
    {
        return $this->getQuota() == 'unlimited';
    }

    /**
     * @return bool
     */
    public function isBandwithUntlimited()
    {
        return $this->getBandiwdth() == 'unlimited';
    }
}
