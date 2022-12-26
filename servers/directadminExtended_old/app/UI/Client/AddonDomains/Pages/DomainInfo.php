<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\AddonDomains\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Account;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Domain;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Others\Label;

class DomainInfo extends BaseContainer implements ClientArea
{
    use DirectAdminAPIComponent, Lang;

    protected $id    = 'domainInfo';
    protected $name  = 'domainInfo';
    protected $title = null;

    protected $info = false;


    protected $domainObject;


    public function initContent()
    {
        $this->loadLang();
        $this->loadUserApi();

        $this->domainObject = $this->userApi->domain->getStats(new Domain([
            'name'  => $this->getRequestValue('actionElementId', false)
        ]));
    }

    public function __call($name, $arguments = [])
    {
        if(method_exists($this->domainObject, $name)) {
            switch ($arguments[0]) {
                case 'label':
                    return $this->getLabel($this->domainObject->{$name}());
                case 'translate':
                    return $this->lang->absoluteTranslate('addonCA', 'addonDomains', 'infoModal', 'domainStatus', $this->domainObject->{$name}());
                default:
                    return $this->domainObject->{$name}();
            }
        }

        return "";
    }

    private function getLabel($status = "off")
    {
        $status = strtolower($status);
        $labelClass = ($status == 'on' || $status == 'yes')? 'lu-label--success' :'lu-label--danger';
        $label = new Label();
        $label->addClass($labelClass)
            ->addClass('lu-label--status')
            ->setBackgroundColor('')
            ->setColor('')
            ->setTitle(ServiceLocator::call('lang')->translate($status));

        return $label->getHtml();
    }

}
