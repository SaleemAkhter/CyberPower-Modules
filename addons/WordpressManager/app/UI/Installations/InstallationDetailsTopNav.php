<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations;

use ModulesGarden\WordpressManager\App\Models\InstanceImage;
use ModulesGarden\WordpressManager\App\Models\ProductSetting;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\CloneButton;
use \ModulesGarden\WordpressManager\Core\UI\Builder\BaseContainer;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface;
use \ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\WebsiteDetails;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Services\GooglePreviewAPI;
use ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails\SslButton;
use ModulesGarden\WordpressManager\App\UI\Installations\Buttons\InstallationUpdateButton;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\HalfPageSection;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\TabSection;

use function ModulesGarden\WordpressManager\Core\Helper\sl;

class InstallationDetailsTopNav extends BaseContainer implements ClientArea, AjaxElementInterface
{
    use RequestObjectHandler;
    use main\Core\UI\Traits\Buttons;
    use main\App\Http\Client\BaseClientController;

    protected $id    = 'mg-wp-installation-details-top-nav';
    protected $name  = 'mg-wp-installation-details-top-nav-name';
    protected $title = 'mg-wp-installation-details-top-nav-title';

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-wp-installation-details-top-nav';

    public function initContent()
    {
        $request       = sl('request');
        $hostingid='';
        if(isset($_GET['hostingId'])){
            $hostingid=$_GET['hostingId'];
        }else{
            $hostingid=$_GET['id'];
        }
        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=new&id='.$request->get('id', '')
        : BuildUrl::getUrl('home', 'new',['id'=>$request->get('id', ''),'hostingId'=>$hostingid]);

        $newInstallation = new ButtonRedirect('new');

        $newInstallation->setRawUrl($baseUrl)
        ->setIcon('lu-dropdown__link-icon lu-zmdi lu-zmdi-edit')
        ->resetClass()
        ->addClass('ml-10')
        ->addClass('lu-dropdown__link')->setShowTitle(true);
        $this->addButton($newInstallation);


        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
        'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=remoteimport&id='.$request->get('id', '').'&hostingId='.$hostingid
        : BuildUrl::getUrl('home', 'remoteimport',['id'=>$request->get('id', ''),'hostingId'=>$hostingid]);

        $newInstallation = new ButtonRedirect('importButton');

        $newInstallation->setRawUrl($baseUrl)
        ->setIcon('lu-dropdown__link-icon lu-zmdi  lu-zmdi-cloud-download')
        ->resetClass()
        ->addClass('ml-10')
        ->addClass('lu-dropdown__link')->setShowTitle(true);
        $this->addButton($newInstallation);


        //todo - refactor me
        $hasPrivateInstanceImage = InstanceImage::OfUserId($this->userId)->enable()->count() > 0;
        if ((ProductSetting::enable()->where('settings','like', '%instanceImages%')->count()   && InstanceImage::enable()->where('id', '>', 0)->count() > 0 )
            || $hasPrivateInstanceImage  )
        {
            $this->addButton((new Buttons\InstanceImageButton('instanceImageButton'))
                ->setMainContainer($this->mainContainer));
        }
    }

    public function returnAjaxData()
    {
        //
    }
}
