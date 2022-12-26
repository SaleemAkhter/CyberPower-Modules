<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Server;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Models\Ovh\ServerSettings;

/**
 * Class Tables
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Tables
{
    private function createTable()
    {
        $productConfig = new ServerSettings();
        $productConfig->createTableIfNotExists();
    }

    private function updateTable()
    {

    }

    public function run()
    {
        $this->createTable();
        $this->updateTable();
    }
}