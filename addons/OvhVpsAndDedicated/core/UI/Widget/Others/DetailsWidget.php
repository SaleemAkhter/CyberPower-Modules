<?php

namespace ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Others;

use ModulesGarden\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\ResponseTemplates\RawDataJsonResponse;

/**
 * Class DetailsWidget
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */

class DetailsWidget extends BaseContainer implements AjaxElementInterface
{
    use Lang;

    protected $id    = 'detailsWidget';
    protected $name  = 'detailsWidget';
    protected $title = 'detailsWidget';

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-details-widget';

    public function initContent()
    {

    }

    public function prepareAjaxData()
    {

    }

    public function getParsedTitle()
    {
        if($this->getRawTitle())
        {
            return $this->getRawTitle();
        }

        if($this->getTitle())
        {
            $this->loadLang();

            return $this->lang->controlerContextTranslate($this->getId() ,$this->getTitle());
        }

        return '';
    }

    public function returnAjaxData()
    {
        $this->prepareAjaxData();

        return (new RawDataJsonResponse([
            'title' => $this->getParsedTitle(),
            'data' => $this->data
        ]));
    }
}
