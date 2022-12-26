<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs;

use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;
use ModulesGarden\Servers\DirectAdminExtended\Core\DependencyInjection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\Context;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;

/**
 * Description of Conteiner
 *
 * @author inbs
 */
class GroupedFormContainer extends Container
{
    use Traits\MainContainerElements;

    protected $name                = 'groupedFormContainer';
    protected $id                  = 'groupedFormContainer';
    protected $defaultTemplateName = 'groupedFormContainer';
    protected $templateName        = 'groupedFormContainer';
    protected $data                = [];
    protected $vueInstanceName   = null;

    public function __construct($baseId = null)
    {
        parent::__construct($baseId);

        $this->prepareElemnentsContainers();
    }

    public function addElement($element, $containerName = null)
    {
        if (is_string($element))
        {
            $element = DependencyInjection::create($element);
        }

        $container = $this->getElementContainerName($containerName);
        if (!$container)
        {
            return $this;
        }

        $id = $element->getId();
        if (!isset($this->{$container}[$id]))
        {
            $element->setMainContainer($this);
            $this->{$container}[$id] = $element;
            if ($element instanceof \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface)
            {
                $this->ajaxElements[] = &$this->{$container}[$id];
            }
            if ($element->isVueComponent())
            {
                $this->vueComponents[$element->getTemplateName()] = &$this->{$container}[$id];
            }
        }

        return $this;
    }

    public function getVueInstanceName()
    {
        if ($this->vueInstanceName === null)
        {
            $randomGen = new \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\RandomStringGenerator(32);
            $this->vueInstanceName = $randomGen->genRandomString('mc');
        }

        return $this->vueInstanceName;
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
        $this->loadDefaultNavbars();

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
                if ($response instanceof \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ResponseInterface)
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

    public function getVueComponentsJs()
    {
        $vJsBody = '';
        foreach ($this->vueComponents as $vElem)
        {
            $vJsBody .= $vElem->getVueComponentsJs();
        }

        return $vJsBody;
    }

    public function getDefaultVueComponentsJs()
    {
        if ($this->defaultComponentsJs === null)
        {
            $this->loadDefaultVueComponentsJs();
        }

        return $this->defaultComponentsJs;
    }

    protected function loadDefaultVueComponentsJs()
    {
        $componentsPath = str_replace(DS . 'ui' . DS, DS . 'assets' . DS . 'js' . DS . 'defaultComponents' . DS, $this->getDefaultTemplateDir());
        $content = scandir($componentsPath);
        $this->defaultComponentsJs = '';
        if ($content)
        {
            foreach ($content as $file)
            {
                $fileInfo = pathinfo($componentsPath . $file);
                if ($fileInfo['extension'] === 'js')
                {
                    $jsContent = file_get_contents($componentsPath . $file);
                    $this->defaultComponentsJs .= $jsContent ? $jsContent : '';
                }
            }
        }

        return $this;
    }
}
