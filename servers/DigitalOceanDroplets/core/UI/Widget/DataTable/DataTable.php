<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\DataTable\DataProviders;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates;

/**
 * Description of Service
 *
 * @author inbs
 */
class DataTable extends BaseContainer implements \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface
{
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\DatatableActionButtons;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\DatatableMassActionButtons;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\VSortable;
    use \ModulesGarden\Servers\DigitalOceanDroplets\App\Traits\DataTableDropdownButtons;
    use \ModulesGarden\Servers\DigitalOceanDroplets\App\Traits\DataTableDropdownActionButtons;
    
    protected $name                = 'dataTable';
    protected $key                 = 'id';
    protected $type                = ['id' => 'int'];
    protected $recordsSet          = [];
    protected $sort                = [];
    protected $columns             = [];
    protected $isActive            = true;
    protected $html                = '';
    protected $config              = [];
    protected $dataProvider        = null;
    protected $searchable          = true;
    protected $vueComponent        = true;

    public function __construct($baseId = null)
    {
        parent::__construct($baseId);

        $this->_construct();
        
        $this->customTplVars['additionalData'] = [];
    }

    protected function _construct()
    {
        $this->loadHtml();
        $this->customTplVars['columns'] = $this->columns;
        $this->customTplVars['jsDrawFunctions'] = $this->getJsDrawFunctions();
    }

    protected function getJsDrawFunctions()
    {
        $functionsList = [];
        foreach ($this->columns as $column)
        {
            if ($column->getCustomJsDrawFunction() !== null)
            {
                $functionsList[$column->name] = $column->getCustomJsDrawFunction();
            }
        }

        return $functionsList;
    }

    public function returnAjaxData()
    {
        $this->loadHtml();
        $this->loadData($this->columns);

        $this->parseDataRecords();
        
        $this->recordsSet->setAdditionalData($this->customTplVars['additionalData']);
        
        return (new ResponseTemplates\RawDataJsonResponse($this->recordsSet))->setCallBackFunction($this->callBackFunction);
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    protected function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    protected function setStatus($status)
    {
        $this->isActive = $status;
        return $this;
    }

    protected function addColumn(Column $column)
    {
        if (!array_key_exists($column->name, $this->columns))
        {
            $this->columns[$column->name] = $column;
        }

        return $this;
    }

    public function setData(\ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\DataSetInterface $data)
    {
        $this->recordsSet = $data;
        
        return $this;
    }

    protected function loadData()
    {
        //do nothing
    }

    protected function loadHtml()
    {
        //do nothing
    }

    protected function getCount()
    {
        return count($this->recordsSet->records);
    }

    protected function getRecords()
    {
        return $this->recordsSet;
    }

    protected function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }

    public function setDataProvider(DataProviders\DataProvider $dataProv)
    {
        $this->dataProvider = $dataProv;

        if (!$this->columns)
        {
            $this->loadHtml();
        }

        $this->setData($this->dataProvider->getData($this->columns));
    }
    
    protected function parseDataRecords()
    {
        $replacementFunctions = $this->getReplacementFunctions();
        if (count($replacementFunctions) === 0)
        {
            return false;
        }

        foreach ($this->recordsSet->records as $key => $row)
        {
            $this->recordsSet->records[$key] = $this->replaceRowData($row, $replacementFunctions);
        }
    }

    protected function replaceRowData($row, $replacementFunctions)
    {
        foreach ($replacementFunctions as $colName => $functionName)
        {
            if (method_exists($this, $functionName))
            {
                $this->setValueForDataRow($row, $colName, $this->{$functionName}($colName, $row));
            }
        }

        return $row;
    }
    
    protected function getReplacementFunctions()
    {
        $replacementFunctions = [];
        foreach ($this->columns as $column)
        {
            if (method_exists($this, 'replaceField' . ucfirst($column->name)))
            {
                $replacementFunctions[$column->name] = 'replaceField' . ucfirst($column->name);
            }
        }
        
        return $replacementFunctions;
    }

    protected function setValueForDataRow(&$row, $colName, $value)
    {
        if (is_array($row))
        {
            $row[$colName] = $value;
            
            return $this;
        }
        
        $row->$colName = $value;

        return $this;        
    }    
    
    public function hasCustomColumnHtml($colName)
    {
        if (method_exists($this, 'customColumnHtml' . ucfirst($colName)))
        {
            return true;
        }    
        
        return false;
    }
    
    public function getCustomColumnHtml($colName)
    {    
        if ($this->hasCustomColumnHtml($colName))
        {
            return $this->{'customColumnHtml' . ucfirst($colName)}();
        }
        
        return false;
    }
}
