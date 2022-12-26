<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 11, 2017)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Helper\Decorator;
use ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use ModulesGarden\WordpressManager\App\Models\PluginPackage;
use ModulesGarden\WordpressManager\App\Models\CustomPlugin;
use ModulesGarden\WordpressManager\App\Repositories\HostingRepository;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\PluginProvider;
use ModulesGarden\WordpressManager\Core\FileReader\Reader\Json;
use ModulesGarden\WordpressManager\Core\ModuleConstants;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\HalfPageSection;
use function ModulesGarden\WordpressManager\Core\Helper\sl;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\TitleField;
use ModulesGarden\WordpressManager\App\UI\Installations\Sections\RowSection;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonSubmitForm;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\SoftwareSetup;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\WordpressManager\App\UI\Installations\Fields\PrefixedText;
/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class PluginUpload extends BaseStandaloneForm implements ClientArea
{
    protected $formData;
    protected $formFields;
    use main\App\Http\Client\BaseClientController;

    // protected $modal;

    /**
     * InstallationCreateForm constructor.
     * @param main\App\UI\Installations\Modals\InstallationCreateModal $modal
     */
    // public function __construct(main\App\UI\Installations\Modals\InstallationCreateModal $modal)
    // {
    //     $this->modal    = $modal;

    //     parent::__construct();
    // }

    public function initContent()
    {
        $this->loadRequestObj();
        $this->formData = $this->request->get('formData');
        $this->initIds('uploadPluginForm');

        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new PluginProvider());
        // $this->tabDetails();

        $submitButton = new ButtonSubmitForm();
        $submitButton->setFormId($this->id);
        $submitButton->runInitContentProcess();
        // debug($submitButton);die();
        $this->setSubmit($submitButton);
        $this->loadDataToForm();
    }
    public function getUploadPluginActionUrl()
    {
// http://cyberpower.test/index.php?m=WordpressManager&mg-page=home&mg-action=plugins&id=7&wpid=250&loadData=uploadPluginForm&namespace=ModulesGarden_WordpressManager_App_UI_Installations_Forms_PluginUpload&index=baseStandaloneForm&ajax=1&mgformtype=create
//http://cyberpower.test/index.php?m=WordpressManager&mg-page=home&mg-action=plugins&id=7&wpid=250&namespace=ModulesGarden_WordpressManager_App_UI_Installations_Forms_PluginUpload&index=baseStandaloneForm&mgformtype=create
//
//
//http://cyberpower.test/index.php?m=WordpressManager&mg-page=home&mg-action=plugin&id=7&namespace=ModulesGarden_WordpressManager_App_UI_Installations_Forms_PluginUpload&index=mg-plugin-install&mgformtype=create
// <form id="uploadPluginForm" namespace="ModulesGarden_WordpressManager_App_UI_Installations_Forms_PluginUpload" index="baseStandaloneForm" mgformtype="create" onsubmit="return false;"></form>
        return BuildUrl::getUrl('home', 'plugins',['id'=>$_GET['id'],'wpid'=>$_GET['wpid'],'namespace'=>'ModulesGarden_WordpressManager_App_UI_Installations_Forms_PluginUpload','ajax'=>1,'loadData'=>'uploadPluginForm','index'=>'baseStandaloneForm','mgformtype'=>'create']);
    }


}
