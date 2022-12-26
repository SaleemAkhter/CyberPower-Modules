<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;


use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Pages\WordPressDescription;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Pages\WordPressMenu;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\WordpressManager\App\UI\Client\PluginPackages\PluginPackageDataTable;
use ModulesGarden\WordpressManager\App\UI\Installations\BackupPage;
use ModulesGarden\WordpressManager\App\UI\Installations\ConfigDataTable;
use ModulesGarden\WordpressManager\App\UI\Installations\DetailPage;
use ModulesGarden\WordpressManager\App\UI\Installations\PluginPage;
use ModulesGarden\WordpressManager\App\UI\Installations\ThemeContainer;
use ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Pages\InstallationPage;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator as DAServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\WordPressManager\WordPressManager as WPHelper;

class WordPressManager extends AbstractController
{
    use ProductsFeatureComponent;

    public function __construct()
    {
        parent::__construct();
        if($this->isWordpressManagerActive())
        {
            \ModulesGarden\Servers\DirectAdminExtended\App\Helpers\WordPressManager::replaceRequestClass();
        }
    }

    protected function isClientLoggedIn()
    {
        return true;
    }

    public function index()
    {
        if(!$this->isWordpressManagerActive())
        {
            return null;
        }

        $this->replaceLangs();

        return Helper\view()
            ->addElement(new WordPressDescription((new \ReflectionClass($this))->getShortName()))
            ->addElement(InstallationPage::class);
    }

    public function details()
    {
        if(!$this->isWordpressManagerActive())
        {
            return null;
        }

        $this->replaceLangs();

        return Helper\view()
            ->addElement(new WordPressMenu((new \ReflectionClass($this))->getShortName()))
            ->addElement(DetailPage::class);
    }

    public function plugins()
    {
        if(!$this->isWordpressManagerActive())
        {
            return null;
        }

        $this->replaceLangs();

        return Helper\view()
            ->addElement(new WordPressMenu((new \ReflectionClass($this))->getShortName()))
            ->addElement(PluginPage::class);
    }

    public function pluginPackages()
    {
        if(!$this->isWordpressManagerActive())
        {
            return null;
        }

        $this->replaceLangs();

        return Helper\view()
            ->addElement(new WordPressMenu((new \ReflectionClass($this))->getShortName()))
            ->addElement(PluginPackageDataTable::class);
    }

    public function themes()
    {
        if(!$this->isWordpressManagerActive())
        {
            return null;
        }

        $this->replaceLangs();

        if(class_exists("\ModulesGarden\WordpressManager\App\UI\Installations\ThemeContainer")) {
            return Helper\view()
                ->addElement(new WordPressMenu((new \ReflectionClass($this))->getShortName()))
                ->addElement(ThemeContainer::class);
        }

        return null;
    }

    public function backups()
    {
        if(!$this->isWordpressManagerActive())
        {
            return null;
        }

        $this->replaceLangs();

        return Helper\view()
            ->addElement(new WordPressMenu((new \ReflectionClass($this))->getShortName()))
            ->addElement(BackupPage::class);
    }

    public function configData()
    {
        if(!$this->isWordpressManagerActive())
        {
            return null;
        }

        $this->replaceLangs();

        return Helper\view()
            ->addElement(new WordPressMenu((new \ReflectionClass($this))->getShortName()))
            ->addElement(ConfigDataTable::class);
    }

    protected function isWordpressManagerActive()
    {
        return !(!$this->isFeatureEnabled(FeaturesSettingsConstants::WORDPRESS_MANAGER)
            || !WPHelper::isActive()
            || !WPHelper::activeForHosting($this->getHostingId()));
    }

    protected function replacelangs(){
        $wpLang = ServiceLocator::call('lang');
        $rfLang = new \ReflectionClass($wpLang);
        $langValues = $rfLang->getProperty('langs');
        $langValues->setAccessible(true);


        $langValues->setValue($wpLang, array_replace_recursive($langValues->getValue($wpLang), DAServiceLocator::call('lang')->getLangs()));

        $wpLang->setContext('addonCA', 'home');
    }

    public function backupDownload()
    {

        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('id'))
            ->where('user_id', $request->getSession('uid'))
            ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        $content      = $module->backupDownload($request->get('backupId'));
        ob_clean();
        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Cache-Control: public");
        header("Content-Type: application/x-pem-file");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:" . strlen($content));
        header("Content-Disposition: attachment; filename=" . $request->get('backupId'));
        echo $content;
        Helper\infoLog(sprintf("Backup '%s' has been downloaded successfully, Installation ID #%s, Client ID #%s", $request->get('backupId'), $installation->id, $request->getSession('uid')));
        die();
    }

    public function getBreadCrumb()
    {

        $clientAreaName = ServiceLocator::call(Addon::class)->getConfig('clientareaName', 'default');
        $url            = BuildUrl::getUrl();
        $breadcrumb     = [BuildUrl::getUrl() => sprintf('<a href="%s">%s</a>', $url, $clientAreaName)];
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request        = Helper\sl('request');
        if ($request->get('mg-action') == "detail")
        {
            $installation     = Installation::where('id', $request->get('id'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
            $url              = BuildUrl::getUrl('Home', 'detail', ["id" => $installation->id]);
            $breadcrumb[$url] = sprintf('<a href="%s">%s</a>', $url, $installation->domain);
        }
        return $breadcrumb;
    }
}