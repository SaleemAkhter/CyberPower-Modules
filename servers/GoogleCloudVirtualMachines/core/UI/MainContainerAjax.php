<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Http\Request;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\DependencyInjection;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Builder\Config;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Builder\Context;

/**
 * Description of Conteiner
 *
 * @author inbs
 */
class MainContainerAjax extends MainContainer
{
    protected $namespaceAjax;
    
    public function __construct($baseId = null)
    {
        $this->namespace = str_replace('\\', '_', get_class($this));

        $this->initIds($baseId);

        $index = \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Helper\sl('request')->get('index');

        if ($index && $index != '')
        {
            $this->index = $index;
        }
    }
    
    public function setNamespaceAjax($namespaceAjax)
    {
        $this->namespaceAjax = $namespaceAjax;
        
        return $this;
    }

    public function addElement($element)
    {
        if (is_string($element))
        {
            $element = DependencyInjection::create($element);
        }
        $element->setIndex($this->index);

        $id = $element->getId();
        
        if (!isset($this->ajaxElements[$id]))
        {
            $element->setMainContainer($this);
            if ($element instanceof \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AjaxElementInterface)
            {
                $this->ajaxElements[$id] = &$element;
            }

            if ($element->isVueComponent())
            {
                $this->vueComponents[$element->getTemplateName()] = &$element;
            }
        }

        return $this;
    }

    public function addAjaxElement(\ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AjaxElementInterface &$element)
    {
        /**
         * @var Context $element
         */
        $this->ajaxElements[$element->getId()] = &$element;
    }

    public function addVueComponent(&$element)
    {
        /**
         * @var Context $element
         */
        if ($element->isVueComponent())
        {
            $this->vueComponents[$element->getTemplateName()] = &$element;
        }
    }

    public function valicateACL($isAdmin)
    {
        foreach ($this->elements as $id => &$element)
        {
            /**
             * @var Context $element
             */
            if($element->setIsAdminAcl($isAdmin)->validateElement($element) === false)
            {
                unset($this->elements[$id]);
                Helper\sl('errorManager')->addError(__CLASS__, 'There is no implemented interface for the widget "' . get_class($element) . '".');
            }
        }

        foreach ($this->ajaxElements as $id => &$element)
        {
            /**
             * @var Context $element
             */
            if($element->setIsAdminAcl($isAdmin)->validateElement($element) === false)
            {
                unset($this->ajaxElements[$id]);
                Helper\sl('errorManager')->addError(__CLASS__, 'There is no implemented interface for the widget "' . get_class($element) . '".');
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

    public function getHtml()
    {
        if ($this->html === '')
        {
            $this->buildHtml();
        }

        return $this->html;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getHtml();
    }

    public function getAjaxResponse()
    {
        $request = Request::build();

        foreach ($this->ajaxElements as $aElem)
        {
            /**
             * @var Context $aElem
             */
            if ($this->namespaceAjax === $aElem->getNamespace())
            {
                $response = $aElem->returnAjaxData();
                if ($response instanceof \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\ResponseInterface)
                {
                    return $response->getFormatedResponse();
                }

                return $response;
            }
        }
    }

    public function getVueComponents()
    {
        $vBody = '';
        foreach ($this->vueComponents as $vElem)
        {
            /**
             * @var Context $vElem
             */
            $vBody .= $vElem->getVueComponents();
        }

        return $vBody;
    }

    public function getAjaxElems()
    {
        return $this->ajaxElements;
    }

    public function getVueComponentsJs()
    {
        $vJsBody = '';
        foreach ($this->vueComponents as $vElem)
        {
            $vJsBody .= $vElem->getVueComponentsJs();
        }

        return $vJsBody;
    }
}
