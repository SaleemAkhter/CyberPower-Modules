<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;

class Error extends BaseContainer implements ClientArea
{
    protected $id    = 'error';
    protected $name  = 'error';
    protected $title = 'error';
    protected $errorMessage = null;


    public function __construct($errorMessage)
    {
        parent::__construct($this->id);
        $this->errorMessage = $errorMessage;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        
        return $this;
    }
    
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
