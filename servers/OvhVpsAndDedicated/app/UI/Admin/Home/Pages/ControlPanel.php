<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Pages;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\FieldsProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\PageController;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\ChangePasswordButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\ConsoleBaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\PowerOffBaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\PowerOnBaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\RebootBaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\ReinstallBaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\ShutdownBaseModalButton;
use function ModulesGarden\Servers\OvhVpsAndDedicated\Core\Helper\isAdmin;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\RescueBaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Buttons\UnrescueBaseModalButton;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Vps\Repository;
/**
 * Description of ProductPage
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ControlPanel extends BaseContainer implements ClientArea, AdminArea
{
    use WhmcsParamsApp;
    public function initContent()
    {
        $this->addButton(new PowerOnBaseModalButton());
        $this->addButton(new PowerOffBaseModalButton());
        $this->addButton(new RebootBaseModalButton);




        if (isAdmin() === false)
        {
            $pageController = new PageController($this->getWhmcsEssentialParams());
            if ($pageController->vpsClientAreaConsole())
            {
                $this->addButton(new ConsoleBaseModalButton());
            }
            if($pageController->vpsClientAreaRescue())
            {
                $this->addRescueButton();
            }
//            if($pageController->vpsClientAreaChangePassword()) //currently not supported
//            {
//                $this->addButton(new ChangePasswordButton());
//            }
            if($pageController->vpsClientAreaReinstall())
            {
                $this->addButton(new ReinstallBaseModalButton());
            }
        }
        else
        {
            //            $this->addButton(new ChangePasswordButton()); //currently not supported
            $this->addButton(new ConsoleBaseModalButton());

            $this->addButton(new ReinstallBaseModalButton());
            $this->addRescueButton();

        }
    }




    public function addRescueButton()
    {
        $repo = new Repository($this->getWhmcsEssentialParams());
        $vps = $repo->get()->model();

        if($vps->getNetbootMode() == 'rescue')
        {
            $this->addButton(new UnrescueBaseModalButton);
            return;
        }

        $this->addButton(new RescueBaseModalButton);
    }

}
