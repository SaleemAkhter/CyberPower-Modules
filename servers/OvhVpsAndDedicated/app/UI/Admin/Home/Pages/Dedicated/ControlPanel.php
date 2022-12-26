<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Pages\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\PageController;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Dedicated\IpmiBaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Dedicated\RescueBaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Dedicated\RebootBaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\Dedicated\ReinstallBaseModalButton;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\isAdmin;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;

/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ControlPanel extends BaseContainer implements ClientArea, AdminArea
{
    use WhmcsParamsApp;
    protected $id = 'controlPanel';

    public function initContent()
    {
        $this->addButton(new RebootBaseModalButton());

        $fieldProvider = new FieldsProvider($this->getWhmcsParamByKey('pid'));
        if($fieldProvider->getField('ipmiAccess')=="on"){
            $this->addButton(new IpmiBaseModalButton());
        }

        if (isAdmin() === false)
        {
            $pageController = new PageController($this->getWhmcsEssentialParams());
            if ($pageController->dedicatedClientAreaReinstall())
            {
                $this->addButton(new ReinstallBaseModalButton());
            }
            if ($pageController->dedicatedClientAreaRescue())
            {
                $this->addButton(new RescueBaseModalButton());
            }
        }
        else
        {
            $this->addButton(new ReinstallBaseModalButton());
            $this->addButton(new RescueBaseModalButton());
        }

    }
}
