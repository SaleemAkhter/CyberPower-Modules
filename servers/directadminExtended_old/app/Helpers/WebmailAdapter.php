<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;



class WebmailAdapter
{

    private static $availableClient = [
        1  => 'roundcube',
        ];

    public static function getClient($webmailClientID){

        if(array_key_exists($webmailClientID, self::$availableClient)){
            return self::$availableClient[$webmailClientID];
        }

        return self::$availableClient[1];
    }


}
