<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\UsersComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\MoveBetweenResellers\Pages\UserList;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\MoveBetweenResellers\Forms\Move;


use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Redirect\Pages\RedirectPage;
define("ADMIN_PAGE",true);

class MoveBetweenResellers extends AbstractController
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
        if(isset($_POST['formData'])){
            return Helper\view()
                        ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(Move::class);
        }

        return Helper\view()
                        ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(UserList::class);

    }
    public function move($value='')
    {
         if(isset($_POST['formData'])){

            $this->loadAdminApi();


            $formData=$_POST['formData'];
            $users=explode(",",$formData['children']);


            $data=[
                'creator'=>$formData['reseller']
            ];
            foreach ($users as $key => $user) {
                $data['users'][]=$user;
            }
            $response=$this->adminApi->reseller->moveUser($data);
            header('Content-Type: application/json; charset=utf-8');

            if(isset($response->success)){
               echo json_encode([
                    'url' => $this->getMoveUsersURL(),
                    'message'=>'User moved successfully',
                    'error'=>false,
                    'status'=>'success'
                    ]);
            }else{
                echo json_encode([
                    'url' => $this->getMoveUsersURL(),
                    'message'=>'Unable to move user',
                    'error'=>true,
                    'status'=>'error'
                    ]);
            }

            exit;

        }

        return Helper\view()
                        ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(Move::class);
    }
     protected function getMoveUsersURL()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'MoveBetweenResellers',
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }


}
