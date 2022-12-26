<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;


class WordPressManager
{
    /**
     * Replace GET/POST variables in Request class in WordpRess Manager
     * @param array $requestFields
     */
    public static function replaceRequestClass(array $requestFields = [])
    {
        $request = new \ModulesGarden\WordpressManager\Core\Http\Request(
            array_merge($_GET, ['m'=>'WordpressManager'], $requestFields),
            array_merge($_POST, ['m'=>'WordpressManager'], $requestFields),
            [],
            $_COOKIE,
            $_FILES,
            $_SERVER
        );

        return \ModulesGarden\WordpressManager\Core\DependencyInjection\Container::getInstance()->instance('request', $request);
    }
}