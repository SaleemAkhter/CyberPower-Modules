<?php

namespace ModulesGarden\DirectAdminExtended\Core\UI\Widget\Others;

use ModulesGarden\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\DirectAdminExtended\Core\UI\ResponseTemplates\RawDataJsonResponse;

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
