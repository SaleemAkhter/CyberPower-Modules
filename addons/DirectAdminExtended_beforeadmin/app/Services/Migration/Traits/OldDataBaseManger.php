<?php
/**
 * Created by PhpStorm.
 * User: Mateusz
 * Date: 06.10.2018
 * Time: 10:59
 */

namespace ModulesGarden\DirectAdminExtended\App\Services\Migration\Traits;

use \Illuminate\Database\Capsule\Manager as Capsule;

trait OldDataBaseManger
{
    protected function removeTableAfterMigrate(){
        Capsule::Schema()->drop($this->oldTable);
    }

    /**
     * @return mixed
     */
    protected function checkOldConfiguration(){
        return Capsule::Schema()->hasTable($this->oldTable);
    }

    /**
     * @return mixed
     */
    protected function getOldSettings(){
        $settings = Capsule::table($this->oldTable)->get();
        if(is_null($settings)){
            return [];
        }
        return $settings;

    }
}