<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Pages;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\Delete;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\Cache;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\ChangeDomain;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\CloneButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\InstallationUpdateButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\InstanceImage;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\PushToLive;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\Ssl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\Staging;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\Upgrade;
use ModulesGarden\Servers\DirectAdminExtended\Core\DependencyInjection\DependencyInjection;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\Core\ModuleConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavMenuButton;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavRedirectButton;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\NavSubRedirectButton;
use ModulesGarden\WordpressManager\App\Helper\InstallationSettings;
use Symfony\Component\Yaml\Yaml;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;

class WordPressMenu extends BaseContainer implements ClientArea
{
    use RequestObjectHandler;

    protected $id = 'wordPressMenu';
    protected $name = 'wordPressMenu';
    protected $title = 'wordPressMenu';

    public function isVueComponent()
    {
        return false;
    }

    public function initContent()
    {
        $params = sl('request')->query->all();
        $this->loadRequestObj();
        $buttonsData = $this->getButtonsData();

        foreach ($buttonsData as $buttonData) {
            if (isset($buttonData['action'])) {
                $redirectButton = new NavRedirectButton();
                $redirectButton->setRawUrl(BuildUrl::getUrl('wordPressManager', $buttonData['action'], [
                    'action' => $params['action'],
                    'id' => $params['id'],
                    'modop' => $params['modop'],
                    'wpid' => $params['wpid'],
                    'a' => $params['a']
                ]));
                $redirectButton->setId($buttonData['name']);
                $redirectButton->setTitle($buttonData['name']);
                $redirectButton->setIcon($buttonData['icon']);
                $this->addButton($redirectButton);
            }

            $navActionButton = new NavMenuButton();
            $navActionButton->setId($buttonData['name']);
            $navActionButton->setTitle($buttonData['name']);
            $navActionButton->setIcon('');
            $this->addButton($navActionButton);
        }

        if ($buttonsData['actions']['actions-control-panel']) {
            $controlPanel = new NavSubRedirectButton('controlPanel');
            $controlPanel->setRawUrl(BuildUrl::getCustomModuleUrl('WordpressManager', 'home', 'controlPanel', [
                'wpid' => $params['wpid'],
                'uid' => $this->request->getSession('uid')
            ]));
            $controlPanel->setIcon('lu-btn__icon lu-zmdi lu-zmdi-shield-security');
            $controlPanel->addClass('lu-btn lu-btn--link lu-btn--plain lu-btn--default lu-nav__link lu-btn-float-left .lu-btn-redirect-align');
            $navActionButton->addButton($controlPanel);
        }

        if ($buttonsData['actions']['actions-clear-cache']) {
            $navActionButton->addButton((new Cache('cache'))
                ->setMainContainer($this->mainContainer)
                ->addClass('lu-nav__link lu-btn-float-left'));
        }

        if ($buttonsData['actions']['actions-clone']) {
            $navActionButton->addButton((new CloneButton('clone'))
                ->setMainContainer($this->mainContainer)
                ->addClass('lu-nav__link lu-btn-float-left'));
        }

        if ($buttonsData['actions']['actions-manage-auto-upgrade']) {
            $navActionButton->addButton((new Upgrade('update'))
                ->setMainContainer($this->mainContainer)
                ->addClass('lu-nav__link lu-btn-float-left'));
        }

        if ($buttonsData['actions']['actions-change-domain']) {
            $navActionButton->addButton((new ChangeDomain('changeDomain'))
                ->setMainContainer($this->mainContainer)
                ->addClass('lu-nav__link lu-btn-float-left'));
        }

        if ($buttonsData['actions']['actions-installation-update-button']) {
            $navActionButton->addButton((new InstallationUpdateButton('upgrade'))
                ->setMainContainer($this->mainContainer)
                ->addClass('lu-nav__link lu-btn-float-left'));
        }

        if ($buttonsData['actions']['actions-staging']) {
            $staging = (new Staging)
                ->setMainContainer($this->mainContainer)
                ->addClass('lu-nav__link lu-btn-float-left');

            if ($this->getInstallation()->getInstallation()->staging) {
                $staging->setHtmlAttributes(["disabled" => ""]);
            }
            $navActionButton->addButton($staging);
        }

        if ($buttonsData['actions']['actions-push-to-live']) {
            $pushToLive = (new PushToLive())
                ->setMainContainer($this->mainContainer)
                ->addClass('lu-nav__link lu-btn-float-left');
            if (!$this->getInstallation()->getInstallation()->staging) {
                $pushToLive->setHtmlAttributes(["disabled" => ""]);
            }
            $navActionButton->addButton($pushToLive);
        }

        if ($buttonsData['actions']['actions-ssl']) {
            $navActionButton->addButton((new Ssl)
                ->setMainContainer($this->mainContainer)
                ->addClass('lu-nav__link lu-btn-float-left'));
        }


        if ($buttonsData['actions']['actions-instance-image']) {
            $navActionButton->addButton((new InstanceImage)
                ->setMainContainer($this->mainContainer)
                ->addClass('lu-nav__link lu-btn-float-left'));
        }

        $navActionButton->addButton((new Delete())
            ->addClass('lu-nav__link lu-btn-float-left'));
    }

    public function addButton($button, $parentId = null)
    {
        if (is_string($button)) {
            $button = DependencyInjection::create($button);
        }
        if ($this->whmcsParams) {
            $button->setWhmcsParams($this->whmcsParams);
        }

        $button->setMainContainer($this->mainContainer);

        $id = $button->getId();
        if (!isset($this->buttons[$id])) {
            $this->buttons[$id] = $button;
            if ($button instanceof \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface) {
                $this->mainContainer->addAjaxElement($this->buttons[$id]);
            }
        }

        return $this;
    }

    private function getInstallation()
    {
        return new InstallationSettings();
    }

    private function getSettings()
    {
        $settings = $this->getInstallation();
        return $settings->getWordpressSettings();
    }

    private function getButtonsData()
    {
        $fetchedSettings = $this->getSettings();
        $buttons = Yaml::parseFile((ModuleConstants::getFullPath('app', 'UI', 'Client', 'WordPressWidgets', 'Config', 'buttonSettings.yaml')));
        $actions = Yaml::parseFile((ModuleConstants::getFullPath('app', 'UI', 'Client', 'WordPressWidgets', 'Config', 'actionSettings.yaml')));

        foreach ($buttons as $key => $action) {
            if ($key === 'management-plugin-packages' && empty($fetchedSettings['pluginPackages']))
            {
                unset($buttons[$key]);
                continue;
            }

            if ($fetchedSettings[$key] === 0) {
                unset($buttons[$key]);
            }
        }

        foreach ($actions['actions'] as $key => $action) {
            if ($fetchedSettings[$key] === 0) {
                unset($actions['actions'][$key]);
            }
        }

        return array_merge($buttons, $actions);
    }
}