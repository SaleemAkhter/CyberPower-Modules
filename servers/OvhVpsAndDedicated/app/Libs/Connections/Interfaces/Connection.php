<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Connections\Interfaces;

/**
 * Class Connection
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
interface Connection
{
    public function setConnection();
    public function getConnection();
}