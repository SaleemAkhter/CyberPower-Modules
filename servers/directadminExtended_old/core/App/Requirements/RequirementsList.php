<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ModulesGarden\Servers\DirectAdminExtended\Core\App\Requirements;

/**
 * Description of Requirements
 *
 * @author INBSX-37H
 */
abstract class RequirementsList
{
    protected $requirementsList = [];

    public function getHandlerInstance()
    {
        $handler = $this->getHandler();
        if (!class_exists($handler))
        {
            return null;
        }

        return new $handler($this->requirementsList);
    }

    abstract function getHandler();
}
