<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Hook\Interfaces;

interface InternalHook
{
    public function __construct($params);

    public function execute();
}
