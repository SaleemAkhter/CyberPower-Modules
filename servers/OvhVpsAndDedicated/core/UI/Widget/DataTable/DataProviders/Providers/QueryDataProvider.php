<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\Providers;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\DataQuery;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\RawDataQuery;

/**
 *
 */
class QueryDataProvider extends \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders\DataProvider
{
    private $query   = null;
    protected $unset = false;

    public function unsetSort()
    {
        $this->unset = true;
        return $this;
    }

    public function setData($query, $params = [])
    {
        $this->query = ($query instanceof \Illuminate\Database\Query\Builder) ? new DataQuery($query) : new RawDataQuery($query, $params);
        if ($this->unset === false)
        {
            $this->query->setSorting($this->orderColumn, $this->orderDir);
        }

        $this->query->setLimit($this->limit);
        $this->query->setOffset($this->offset);
        $this->query->setSearch($this->toSearch);
        
        return $this;
    }

    public function getData(array $avalibleCols = [])
    {
        return $this->query->getData($avalibleCols);
    }

    public function setDefaultSorting($column, $direction)
    {
        if ((!$this->request->query->get('iSortCol_0') && !$this->request->query->get('sSortDir_0')) && $this->unset === false)
        {
            $this->setSortBy($column);
            $this->setSortDir($direction);
            if ($this->query)
            {
                $this->query->setSorting($column, $direction);
            }
        }

        return $this;
    }
}
