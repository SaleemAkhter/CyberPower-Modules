<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Hook\Interfaces;

interface InternalHook
{
    public function __construct($params);

    public function execute();
}
