<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces;

/**
 * Description of InstallerProvider
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
interface InstallerProvider
{

    public function login();

    public function setLoger($loger);

    public function setGet($get);

    public function setPost($post);
    
    public function get($get);
    
    public function post($get, $post = [], $filter = true);

    public function sendRequest();

    public function getResponse();
}
