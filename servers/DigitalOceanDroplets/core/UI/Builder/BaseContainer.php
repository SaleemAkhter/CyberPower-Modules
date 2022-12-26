<?php
namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\DependencyInjection;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;

/**
 * Base Container element. Every UI element should extend this.
 *
 * @author inbs
 */
class BaseContainer extends Context
{
    protected $data = [];

    public function addElement($element)
    {
        if (is_string($element))
        {
            $element = DependencyInjection::create($element);
        }

        $id = $element->getId();
        if ($this->isId(ServiceLocator::call('request')->get('loadData')))
        {
            self::$findItemContext = true;
        }
        if (!isset($this->elements[$id]))
        {
            $element->setWhmcsParams($this->whmcsParams);
            $element->setIndex(ServiceLocator::call('request')->get('index'));
            $element->setMainContainer($this->mainContainer);

            $this->elements[$id] = $element;
            if ($element instanceof \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface)
            {
                $this->mainContainer->addAjaxElement($this->elements[$id]);
            }

            if ($element->hasVueComponents())
            {
                $this->mainContainer->addVueComponent($this->elements[$id]);
            }
        }

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data = [])
    {
        $this->data = $data;
        $this->updateData();
        
        return $this;
    }

    protected function updateData()
    {
        foreach ($this->data as $key => $value)
        {
            if (property_exists($this, $key))
            {
                $this->$key = $value;
            }
        }
        $this->data = [];
        
        return $this;
    }
}
