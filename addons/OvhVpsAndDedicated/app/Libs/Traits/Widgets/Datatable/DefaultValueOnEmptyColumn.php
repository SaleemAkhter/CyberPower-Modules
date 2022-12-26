<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\Libs\Traits\Widgets\Datatable;

/**
 * Class DefaultValueOnEmptyColumn
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
trait DefaultValueOnEmptyColumn
{
    protected $defaultColumnValue = '-';

    protected $columnsToDefaultValue = [];

    protected $everyColumnDefaultValue = false;

    /**
     * Overrided method form datatable
     *
     * @return array
     */
    protected function getReplacementFunctions()
    {
        $replacementFunctions = [];
        foreach ($this->columns as $column)
        {
            if (method_exists($this, 'replaceField' . ucfirst($column->name)))
            {
                $replacementFunctions[$column->name] = 'replaceField' . ucfirst($column->name);
            }
            elseif($this->everyColumnDefaultValue ||  in_array($column->name, $this->columnsToDefaultValue))
            {
                $replacementFunctions[$column->name] = 'replaceFieldDefaultColumnValue';
            }
        }

        return $replacementFunctions;
    }

    protected function setColumnsNameToDefaultValue(array $columnsToDefaultValue)
    {
        $this->columnsToDefaultValue = $columnsToDefaultValue;

        return $this;
    }

    protected function addColumnToDefaultValue($columnName)
    {
        $this->columnsToDefaultValue[] = $columnName;

        return $this;
    }

    protected function replaceFieldDefaultColumnValue($colName, $row)
    {
        if(trim($row[$colName]) == '')
        {
            return $this->defaultColumnValue;
        }
        return $row[$colName];
    }

    protected function setDefaultValueForEveryColumn($bool)
    {
        $this->everyColumnDefaultValue = (bool) $bool;

        return $this;
    }

    protected function isDefaultValueForEveryColumn()
    {
        return $this->everyColumnDefaultValue;
    }
}