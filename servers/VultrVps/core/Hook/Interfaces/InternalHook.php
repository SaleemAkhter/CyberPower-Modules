<?php

namespace ModulesGarden\Servers\VultrVps\Core\Hook\Interfaces;

interface InternalHook
{
    public function __construct($params);

    public function execute();
}
