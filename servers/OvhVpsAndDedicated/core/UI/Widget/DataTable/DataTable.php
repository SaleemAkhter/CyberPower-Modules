<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\DataTable\DataProviders;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\DependencyInjection\DependencyInjection;

/**
 * Description of Service
 *
 * @author inbs
 */
class DataTable extends BaseContainer implements \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AjaxElementInterface
{
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\DatatableActionButtons;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\DatatableMassActionButtons;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\VSortable;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\TitleButtons;
    use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Traits\TableLength;
    
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

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-datatable';

    protected $searchBarButtonsVisible = 1;
    protected $dropdawnWrapper = null;

    protected $elementsContainers = ['elements', 'buttons'];

    public function __construct($baseId = null)
    {
        parent::__construct($baseId);

        $this->_construct();
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

        $returnTemplate = self::getVueComponents();

        return (new ResponseTemplates\RawDataJsonResponse(['recordsSet' => $this->recordsSet, 'template' => $returnTemplate,
            'registrations' => self::getVueComponentsRegistrations()]))->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds($this->refreshActionIds);
        
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

    public function setData(\ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\DataSetInterface $data)
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

    public function getSearchBarButtonsVisible()
    {
        return $this->searchBarButtonsVisible;
    }

    public function addButton($button)
    {
        if ($this->getButtonsCount() < $this->getSearchBarButtonsVisible())
        {
            parent::addButton($button);

            return $this;
        }

        $this->addButtonToDropdawn($button);

        return $this;
    }

    public function addButtonToDropdawn($button)
    {
        if ($this->dropdawnWrapper === null)
        {
            $this->dropdawnWrapper = DependencyInjection::call(\ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdown::class);
            $this->registerMainContainerAdditions($this->dropdawnWrapper);
        }

        $this->dropdawnWrapper->addButton($button);

        return $this;
    }

    public function hasDropdawnButton()
    {
        return $this->dropdawnWrapper !== null;
    }

    public function getDropdawnButtonHtml()
    {
        return $this->dropdawnWrapper->getHtml();
    }
}
