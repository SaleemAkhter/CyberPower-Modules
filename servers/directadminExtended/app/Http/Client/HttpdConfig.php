<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\UsersComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Pages\HttpdConf;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Redirect\Pages\RedirectPage;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates\RawDataJsonResponse;

define("ADMIN_PAGE",true);

class HttpdConfig extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,
        \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent,
        \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent,
        DirectAdminAPIComponent,
        UsersComponent;


    public function index()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::ADMIN_ADD_USER) === false)
        {
            return null;
        }

        return Helper\view()
                        ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Pages\HttpdConfigTable::class);
    }


    public function httpdconf()
    {
        $domain = $this->getRequestValue('actionElementId' ,false);

        if($domain !== false)
        {


            return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), ['index','httpdconf']))
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()."EditTitle"))
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\StartBox($this->getClassName()."Edit"))

             ->addElement(httpdConf::class)
             ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\EndBox($this->getClassName()."Ends"));

            $this->loadUserApi();

            if(in_array($userName, $this->getUserList())){
                return Helper\view()->addElement(UserEdit::class);
            }
        }

    }


}
