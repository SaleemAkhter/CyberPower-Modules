<?php

use \ModulesGarden\Servers\DirectAdminExtended\Core\DependencyInjection;
use \ModulesGarden\Servers\DirectAdminExtended\Core\Http\View\MainMenu;
use \ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;

use WHMCS\View\Menu\Item as MenuItem;

$hookManager->register(
    function ($vars)
    {

        // $unreadmessagecount='';
        // $resellerImpersonated=false;
        // if(isset($_SESSION['resellerloginas'],$_SESSION['resellerloginas'][$this->product->id])){
        //     $resellerImpersonated=true;
        // }
        // if(isset($_SESSION['unreadmessagecount'])){
        //     $unreadmessagecount=$_SESSION['unreadmessagecount'];

        // }
    },
    1001
);
