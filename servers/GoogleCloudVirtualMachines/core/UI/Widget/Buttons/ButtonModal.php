<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Builder\BaseContainer;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AjaxElementInterface;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Modals\ExampleModal;
use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\ResponseTemplates;

/**
 * base button controller
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ButtonModal extends BaseContainer implements AjaxElementInterface
{
    protected $id             = 'ButtonModal';
    protected $class          = ['lu-btn lu-btn--link lu-btn--plain lu-btn--default'];
    protected $icon           = 'lu-zmdi lu-zmdi-plus';
    protected $title          = 'ButtonModal';
    protected $htmlAttributes = [
        'href'        => 'javascript:;',
        'data-toggle' => 'lu-tooltip',
    ];

    protected $modal          = null;

    public function returnAjaxData()
    {
        $returnHtml = $this->modal->getHtml();
        $returnTemplate = $this->mainContainer->getVueComponents();

        return (new ResponseTemplates\RawDataJsonResponse(['htmlData' => $returnHtml, 'template' => $returnTemplate,
            'registrations' => self::getVueComponentsRegistrations()]))->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds($this->refreshActionIds);
    }

    public function initContent()
    {
        $this->initLoadModalAction(new ExampleModal());
    }

    public function setModal($modal)
    {
        $modal->setMainContainer($this->mainContainer);
        $this->modal = $modal;
        if ($modal instanceof \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Interfaces\AjaxElementInterface)
        {
            $this->mainContainer->addAjaxElement($this->modal->runInitContentProcess());
        }
    }

    protected function initLoadModalAction($modal)
    {
        $this->htmlAttributes['@click'] = 'loadModal($event, \'' . $this->id . '\', \'' . $this->getNamespace() . '\', \'' . $this->getIndex() . '\', null, true)';
        $this->setModal($modal);
    }
}
