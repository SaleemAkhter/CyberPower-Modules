<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations;

use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Helper\CheckWpVersion;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\ModuleSettings;
use ModulesGarden\WordpressManager\App\Models\ProductSetting;
use ModulesGarden\WordpressManager\App\Models\WebsiteDetails;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\InstanceImageButton;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\PushToLiveButton;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\SslButton;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\StagingButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\ChangeDomainButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\CloneButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\InstallationUpdateButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\UpgradeButton;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonBase;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use function ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\Core\Helper;

class AutodetectInstallations extends BaseContainer implements ClientArea, AjaxElementInterface
{
    use RequestObjectHandler;
    use main\Core\UI\Traits\Buttons;
    use main\App\Http\Client\BaseClientController;
    use \ModulesGarden\WordpressManager\Core\UI\Traits\FormDataProvider;

    protected $id = 'mg-wp-autodetectInstallations';
    protected $name = 'mg-wp-autodetectInstallations-name';
    protected $title = 'mg-wp-autodetectInstallations-title';

    protected $vueComponent = true;
    protected $defaultVueComponentName = 'mg-wp-autodetectInstallations';

    private $settings;

    public function initContent()
    {
        if(isset($_POST['startautodetect'])){
            $response=$this->autodetect();
            echo json_encode(['data'=>$response]);
            exit;
        }
        return $this->returnAjaxButtons();
    }
    public function autodetect()
    {
        $data=$_POST['formData'];
        $this->setProvider(new InstallationProvider);
        return $this->dataProvider->autodetect();
    }
    public function updateSiteInfo()
    {
        $data=$_POST['formData'];
        $this->setProvider(new InstallationProvider);
        return $this->dataProvider->updateSiteInfo();
    }
    public function updateSiteConfig()
    {
        $data=$_POST['formData'];
        $this->setProvider(new InstallationProvider);
        return $this->dataProvider->updateSiteConfig();
    }
    public function returnAjaxButtons()
    {
        $this->loadRequestObj();

        return (new RawDataJsonResponse(['data' => []]));
    }
    public function getAvailableOpts(){
        return [
                0 => Helper\sl("lang")->abtr("Do not auto upgrade"),
                1 => Helper\sl("lang")->abtr("Upgrade to any latest version available (Major as well as Minor)"),
                2 => Helper\sl("lang")->abtr("Upgrade to Minor versions only"),
            ];
    }
    public function getManagePluginsButton($wpid)
    {

    }
    public function returnAjaxData()
    {
        $data=['installations'=>[]];

        return (new RawDataJsonResponse(['data' => $data]));
    }


    public function replaceFieldVersion($key, $row)
    {
        $result = (new CheckWpVersion())->checkIfNewer($row->version);

        if ($result)
        {
            $rd = new ButtonBase;
            $rd->setName('updateWpAlert');
            $rd->setTitle('updateWpAlert');
            $rd->replaceClasses(['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--danger redAlert vertical-align-middle']);
            $rd->setIcon('lu-btn__icon lu-zmdi lu-zmdi-alert-circle');

            return $row->version . $rd;
        }

        return $row->version;
    }
}
