<?php

namespace ModulesGarden\WordpressManager\App\Database;

use Illuminate\Database\Capsule\Manager as Capsule;
use ModulesGarden\WordpressManager\Core\ModuleConstants;

class PdoConnection
{
    public static function getCapsule()
    {
        $dir = ModuleConstants::getFullPathWhmcs();
        $file = $dir .  DS . "configuration.php";
        include $file;
        $capsule = new \WHMCS\Database\Capsule();
        $config = array( "driver" => "mysql", "host" => $db_host, "database" => $db_name, "username" => $db_username, "password" => $db_password, "charset" => "utf8", "collation" => "utf8_unicode_ci", "prefix" => "");
        $capsule->addConnection($config);
        $capsule->setFetchMode(\PDO::FETCH_OBJ);
        $capsule->setEventDispatcher(new \Illuminate\Events\Dispatcher(new \Illuminate\Container\Container()));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        return $capsule;
    }

}