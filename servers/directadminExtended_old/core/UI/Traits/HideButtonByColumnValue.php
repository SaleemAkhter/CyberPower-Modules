<?php


namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits;


trait HideButtonByColumnValue
{
    protected $hideByColumnValue = false;
    protected $hideColumnName = null;
    protected $hideColumnValue = null;

    public function setHideByColumnValue($hideColumnName, $hideColumnValue)
    {
        if (is_string($hideColumnName))
        {
            $this->hideColumnValue   = $hideColumnValue;
            $this->hideColumnName    = $hideColumnName;
            $this->hideByColumnValue = true;
        }

        return $this;
    }

    public function unsetHideByColumnValue()
    {
        $this->hideColumnValue   = null;
        $this->hideColumnName    = null;
        $this->hideByColumnValue = false;

        return $this;
    }

    public function isHideByColumnValue()
    {
        return $this->hideByColumnValue;
    }

    public function getHideColumnName()
    {
        return $this->hideColumnName;
    }

    public function getHideByColumnValue()
    {
        return $this->hideColumnValue;
    }

    public function isHideColumnValueString()
    {
        return is_string($this->hideColumnValue);
    }
}