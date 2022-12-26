<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\UsersComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Pages\UserEditPackage;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Pages\UserEditIp;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Pages\UserInfo;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Pages\UserEditUsage;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Redirect\Pages\RedirectPage;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

define("ADMIN_PAGE",true);

class UserManager extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,
        \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent,
        \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent,
        DirectAdminAPIComponent,
        UsersComponent;


    public function index()
    {
        if (!$this->isServerProduct() || $this->isFeatureEnabled(FeaturesSettingsConstants::ADMIN_USER_MANGER) === false)
        {
            return null;
        }

        return Helper\view()
                        ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Pages\UsersTable::class);

    }
    public function LoginAs()
    {
        if (!$this->isServerProduct() || $this->isFeatureEnabled(FeaturesSettingsConstants::ADMIN_USER_MANGER) === false)
        {
            return null;
        }
        $userName = $this->getRequestValue('actionElementId' ,false);

        if($userName !== false && $this->checkUserExists($userName))
        {
            $this->loadAdminApi();

            $user = json_decode(json_encode($this->adminApi->reseller->config(new Models\Command\User([
                'username'  => $userName
            ]))));
            $oldsessions=Request::build()->getSession('adminloginas');
            $oldsessionsrole=Request::build()->getSession('adminloginasrole');


            if($oldsessions){
                $sessions=$oldsessions;
                $sessions[$this->getWhmcsParamByKey('serviceid')]=$userName;
                $sessionsrole=$oldsessionsrole;
                $sessionsrole[$this->getWhmcsParamByKey('serviceid')]=$user->usertype;
            }else{
                $sessions=[
                    $this->getWhmcsParamByKey('serviceid')=>$userName
                ];
                $sessionsrole[$this->getWhmcsParamByKey('serviceid')]=$user->usertype;

            }
            Request::build()->addSession('adminloginas', $sessions);
            Request::build()->addSession('adminloginasrole', $sessionsrole);
            redir([
                    'action' => 'productdetails',
                    'id'     =>   $this->getRequestValue('id', false),
                ],"clientarea.php");
        }

    }
    public function ResellerLevelAccess()
    {
        if (!$this->isServerProduct() || $this->isFeatureEnabled(FeaturesSettingsConstants::ADMIN_USER_MANGER) === false)
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
        }else{
            $adminloginas=Request::build()->getSession('adminloginas');
            if($adminloginas && isset($adminloginas[$this->getWhmcsParamByKey('serviceid')]))
            {
                unset($_SESSION['adminloginas'][$this->getWhmcsParamByKey('serviceid')]);
                unset($_SESSION['adminloginasrole'][$this->getWhmcsParamByKey('serviceid')]);
                redir([
                        'action' => 'productdetails',
                        'id'     =>   $this->getRequestValue('id', false),
                    ],"clientarea.php");
            }
        }
    }


    public function EditUser()
    {
        if (!$this->isServerProduct() || $this->isFeatureEnabled(FeaturesSettingsConstants::ADMIN_USER_MANGER) === false)
        {
            return null;
        }

        $userName = $this->getRequestValue('actionElementId' ,false);

        if($userName !== false)
        {
            return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), ['index','EditUser']))
            ->addElement(
                (new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                ->setTitle("EditTitle")
                ->setDescription("EditDescription")
            )
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
    public function info()
    {
        if (!$this->isServerProduct() || $this->isFeatureEnabled(FeaturesSettingsConstants::ADMIN_USER_MANGER) === false)
        {
            return null;
        }

        $userName = $this->getRequestValue('actionElementId' ,false);

        if($userName !== false)
        {
            return Helper\view()
            ->addElement(new Breadcrumb($this->getClassName(), ['index','info']))
            ->addElement(
                (new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                ->setTitle("InfoTitle")
                ->setDescription("InfoDescription")
            )
            ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\StartBox($this->getClassName()."Info"))
             ->addElement(UserInfo::class)
             ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\EndBox($this->getClassName()."Ends"));

            $this->loadUserApi();

            if(in_array($userName, $this->getUserList())){
                return Helper\view()->addElement(UserEdit::class);
            }
        }

    }

}
