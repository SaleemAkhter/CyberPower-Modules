<?php

namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\DataSetInterface;

class DataSet implements DataSetInterface
{
    public $offset         = 0;
    public $fullDataLenght = 0;
    public $records        = [];

    public function getOffset()
    {
        return $this->offset;
    }

    public function getRecords()
    {
        return $this->records;
    }

    public function getLenght()
    {
        return count($this->records);
    }

    public function getFullLenght()
    {
        return $this->fullDataLenght;
    }
}
