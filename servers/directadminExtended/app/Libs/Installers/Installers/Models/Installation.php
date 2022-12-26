<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models;

class Installation extends AbstractModel
{
    protected $domain;
    protected $directory;
    protected $application;
    //Installation Fields
    protected $fields     = [];
    //Remove
    protected $removeDir;
    protected $removeDb;
    protected $removeDatadir;
    protected $removeWwwdir;
    protected $removeins;
    protected $version;
    protected $overwriteExisting = false;
    protected $protocol;

    public function fillFieldsData(array $data)
    {
        $this->fillBasicData($data);
        foreach ($this->getFields() as $field)
        {
            if (!empty($data[$field->getName()]))
            {
                $value = $data[$field->getName()];
                $this->convertCheckboxValue($value);
                $field->setValue($value);
            }
        }
    } 

    public function fillBasicData(array $data)
    {
        foreach ($data as $key => $value)
        {
            if (property_exists($this, $key))
            {
                $this->$key = $value;
            }
        }
    }

    public function getFieldsParamsArray()
    {
        $array = [];
        foreach ($this->getFields() as $field)
        {
            $array[$field->getName()] = $field->getValue();
        }

        return $array;
    }

    public function addField(Field $field)
    {
        $this->fields[] = $field;

        return $this;
    }

    private function convertCheckboxValue(&$value)
    {
        if ($value == 'on')
        {
            $value = 1;
        }
        elseif ($value == 'off')
        {
            $value = 0;
        }
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getDirectory()
    {
        return $this->directory;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;
        return $this;
    }

    public function getProtocol()
    {
        return $this->protocol;
    }

    public function getRemoveDir()
    {
        return $this->removeDir;
    }

    public function getRemoveDb()
    {
        return $this->removeDb;
    }

    public function getRemoveDatadir()
    {
        return $this->removeDatadir;
    }

    public function getRemoveWwwdir()
    {
        return $this->removeWwwdir;
    }

    public function getRemoveins()
    {
        return $this->removeins;
    }

    public function setRemoveDir($removeDir)
    {
        $this->removeDir = $removeDir;
        return $this;
    }

    public function setRemoveDb($removeDb)
    {
        $this->removeDb = $removeDb;
        return $this;
    }

    public function setRemoveDatadir($removeDatadir)
    {
        $this->removeDatadir = $removeDatadir;
        return $this;
    }

    public function setRemoveWwwdir($removeWwwdir)
    {
        $this->removeWwwdir = $removeWwwdir;
        return $this;
    }

    public function setRemoveins($removeins)
    {
        $this->removeins = $removeins;
        return $this;
    }
    
    public function getApplication()
    {
        return $this->application;
    }

    public function setApplication($application)
    {
        $this->application = $application;
        return $this;
    }
    
    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }
    
    public function getOverwriteExisting() {
        return $this->overwriteExisting;
    }

    public function setOverwriteExisting($overwriteExisting) {
        $this->overwriteExisting = $overwriteExisting;
        return $this;
    }
    
}
