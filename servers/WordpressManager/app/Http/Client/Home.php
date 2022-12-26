<?php

namespace ModulesGarden\WordpressManager\App\Http\Client;


use ModulesGarden\WordpressManager\App\Models\ModuleSettings;
use ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use ModulesGarden\WordpressManager\App\UI\Installations\WebsiteDetailsContainer;
use ModulesGarden\WordpressManager\App\UI\Installations\WebsiteDetailsPageContainer;
use ModulesGarden\WordpressManager\Core\Http\AbstractClientController;
use ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Sidebar\SidebarItem;
use function \ModulesGarden\WordpressManager\Core\Helper\redirect;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\ImportButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\InstallationCreateButton;
use ModulesGarden\WordpressManager\App\UI\Client\Customs\Buttons\Breadcrumb;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\InstallationUpdateFormButton;
/**
 * Description of Home
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Home extends AbstractClientController
{

    use BaseClientController;
    use \ModulesGarden\WordpressManager\App\Traits\BreadcrumbComponent,
    \ModulesGarden\WordpressManager\App\Traits\ClassNameComponent;
    private $wpid;

    protected function isClientLoggedIn()
    {
        return  (isset($_SESSION['resellerloginas']) && !empty($_SESSION['resellerloginas']));

    }


    public function index()
    {

        if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }
        $this->loadRequestObj();
        if (!Hosting::ofUserId($this->request->getSession('uid'))->productEnable()->count()) {
            return Helper\redirectByUrl('clientarea.php', ['action' => 'services']);
        }
        sl('sidebar')->destroy();

        $view = Helper\view()->setCustomJsCode()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                ->addElement(new \ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages\Description($this->getClassName()))
            ->setAppLayout(\ModulesGarden\WordpressManager\Core\UI\Helpers\AppLayoutConstants::NAVBAR_TOP)
            ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\Installations::class);

        if (((new ModuleSettings())->getSettings())['extendedView'] == 'on') {

            $view = Helper\view()->setCustomJsCode()
                ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                ->addElement(new \ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages\Description($this->getClassName().ucfirst(__FUNCTION__)))
                ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\InstallationDetailsTopNav::class)
                ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\InstallationDetailsPageC::class);
        }

        if ($this->isRessellerHosting() && !$this->request->get('hostingId')) {
            // $view->addElement(\ModulesGarden\WordpressManager\App\UI\Client\ResellerHosting\ProductDataTable::class);
        }

        return $view;
    }

    public function reseller()
    {
        return $this->index();
    }

    private function isRessellerHosting()
    {
        $this->loadRequestObj();
        return Hosting::ofUserId($this->request->getSession('uid'))->ProductReseller()->count() > 0;
    }

    private function sidebar()
    {
        $this->getSettings();

        $sidebar     = sl('sidebar');
        $sidebar->getSidebar("management")->getChild('details')->delete();
        /* @var $sidebar \ModulesGarden\WordpressManager\Core\UI\Widget\Sidebar\SidebarService */
        //installation details
        $sidebarItem = new SidebarItem('detail');
        $sidebarItem->setHref(BuildUrl::getUrl('home', 'detail', ['wpid' => $this->wpid]));
        $sidebar->getSidebar("management")->add($sidebarItem);

        //website details

        if ($this->checkIfCanDisplay($this->data['pageSpeedInsightsOption'])) {
            $sidebarItem = new SidebarItem('websiteDetails');
            $sidebarItem->setHref(BuildUrl::getUrl('home', 'websiteDetails', ['wpid' => $this->wpid]));
            $sidebar->getSidebar("management")->add($sidebarItem);
        }

        //Backups
        if ($this->checkIfCanDisplay($this->data['management-backups'])) {
            $sidebarItem = new SidebarItem('backups');
            $sidebarItem->setHref(BuildUrl::getUrl('home', 'backups', ['wpid' => $this->wpid]));
            $sidebar->getSidebar("management")->add($sidebarItem);
        }

        //themes
        if ($this->checkIfCanDisplay($this->data['management-themes'])) {
            $sidebarItem = new SidebarItem('themes');
            $sidebarItem->setHref(BuildUrl::getUrl('home', 'themes', ['wpid' => $this->wpid]));
            $sidebar->getSidebar("management")->add($sidebarItem);
        }

        //plugins
        if ($this->checkIfCanDisplay($this->data['management-plugins'])) {
            $sidebarItem = new SidebarItem('plugins');
            $sidebarItem->setHref(BuildUrl::getUrl('home', 'plugins', ['wpid' => $this->wpid]));
            $sidebar->getSidebar("management")->add($sidebarItem);
        }

        //pluginPackages
        if ($this->checkIfCanDisplay($this->data['management-plugin-packages'])) {
            $this->reset();
            $this->setInstallationId($this->wpid);
            $this->setUserId($this->request->getSession('uid'));
            if ($this->getInstallation()->hosting->productSettings->hasPluginPackages()) {
                $sidebarItem = new SidebarItem('pluginPackages');
                $sidebarItem->setHref(BuildUrl::getUrl('home', 'pluginPackages', ['wpid' => $this->wpid]));
                $sidebar->getSidebar("management")->add($sidebarItem);
            }
        }

        //config
        if ($this->checkIfCanDisplay($this->data['management-config'])) {
            $sidebarItem = new SidebarItem('config');
            $sidebarItem->setHref(BuildUrl::getUrl('home', 'config', ['wpid' => $this->wpid]));
            $sidebar->getSidebar("management")->add($sidebarItem);
        }

        //Users
        if ($this->checkIfCanDisplay($this->data['management-users'])) {
            $sidebarItem = new SidebarItem('users');
            $sidebarItem->setHref(BuildUrl::getUrl('home', 'users', ['wpid' => $this->wpid]));
            $sidebar->getSidebar("management")->add($sidebarItem);
        }
    }

    protected function isRequestValid()
    {
        $this->loadRequestObj();
        return $this->request->get('wpid');
    }
    public function new()
    {
        if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }

        return Helper\view()
            ->setCustomJsCode()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages\Description($this->getClassName().ucfirst(__FUNCTION__)))
            ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\Forms\NewInstallationForm::class);
    }

    public function remoteimport()
    {
        if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }
        $progressref=$this->request->get('ref');
        if($progressref){
            $provider               = new \ModulesGarden\WordpressManager\App\UI\Installations\Providers\ImportProvider();

            $response= $provider->checkProgress();
            header('Content-Type: application/json; charset=utf-8');
            echo  json_encode(['success'=>true,'message'=>$response]);
            exit;
        }
        return Helper\view()
            ->setCustomJsCode()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages\Description($this->getClassName().ucfirst(__FUNCTION__)))
            ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\Forms\InstallationImportForm::class);
    }
    public function autodetect()
    {
        if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }
        $progressref=$this->request->get('ref');
        if($progressref){
            $provider               = new \ModulesGarden\WordpressManager\App\UI\Installations\Providers\ImportProvider();

            $response= $provider->checkProgress();
            header('Content-Type: application/json; charset=utf-8');
            echo  json_encode(['success'=>true,'message'=>$response]);
            exit;
        }
        return Helper\view()
            ->setCustomJsCode()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages\Description($this->getClassName().ucfirst(__FUNCTION__)))
            ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\AutodetectInstallations::class);
    }

    public function detail()
    {   if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }
        if (!$this->isRequestValid()) {
            return redirect('home', 'index');
        }

        $this->setWpid($this->request->get('wpid'));

        // $this->sidebar();
        // sl('sidebar')->getSidebar("management")->getChild('detail')->setActive(true);
        $upgrade_plugins=$this->request->get('upgrade_plugins');
        if($upgrade_plugins){
            $provider               = new \ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider();

            $response= $provider->upgradePlugins();
            header('Content-Type: application/json; charset=utf-8');

            echo  json_encode(['success'=>true,'message'=>$response]);
            exit;
        }
        $upgrade_themes=$this->request->get('upgrade_themes');
        if($upgrade_themes){
            $provider               = new \ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider();

            $response= $provider->upgradeThemes();
            header('Content-Type: application/json; charset=utf-8');

            echo  json_encode(['success'=>true,'message'=>$response]);
            exit;
        }
        return Helper\view()
            ->setCustomJsCode()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages\Description($this->getClassName().ucfirst(__FUNCTION__)))
            ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\Forms\EditDetailForm::class)
            ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\DetailPage::class);
            // ->addElement( new InstallationUpdateFormButton());
    }


    public function websiteDetails()
    {
        if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }
        if (!$this->isRequestValid() || $this->getSettings()['pageSpeedInsightsOption'] === 0) {
            return redirect('home', 'index');
        }
        $this->setWpid($this->request->get('wpid'));
        $this->sidebar();
        sl('sidebar')->getSidebar("management")->getChild('websiteDetails')->setActive(true);

        return Helper\view()
            ->setCustomJsCode()

            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(WebsiteDetailsPageContainer::class);
    }


    public function themes()
    {
        if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }
        $this->getSettings();
        if (!$this->isRequestValid() || $this->data['management-themes'] === 0) {

            return redirect('home', 'index');
        }
        $this->setWpid($this->request->get('wpid'));
        $this->sidebar();
        sl('sidebar')->getSidebar("management")->getChild('themes')->setActive(true);
        return Helper\view()
            ->setCustomJsCode()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages\Description($this->getClassName().ucfirst(__FUNCTION__)))
            ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\ThemeContainer::class);
    }

    public function plugins()
    {
        if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }
        $this->getSettings();
        if (!$this->isRequestValid() || $this->data['management-plugins'] === 0) {
            return redirect('home', 'index');
        }
        $this->setWpid($this->request->get('wpid'));
        $this->sidebar();
        sl('sidebar')->getSidebar("management")->getChild('plugins')->setActive(true);
        return Helper\view()
            ->setCustomJsCode()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages\Description($this->getClassName().ucfirst(__FUNCTION__)))
            ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\PluginPage::class);
    }

    public function backups()
    {
        if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }
        $this->getSettings();
        if (!$this->isRequestValid() || $this->data['management-backups'] === 0) {
            return redirect('home', 'index');
        }
        $this->setWpid($this->request->get('wpid'));
        $this->sidebar();
        sl('sidebar')->getSidebar("management")->getChild('backups')->setActive(true);
        return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages\Description($this->getClassName().ucfirst(__FUNCTION__)))
            ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\BackupPage::class);
    }

    public function config()
    {
        if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }
        $this->getSettings();
        if (!$this->isRequestValid() || $this->data['management-config'] === 0) {
            return redirect('home', 'index');
        }
        $this->setWpid($this->request->get('wpid'));
        $this->sidebar();
        sl('sidebar')->getSidebar("management")->getChild('config')->setActive(true);
        return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages\Description($this->getClassName().ucfirst(__FUNCTION__)))
            ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\ConfigDataTable::class);
    }

    public function users()
    {
        if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }
        $this->getSettings();
        if (!$this->isRequestValid() || $this->data['management-users'] === 0) {
            return redirect('home', 'index');
        }
        $this->setWpid($this->request->get('wpid'));
        $this->sidebar();
        sl('sidebar')->getSidebar("management")->getChild('users')->setActive(true);
        return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
            ->addElement(new \ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages\Description($this->getClassName().ucfirst(__FUNCTION__)))
            ->addElement(\ModulesGarden\WordpressManager\App\UI\Installations\UserPage::class);
    }

    public function pluginPackages()
    {
        if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }
        $this->getSettings();
        if (!$this->isRequestValid() || $this->data['management-plugin-packages'] === 0) {
            return redirect('home', 'index');
        }
        $this->setWpid($this->request->get('wpid'));
        $this->sidebar();
        // sl('sidebar')->getSidebar("management")->getChild('pluginPackages')->setActive(true);
        return Helper\view()->setAppLayout(\ModulesGarden\WordpressManager\Core\UI\Helpers\AppLayoutConstants::NAVBAR_LEFT)
            ->addElement(\ModulesGarden\WordpressManager\App\UI\Client\PluginPackages\PluginPackageDataTable::class);
    }

    public function backupDownload()
    {
        if (!$this->isClientLoggedIn()) {
            return redir('', 'clientarea.php');
        }
        $this->setWpid($this->request->get('wpid'));
        $this->setInstallationId($this->wpid);
        $this->setUserId($this->request->getSession('uid'));
        if ($this->getInstallation()->username) {
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        $content = $this->subModule()->backupDownload($this->request->get('backupId'), $this->getInstallation());
        ob_clean();
        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Cache-Control: public");
        header("Content-Type: application/x-pem-file");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:" . strlen($content));
        header("Content-Disposition: attachment; filename=" . $this->request->get('backupId'));
        echo $content;
        Helper\infoLog(sprintf("Backup '%s' has been downloaded successfully, Installation ID #%s, Client ID #%s", $this->request->get('backupId'), $installation->id, $this->request->getSession('uid')));
        die();
    }

    /**
     * 
     * @deprecated since version 1.3.0
     */
    public function getBreadCrumb()
    {
    }

    public function controlPanel()
    {
        $this->setWpid($this->request->get('wpid'));
        $this->setInstallationId($this->wpid);
        $this->setUserId($this->request->getSession('uid'));
        if ($this->getInstallation()->username) {
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        $adminPanel = $this->subModule()->getSingleSignOnUrl($this->getInstallation()->relation_id);
        ob_clean();
        header("Location: " . $adminPanel);
        die();
    }

    public function getWpid()
    {
        return $this->wpid;
    }

    public function setWpid($wpid)
    {
        $this->wpid = $wpid;
        return $this;
    }

    private function getSettings()
    {
        $this->loadRequestObj();
        $this->reset();
        $this->setInstallationId($this->request->get('wpid'))
            ->setUserId($this->request->getSession('uid'));

        $repository               = new ProductSettingRepository;
        $model                    = $repository->forProductId($this->getInstallation()->hosting->product->id);
        $this->data               = $model->toArray();

        return $this->data;
    }

    private function checkIfCanDisplay($data)
    {
        return $data == 1;
    }
}
