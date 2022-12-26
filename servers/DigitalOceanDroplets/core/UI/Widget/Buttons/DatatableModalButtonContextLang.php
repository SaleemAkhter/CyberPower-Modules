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
class DatatableModalButtonContextLang extends BaseContainer implements AjaxElementInterface
{
    protected $id             = 'datatableModalButtonContextLang';
    protected $class          = ['lu-btn lu-btn-circle lu-btn-outline lu-btn-inverse lu-btn-success lu-btn-icon-only'];
    protected $icon           = 'fa fa-plus';
    protected $title          = 'datatableModalButtonContextLang';
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
        $this->setModal(new ExampleModal());
    }

    public function setModal($modal)
    {
        $modal->setMainContainer($this->mainContainer);
        $this->modal = $modal;
        if ($modal instanceof \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface)
        {
            $this->mainContainer->addAjaxElement($modal);
        }
    }
}
