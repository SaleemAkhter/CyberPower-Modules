<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Custom\Task;

abstract class TaskAbstract
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR   = 'error';
    
    protected $name          = 'Task';
    protected $status        = self::STATUS_SUCCESS;
    protected $data          = [];
    protected $executeMethod = null;
    protected $errorLogging  = false;
    protected $errorMessage  = null;

    public function __construct($data = [], $name = null)
    {
        $this->data          = $data;
        $this->executeMethod = lcfirst((new \ReflectionClass($this))->getShortName());

        if ($name !== null)
        {
            $this->name = $name;
        }
    }

    public function run()
    {
        if (!method_exists($this, $this->executeMethod))
        {
            $this->logError(sprintf('Task : %s method does not exists', $this->executeMethod));
            return;
        }

        try
        {
            $this->{$this->executeMethod}();
        }
        catch (\Exception $ex)
        {
            if ($this->errorLogging === true)
            {
                $this->logError($ex->getMessage());
            }
        }

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function enableErrorLogging()
    {
        $this->errorLogging = true;

        return $this;
    }

    public function log($message)
    {
        \logModuleCall('DirectAdminExtended', ucfirst($this->executeMethod), 'Status : ' . $this->status, $message);

        return $this;
    }
    
    public function logError($message, $request = null)
    {
        \logModuleCall('DirectAdminExtended', ucfirst($this->executeMethod), 'Status : ' . self::STATUS_ERROR . $request !== null ? PHP_EOL . $request : '', $message);

        return $this;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }
}
