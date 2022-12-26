<?php

namespace ModulesGarden\Servers\VultrVps\Core\Tasks;

use ModulesGarden\Servers\VultrVps\Core\Models\Tasks\Tasks;

class Manager
{
    public function __construct()
    {

    }

    /**
     *
     */
    public function run()
    {
        foreach($this->getTasks() as $task)
        {

        }
    }

    /**
     * @TODO - all pagination
     * @return mixed
     */
    protected function getTasks()
    {
        return Tasks::get();
    }
}