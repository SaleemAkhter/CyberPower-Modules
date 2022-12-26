<?php

namespace ModulesGarden\WordpressManager\Core\UI\Traits;

/**
 * HideButtonByColumnValue related functions
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait ShowButtonByColumnValue
{
    protected $hideByColumnValue = false;
    protected $hideColumnName = null;
    protected $hideColumnValue = null;
    
    public function setShowByColumnValue($hideColumnName, $hideColumnValue)
    {
        if (is_string($hideColumnName))
        {
            $this->hideColumnValue   = $hideColumnValue;
            $this->hideColumnName    = $hideColumnName;
            $this->hideByColumnValue = true;
        }
        
        return $this;
    }
    
    public function unsetShowByColumnValue()
    {
        $this->hideColumnValue   = null;
        $this->hideColumnName    = null;
        $this->hideByColumnValue = false;

        return $this;
    }
    
    public function isShowByColumnValue()
    {
        return $this->hideByColumnValue;
    }
    
    public function getShowColumnName()
    {
        return $this->hideColumnName;
    }
    
    public function getShowByColumnValue()
    {
        return $this->hideColumnValue;
    }
    
    public function isShowColumnValueString()
    {
        return is_string($this->hideColumnValue);
    }    
}
