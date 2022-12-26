<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;


use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ApplicationProviderApi;

class Wordpress extends ApplicationProviderApi
{
    public function redirect()
    {
        $this->loadApplicationAPI();
        $url = $this->api->loginIntoWordpress($this->getRequestValue('appId'));
        return $url['sign_on_url'];
    }
}