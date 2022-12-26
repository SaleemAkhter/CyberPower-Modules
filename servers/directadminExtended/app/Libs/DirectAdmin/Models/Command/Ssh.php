<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class Ssh extends AbstractModel implements ResponseLoad
{

    protected $id;
    protected $comment;
    protected $password;
    protected $password2;
    protected $type;
    protected $fingerprint;
    protected $select = [];
    protected $option;
    protected $authorize;

    protected $authorizedKeys;
    protected $globalKeys;
    protected $keyOptions;
    protected $keysize;
    protected $publicKeys;
    protected $users;
    protected $sshKey;

    protected $command;
    protected $environment;
    protected $noagentforwarding;
    protected $from;
    protected $noportforwarding;
    protected $noX11forwarding;
    protected $nopty;
    protected $tunnel;
    protected $permitopen;


    public function loadResponse($response, $function = null)
    {
        if ($response && !$function) {
            $this->addResponseElement(new self($response));

            return $this;
        }
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getSSHKey()
    {
        return $this->sshKey;
    }

    public function setSSHKey($sshKey)
    {
        $this->sshKey = $sshKey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
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
     * @param mixed $domain
     * @return Ssh
     */
    public function setPassword($password)
    {
        $this->password = $password;
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
     * @param mixed $domain
     * @return Ssh
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setOption($option)
    {
        $this->option = $option;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKeySize()
    {
        return $this->keysize;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setKeysize($keysize)
    {
        $this->keysize = $keysize;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthorizedKeys()
    {
        return $this->authorizedKeys;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setAuthorizedKeys($authorizedKeys)
    {
        $this->authorizedKeys = $authorizedKeys;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getKeyOptions()
    {
        return $this->keyOptions;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setKeyOptions($keyOptions)
    {
        $this->keyOptions = $keyOptions;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getPublicKeys()
    {
        return $this->publicKeys;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setPublicKeys($publicKeys)
    {
        $this->publicKeys = $publicKeys;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function getFingerprint()
    {
        return $this->fingerprint;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setFingerprint($fingerprint)
    {
        $this->fingerprint = $fingerprint;
        return $this;
    }

    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setSelect($select)
    {
        $this->select = $select;
        return $this;
    }

    public function getcommand()
    {
        return $this->command;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setcommand($command)
    {
        $this->command = $command;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getenvironment()
    {
        return $this->environment;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setenvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getnoagentforwarding()
    {
        return $this->noagentforwarding;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setnoagentforwarding($noagentforwarding)
    {
        $this->noagentforwarding = $noagentforwarding;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getfrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setfrom($from)
    {
        $this->from = $from;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getnoportforwarding()
    {
        return $this->noportforwarding;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setnoportforwarding($noPortForwarding)
    {
        $this->noportforwarding = $noPortForwarding;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getnoX11forwarding()
    {
        return $this->noX11forwarding;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setnoX11forwarding($noX11Forwarding)
    {
        $this->noX11forwarding = $noX11Forwarding;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getnopty()
    {
        return $this->nopty;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setnopty($noPty)
    {
        $this->nopty = $noPty;
        return $this;
    }



    /**
     * @return mixed
     */
    public function gettunnel()
    {
        return $this->tunnel;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function settunnel($tunnel)
    {
        $this->tunnel = $tunnel;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getpermitopen()
    {
        return $this->permitopen;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setpermitopen($permitopen)
    {
        $this->permitopen = $permitopen;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getAuthorize()
    {
        return $this->authorize;
    }

    /**
     * @param mixed $domain
     * @return Ssh
     */
    public function setAuthorize($authorize)
    {
        $this->authorize = $authorize;
        return $this;
    }

}
