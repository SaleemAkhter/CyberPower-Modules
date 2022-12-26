<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Hook\Interfaces;

interface InternalHook
{
    public function __construct($params);

    public function execute();
}
