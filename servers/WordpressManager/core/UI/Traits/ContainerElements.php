<?php

namespace ModulesGarden\WordpressManager\Core\UI\Traits;

use \ModulesGarden\WordpressManager\Core\DependencyInjection\DependencyInjection;
use \ModulesGarden\WordpressManager\Core\ServiceLocator;

/**
 * Container Elements related functions
 * Base Container Trait
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
trait ContainerElements
{
    /** 
     * a default container name for all elements in UI components
     * @var type string
     */
    protected $defaultContainer = 'elements';
    
    /* 
     * a list of allowed containers, it this way in order to prevent 
     * an accidental overwrite other variables
     */
    protected $elementsContainers = ['elements'];
    
    /** 
     * an array of elements for every UI component
     * @var type array 
     */
    protected $elements = [];
    
    public function addElement($element, $containerName = null)
    {
        $element = $this->prepareElementInstance($element);
        
        $this->checkElementItemContext();

        $this->addElementToContainer($element, $containerName);
        
        return $this;
    }
    
    protected function prepareElementInstance($element)
    {
        if (is_string($element))
        {
            $element = DependencyInjection::create($element);
        }

        $element->setIndex(ServiceLocator::call('request')->get('index'));

        return $element;
    }
    
    protected function checkElementItemContext()
    {
        if ($this->isIdEqual($this->getRequestValue('loadData')))
        {
            self::$findItemContext = true;
        }        
    }
    
    /** 
     * do not use this function directly, addElement function is the only proper way
     */
    protected function addElementToContainer($element, $containerName = null)
    {
        /** 
         * unique element id
         */
        $id = $element->getId();
        
        $container = $this->getElementContainerName($containerName);
        if (!$container)
        {
            return null;
        }
        
        if (!isset($this->{$container}[$id]))
        {
            $this->{$container}[$id] = $element;

            $this->registerMainContainerAdditions($this->{$container}[$id]);
        }
    }

    protected function registerMainContainerAdditions(&$element)
    {
        $element->setMainContainer($this->mainContainer);

        if ($element instanceof \ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface)
        {
            $this->mainContainer->addAjaxElement($element);
        }

        if ($element->isVueComponent())
        {
            $this->mainContainer->addVueComponent($element);
        }        
    }
    
    /** 
     * determines property name where the element will be added
     * @param type $containerName
     * @return type null|string
     */
    public function getElementContainerName($containerName = null)
    {
        /** 
         * if not set use default container
         */
        if ($containerName === null)
        {
            return $this->defaultContainer;
        }
        
        /** 
         * if invalid value provided
         */
        if (!$containerName || !is_string($containerName) || $containerName === '')
        {
            return null;
        }
        
        /** 
         * container does not exists, or is not on the allowed list
         */
        if (!property_exists($this, $containerName) || !in_array($containerName, $this->elementsContainers))
        {
            return null;
        }
        
        /** 
         * container is not an array
         */
        if (!is_array($this->{$containerName}))
        {
            return null;
        }        
        
        return $containerName;        
    }
    
    public function addNewElementsContainer($containerName = null)
    {
        if (!is_string($containerName) || $containerName === '')
        {
            return $this;
        }
        
        if (!in_array($containerName, $this->elementsContainers))
        {
            $this->elementsContainers[] = $containerName;
        }
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getElements($containerName = null)
    {
        if (!$containerName)
        {
            return $this->elements;
        }
        
        if (in_array($containerName, $this->elementsContainers) && property_exists($this, $containerName))
        {
            return $this->{$containerName};
        }
        
        return [];
    }
    
    public function elementsExists($containerName = null)
    {
        if (!$containerName)
        {
            return count($this->elements) > 0;
        }
        
        if (in_array($containerName, $this->elementsContainers) && property_exists($this, $containerName))
        {
            return count($this->{$containerName}) > 0;
        }
        
        return false;
    }    
    
    public function getElementById($id, $containerName = null)
    {
        if (!$containerName)
        {        
            return $this->elements[$id];
        }
        
        if (in_array($containerName, $this->elementsContainers) && property_exists($this, $containerName))
        {
            return $this->{$containerName}[$id];
        }
        
        return null;
    }

    public function insertElementById($id, $containerName = null)
    {
        if (isset($this->elements[$id]) && !$containerName)
        {
            return $this->elements[$id]->getHtml();
        }

        if (in_array($containerName, $this->elementsContainers) && property_exists($this, $containerName))
        {
            return $this->{$containerName}[$id]->getHtml();
        }        
        
        return '';
    }
    
    public function getElementsContainers()
    {
        return $this->elementsContainers;
    }
}
