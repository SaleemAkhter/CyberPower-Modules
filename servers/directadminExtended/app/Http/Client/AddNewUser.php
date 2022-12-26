<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\UsersComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Pages\UserEditPackage;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Pages\UserEditIp;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Pages\UserEditUsage;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Redirect\Pages\RedirectPage;

define("ADMIN_PAGE",true);

class AddNewUser extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,
        \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent,
        \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent,
        DirectAdminAPIComponent,
        UsersComponent;


    public function index()
    {
        if (!$this->isServerProduct() || $this->isFeatureEnabled(FeaturesSettingsConstants::ADMIN_ADD_USER) === false)
        {
            return null;
        }

        return Helper\view()
                        ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Pages\AddNewUser::class);

    }
    public function LoginAs()
    {
        if (!$this->isServerProduct() || $this->isFeatureEnabled(FeaturesSettingsConstants::ADMIN_ADD_USER) === false)
        {
            return null;
        }
        $userName = $this->getRequestValue('actionElementId' ,false);

        if($userName !== false && $this->checkUserExists($userName))
        {
            $oldsessions=Request::build()->getSession('resellerloginas');
            if($oldsessions){
                $sessions=$oldsessions;
                $sessions[$this->getWhmcsParamByKey('serviceid')]=$userName;
            }else{
                $sessions=[
                    $this->getWhmcsParamByKey('serviceid')=>$userName
                ];
            }
            Request::build()->addSession('resellerloginas', $sessions);
            redir([
                    'action' => 'productdetails',
                    'id'     =>   $this->getRequestValue('id', false),
                ],"clientarea.php");
        }
    }
    public function ResellerLevelAccess()
    {
        if (!$this->isServerProduct() || $this->isFeatureEnabled(FeaturesSettingsConstants::ADMIN_ADD_USER) === false)
        {
            return null;
        }
        $resellerloginas=Request::build()->getSession('resellerloginas');
        if($resellerloginas && isset($resellerloginas[$this->getWhmcsParamByKey('serviceid')]))
        {
            unset($_SESSION['resellerloginas'][$this->getWhmcsParamByKey('serviceid')]);
            redir([
                    'action' => 'productdetails',
                    'id'     =>   $this->getRequestValue('id', false),
                ],"clientarea.php");
        }
    }


    public function EditUser()
    {
        if (!$this->isServerProduct() || $this->isFeatureEnabled(FeaturesSettingsConstants::USER_MANAGER) === false)
        {
            return null;
        }

        $userName = $this->getRequestValue('actionElementId' ,false);

        if($userName !== false)
        {
            return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), ['index','EditUser']))
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()."EditTitle"))
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\StartBox($this->getClassName()."Edit"))

             ->addElement(UserEditPackage::class)
             ->addElement(UserEditIp::class)
             ->addElement(UserEditUsage::class)
             ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\EndBox($this->getClassName()."Ends"));

            $this->loadUserApi();

            if(in_array($userName, $this->getUserList())){
                return Helper\view()->addElement(UserEdit::class);
            }
        }

    }

}
