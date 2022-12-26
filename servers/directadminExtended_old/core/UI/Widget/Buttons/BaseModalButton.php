<?php


namespace ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Modals\ExampleModal;

class BaseModalButton extends BaseContainer implements AjaxElementInterface
{
    protected $id             = 'baseModalButton';
    protected $class          = ['lu-btn lu-btn-circle lu-btn-outline lu-btn-inverse lu-btn-default lu-btn-icon-only'];
    protected $icon           = 'fa fa-plus';
    protected $title          = 'baseModalButton';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
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
        $modal->setMainContainer($this->mainContainer);
        $this->modal = $modal;
        if ($modal instanceof \ModulesGarden\Servers\directadminExtended\Core\UI\Interfaces\AjaxElementInterface)
        {
            $this->mainContainer->addAjaxElement($modal->initContent());
        }
    }

    protected function initLoadModalAction($modal)
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\')';
        $this->setModal($modal);
    }
}