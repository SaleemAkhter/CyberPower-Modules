<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Redirect\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\SSO;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;

class RedirectPage extends BaseContainer implements ClientArea
{
    protected $id = 'redirectPage';
    protected $redirectURL;
    protected $username;
    protected $password;
    protected $error;
    public function initContent()
    {
        $sso =  new SSO($this->getRequestValue('id'));

        $this->fillFields($sso->getCorrectRedirection($this->getRequestValue('redirect-action', false)));

        parent::initContent();
    }


    protected function fillFields($data)
    {
        if($data['error'])
        {
            $this->setError($data['error']);
        }
        else
        {
            $this->setRedirectURL($data['url'])->setUsername($data['username'])->setPassword($data['password']);
        }
    }
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }
     public function getError()
    {
        return $this->error;
    }
    /**
     * @return mixed
     */
    public function getRedirectURL()
    {
        return $this->redirectURL;
    }

    /*
     * @param mixed $redirectURL
     */
    public function setRedirectURL($redirectURL)
    {
        $this->redirectURL = $redirectURL;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /*
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /*
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

}
