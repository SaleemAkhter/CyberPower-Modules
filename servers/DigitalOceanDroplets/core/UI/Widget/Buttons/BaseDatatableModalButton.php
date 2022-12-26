<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Builder\BaseContainer;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Modals\ExampleModal;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class BaseDatatableModalButton extends BaseContainer implements AjaxElementInterface
{
    protected $id             = 'baseDatatableModalButton';
    protected $class          = ['lu-btn lu-btn-circle lu-btn-outline lu-btn-inverse lu-btn-success lu-btn-icon-only'];
    protected $icon           = 'fa fa-plus';
    protected $title          = 'baseDatatableModalButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'tooltip',
    ];
    protected $modal          = null;

    public function returnAjaxData()
    {
        return (new ResponseTemplates\HtmlDataJsonResponse($this->modal->getHtml()))->setCallBackFunction($this->callBackFunction);
    }

    public function initContent()
    {
        $this->initLoadModalAction(new ExampleModal());
    }

    public function setModal($modal)
    {
        $modal->setWhmcsParams($this->whmcsParams);
        $modal->setMainContainer($this->mainContainer);
        $this->modal = $modal;
        if ($modal instanceof \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface)
        {
            $this->mainContainer->addAjaxElement($modal);
        }
        
        return $this;
    }

    protected function initLoadModalAction($modal)
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\')';
        $this->setModal($modal);
        
        return $this;
    }
}
