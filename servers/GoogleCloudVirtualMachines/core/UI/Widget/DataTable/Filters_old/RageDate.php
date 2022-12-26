<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\DataTable\Filters;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\DataTable\Filters;

/**
 * Description of RageDate
 *
 * @author inbs
 */
class RageDate extends Filters
{

    protected function loadFilter()
    {
        $records = $this->records;
        $from    = (isset($this->data['from'])) ? strtotime($this->data['from']) : null;
        $to      = (isset($this->data['to'])) ? strtotime($this->data['to']) : null;
        foreach ($records as $key => $item)
        {
            if (isset($data[$this->name]))
            {
                $value = strtotime($item[$this->name]);
                if (isset($from) && ($value < $from) || isset($to) && ($value > $to))
                {
                    unset($this->records[$key]);
                }
            }
        }
    }
}
