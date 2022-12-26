<?php

namespace ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\Filters;

use ModulesGarden\Servers\HetznerVps\Core\UI\Widget\DataTable\Filters;

/**
 * Description of Select
 *
 * @author inbs
 */
class Selectold extends Filters
{

    protected function loadFilter()
    {
        $records = $this->records;
        foreach ($records as $key => $data)
        {
            if (isset($data[$this->name]) && preg_replace('/\s+/', '', $this->data) != "")
            {
                if ((string) $data[$this->name] != (string) $this->data)
                {
                    unset($this->records[$key]);
                }
            }
        }
    }
}
