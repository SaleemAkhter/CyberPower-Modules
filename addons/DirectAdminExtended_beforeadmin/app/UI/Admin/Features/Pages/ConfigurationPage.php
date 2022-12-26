<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Pages;

use ModulesGarden\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use function ModulesGarden\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\TabsWidget\TabsWidget;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ServerParams;


class ConfigurationPage extends TabsWidget implements AdminArea
{
    protected $id    = 'configurationPage';
    protected $name  = 'configurationPage';
    protected $title = null;

    public function initContent()
    {
        $this->checkServerSettings();
        $this->addElement(di(Configuration::class))
            ->addElement(di(BackupsContainer::class));
    }


    private function checkServerSettings()
    {
        $params = ServerParams::getServerParamsByProductId($this->getRequestValue('pid'));

        if(is_null($params))
        {
            throw new \Exception(ServiceLocator::call('lang')->absoluteTranslate('cannotGetServer'));
        }
    }
}
