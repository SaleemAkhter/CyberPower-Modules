<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\Request;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\DependencyInjection;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\Context;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;

/**
 * Description of Conteiner
 *
 * @author inbs
 */
class MainContainer extends Container
{
    protected $name                = 'mainContainer';
    protected $id                  = 'mainContainer';
    protected $defaultTemplateName = 'mainContainer';
    protected $templateName        = 'mainContainer';
    protected $data                = [];
    protected $ajaxElements        = [];
    protected $vueComponents       = [];

    public function addElement($element)
    {
        if (is_string($element))
        {
            $element = DependencyInjection::create($element);
        }

        $id = $element->getId();
        if (!isset($this->elements[$id]))
        {
            $element->setWhmcsParams($this->whmcsParams);
            $element->setMainContainer($this);
            $this->elements[$id] = $element;
            if ($element instanceof \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface)
            {
                $this->ajaxElements[] = &$this->elements[$id];
            }

            if ($element->hasVueComponents())
            {
                $this->vueComponents[$element->getTemplateName()] = &$this->elements[$id];
            }
        }

        return $this;
    }

    public function addAjaxElement(\ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface &$element)
    {
        $this->ajaxElements[] = &$element;
    }

    public function addVueComponent(\ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface &$element)
    {
        $this->vueComponents[$element->getTemplateName()] = &$element;
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
            if ($request->get('loadData', false) === $aElem->getId())
            {
                $response = $aElem->returnAjaxData();

                if ($response instanceof \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ResponseInterface)
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
            $vBody .= $vElem->getVueComponents();
        }

        return $vBody;
    }

    public function getAjaxElems()
    {
        return $this->ajaxElements;
    }
}
