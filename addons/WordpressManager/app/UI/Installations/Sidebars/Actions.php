<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Sidebars;

use ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use \ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use \ModulesGarden\WordpressManager\App\UI\Installations\Buttons\CacheButton;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Sidebar\SidebarItem;
use \ModulesGarden\WordpressManager\App\UI\Installations\Buttons\CloneButton;
use \ModulesGarden\WordpressManager\App\UI\Installations\Buttons\UpgradeButton;
use \ModulesGarden\WordpressManager\App\UI\Installations\Buttons\ChangeDomainButton;
use \ModulesGarden\WordpressManager\App\UI\Installations\Buttons\InstallationUpdateButton;
use \ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\StagingButton;
use \ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\PushToLiveButton;
use \ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\SslButton;
use \ModulesGarden\WordpressManager\App\UI\Installations\Buttons\InstallationDeleteButton;
use function ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\InstanceImageButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\MaintenanceModeButton;

class Actions extends \ModulesGarden\WordpressManager\Core\UI\Widget\Sidebar\SidebarAjax
                implements \ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea
{
    use \ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
    
    protected $id = 'actions';
    
    public function prepareAjaxData()
    {
        $this->loadRequestObj();
        $this->reset();
        $this->setInstallationId($this->request->get('wpid'))
              ->setUserId($this->request->getSession('uid'));

        $repository               = new ProductSettingRepository;
        $model                    = $repository->forProductId($this->getInstallation()->hosting->product->id);
        $this->data               = $model->toArray();
         //Control Panel

        if($this->data['actions-control-panel'] == 1)
        {
            $controlPanel = new SidebarItem('controlPanel');
            $controlPanel->setHref(BuildUrl::getUrl('home', 'controlPanel', ['wpid' => $this->getRequestValue('wpid')]));
            $controlPanel->setTarget('_blank');
            $controlPanel->setIcon('lu-btn__icon lu-zmdi lu-zmdi-shield-security');
            $this->add($controlPanel);
        }

        //Clear Cache
        if($this->checkIfCanDisplay($this->data['actions-clear-cache']))
        {
            $this->add((new CacheButton('cache'))->setMainContainer($this->mainContainer));
        }

        //Clone
        if($this->checkIfCanDisplay($this->data['actions-clone']))
        {
            $this->add((new CloneButton('clone'))->setMainContainer($this->mainContainer));
        }

        //Upgrade
        if($this->checkIfCanDisplay($this->data['actions-update']))
        {
            $update = (new UpgradeButton('upgrade'))->setMainContainer($this->mainContainer);
            $this->add($update);
        }

        //Change Domain
        if($this->checkIfCanDisplay($this->data['actions-change-domain']))
        {
            $this->add((new ChangeDomainButton('changeDomain'))->setMainContainer($this->mainContainer));
        }

        //Manage Auto Upgrades
        if($this->checkIfCanDisplay($this->data['actions-manage-auto-upgrade']))
        {
            $this->add((new InstallationUpdateButton('installationUpdate'))->setMainContainer($this->mainContainer));
        }

        //Staging
        if($this->checkIfCanDisplay($this->data['actions-staging']))
        {
            $staging = (new StagingButton)->setMainContainer($this->mainContainer);

            if ($this->getInstallation()->staging)
            {
                $staging->setHtmlAttributes(["disabled" => ""]);
            }
            $this->add($staging);
        }

        //Push To Live
        if($this->checkIfCanDisplay($this->data['actions-push-to-live']))
        {
            $pushToLive = (new PushToLiveButton)->setMainContainer($this->mainContainer);
            if (!$this->getInstallation()->staging)
            {
                $pushToLive->setHtmlAttributes(["disabled" => ""]);
            }
            $this->add($pushToLive);
        }

        //SSL
        if($this->checkIfCanDisplay($this->data['actions-ssl']))
        {
            $this->add((new SslButton)->setMainContainer($this->mainContainer));
        }

        //MaintenanceMode
        if($this->checkIfCanDisplay($this->data['actions-maintenance-mode']))
        {
            $this->add((new MaintenanceModeButton)->setMainContainer($this->mainContainer));
        }

        //InstanceImage
        if($this->checkIfCanDisplay($this->data['actions-instance-image']))
        {
            $this->add((new InstanceImageButton)->setMainContainer($this->mainContainer));
        }

        //Delete
        $buttonDelete = (new InstallationDeleteButton('installationDeleteButton'))->setMainContainer($this->mainContainer);
        if($this->getInstallation()->auto && !$this->getInstallation()->hosting->productSettings->isDeleteAutoInstall()){
            $buttonDelete->setHtmlAttributes(["disabled" => ""]);
        }
        $this->add($buttonDelete);
        
    }

    private function checkIfCanDisplay($data)
    {
        return $data == 1;
    }
}
