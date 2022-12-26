<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Pages;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\ImportButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\InstallationCreateButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Buttons\InstanceImageButton;
use ModulesGarden\WordpressManager\App\Models\InstanceImage;
use ModulesGarden\WordpressManager\App\Models\ProductSetting;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;

class InstallationPage extends \ModulesGarden\WordpressManager\App\UI\Installations\InstallationPage
{
    protected function loadHtml()
    {
        $this->request = \ModulesGarden\Servers\DirectAdminExtended\App\Helpers\WordPressManager::replaceRequestClass([
            'hostingId' => di('request')->get('id')
        ]);

        parent::loadHtml();

        unset($this->columns['username']);
        unset($this->columns['name']);
    }

    public function initContent()
    {
        parent::initContent();

        $baseUrl = \ModulesGarden\WordpressManager\Core\Helper\BuildUrl::getUrl('home', 'controlPanel');

        if (!empty($this->actionButtons['controlPanelButton'])){
            $this->actionButtons['controlPanelButton']
                ->setRawUrl($baseUrl)
                ->initContent();
        }

        /**
         * Create button. We need to override it because we want to change fields in dropdown
         */
        unset($this->buttons['buttonCreate']);
        if($this->dropdawnWrapper)
        {
            $r = new \ReflectionProperty($this->dropdawnWrapper, 'elements');
            $r->setAccessible(true);
            $this->dropdawnWrapper->buttons = [];
            $r->setAccessible(false);
        }

        $this->addButton(new InstallationCreateButton());
        $this->addButton(new ImportButton('ImportButton'));

        $hasPrivateInstanceImage = InstanceImage::OfUserId($this->userId)->enable()->count() > 0;
        if ((ProductSetting::enable()->where('settings', 'like', '%instanceImages%')->count() && InstanceImage::enable()->where('id', '>', 0)->count() > 0)
            || $hasPrivateInstanceImage) {
            $this->addButton(new InstanceImageButton('InstanceImageButton'));
        }
    }
}