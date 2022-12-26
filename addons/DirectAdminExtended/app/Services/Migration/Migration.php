<?php
/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 2018-10-05
 * Time: 15:28
 */

namespace ModulesGarden\DirectAdminExtended\App\Services\Migration;


use ModulesGarden\DirectAdminExtended\App\Services\Migration\Helpers\Addon;
use ModulesGarden\DirectAdminExtended\App\Services\Migration\Helpers\Product;

class Migration
{
        public function __construct()
        {

        }

        public function run()
        {
            $this->migrateProductsSettings();
            $this->migrateAddonSettings();
        }

        private function migrateProductsSettings()
        {
            $helper = new Product();
            $helper->run();
        }

        private function migrateAddonSettings()
        {
            $helper = new Addon();
            $helper->run();
        }

}