<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Forms\DataProviders;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\FormDataProviderInterface;

/**
 * BaseDataProvider - form controler witch CRUD implementation
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
abstract class BaseDataProvider implements FormDataProviderInterface
{

    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\RequestObjectHandler;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\RequestFormDataHandler;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\WhmcsParams;

    protected $data         = [];
    protected $loaded       = false;
    protected $disabledList = [];

    public function __construct()
    {
        $this->loadFormDataFromRequest();
    }

    public function create()
    {
        //to be overwritten if needed
    }

    abstract public function read();

    abstract public function update();

    public function delete()
    {
        //to be overwritten if needed
    }

    public function getValueById($id)
    {
        $this->initData();

        if ($this->data[$id] || $this->data[$id] === 0)
        {
            return $this->data[$id];
        }

        return null;
    }

    public function getData()
    {
        $this->initData();

        return $this->data;
    }

    public function isDisabledById($id)
    {
        $this->initData();

        if (in_array($id, $this->disabledList))
        {
            return true;
        }

        return false;
    }

    protected function initData()
    {
        if ($this->loaded === false)
        {
            $this->read();
            $this->loaded = true;
        }
    }

    protected function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    protected function setDisabled($id)
    {
        if (!in_array($id, $this->disabledList))
        {
            $this->disabledList[] = $id;
        }
    }

    protected function removeFromDisabled($id)
    {
        if (in_array($id, $this->disabledList))
        {
            $key = array_search($id, $this->disabledList[]);
            if ($key)
            {
                unset($this->disabledList[$key]);
            }
        }
    }

}
