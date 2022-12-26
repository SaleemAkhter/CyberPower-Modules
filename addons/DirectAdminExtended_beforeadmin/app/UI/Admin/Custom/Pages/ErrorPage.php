<?php
namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Custom\Pages;

use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\DirectAdminExtended\Core\UI\Builder\BaseContainer;

/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 04.04.2019
 * Time: 08:49
 */

class ErrorPage extends BaseContainer implements AdminArea
{
    protected $id = 'errorPage';
    protected $errorMessage;

    public function initContent()
    {

    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }

}