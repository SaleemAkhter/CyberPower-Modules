<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\MainContainer;
use function ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\sl;

/**
 * Description of Context
 *
 * @author inbs
 */
class Context
{

    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Title;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\HtmlElements;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Template;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Searchable;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Buttons;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Icon;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\VueComponent;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\HtmlAttributes;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\ACL;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\CallBackFunction;
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\Alerts;    
    use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Traits\WhmcsParams;  
    
    protected $elements            = [];
    protected $html                = '';
    protected $customTplVars       = [];
    protected $mainContainer       = null;
    protected $namespace           = '';
    public static $findItemContext = false;

    public function __construct($baseId = null)
    {
        $this->namespace = str_replace('\\', '_', get_class($this));
        $this->initIds($baseId);

        $this->prepareDefaultHtmlElements();
        $this->loadTemplateVars();
    }

    /**
     * @return string
     */
    public function getElements()
    {
        return $this->elements;
    }

    public function getElementById($id)
    {
        return $this->elements[$id];
    }

    public function insertElementById($id)
    {
        if (isset($this->elements[$id]))
        {
            return $this->elements[$id]->getHtml();
        }

        return '';
    }

    /**
     * @return string
     */
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

    protected function buildHtml()
    {
        $this->html = self::generate($this);
    }

    public function getCustomTplVars()
    {
        return $this->customTplVars;
    }

    public function getCustomTplVarsValue($varName)
    {
        return $this->customTplVars[$varName];
    }

    /**
     * @param \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\Context $object
     * @return string
     */
    public static function generate(Context $object)
    {
        $tpl = $object->getTemplateName();

        $vars = [
            'title'          => $object->getTitle(),
            'class'          => $object->getClasses(),
            'name'           => $object->getName(),
            'elementId'      => $object->getId(),
            'htmlAttributes' => $object->getHtmlAttributes(),
            'elements'       => $object->getElements(),
            'scriptHtml'     => $object->getScriptHtml(),
            'customTplVars'  => $object->getCustomTplVars(),
            'rawObject'      => $object,
            'namespace'      => $object->getNamespace(),
            'isDebug'        => (bool)(int)sl('configurationAddon')->getConfig('debug', '0')
        ];
        $lang = ServiceLocator::call('lang');

        $lang->stagCurrentContext('builder' . $object->getName());
        $lang->addToContext(lcfirst($object->getName()));
        $return = ServiceLocator::call('smarty')->setLang($lang)->view($tpl, $vars, $object->getTemplateDir());
        if ($object->hasVueComponents() && file_exists($object->getTemplateDir() . str_replace('.tpl', '', $tpl) . '_components.tpl'))
        {
            $vueComponents = ServiceLocator::call('smarty')->setLang($lang)->view(str_replace('.tpl', '', $tpl) . '_components', $vars, $object->getTemplateDir());
            $object->setVueComponents($vueComponents, $object->getId());
        }
        $lang->unstagContext('builder' . $object->getName());

        return $return;
    }

    public function setMainContainer(MainContainer &$mainContainer)
    {
        $this->mainContainer = &$mainContainer;
        foreach ($this->elements as $element)
        {
            $element->setMainContainer($mainContainer);
        }
        
        if (self::$findItemContext === false)
        {
            $this->initContent();
        }
        
        return $this;
    }

    public function initContent()
    {
        
    }

    public function getNamespace()
    {
        return $this->namespace;
    }
}
