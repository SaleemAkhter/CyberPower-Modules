<?php

namespace ModulesGarden\WordpressManager\App\Interfaces;

interface MaintenanceInterface
{
    public function checkStatus();

    public function disable();

    public function enable();
}
