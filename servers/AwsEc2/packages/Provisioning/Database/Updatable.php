<?php


namespace ModulesGarden\Servers\AwsEc2\Packages\Provisioning\Database;


interface Updatable
{
    public function update();
    public function prepareForConstraints();
}