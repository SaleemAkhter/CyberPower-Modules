<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\CustomComponents;

use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\RawDataJsonResponse;

/**
 * Class SettingValue
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class DetailsWidget extends BaseContainer implements ClientArea, AjaxElementInterface
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