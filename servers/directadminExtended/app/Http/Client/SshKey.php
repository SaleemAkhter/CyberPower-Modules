<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Client;

use ModulesGarden\Servers\DirectAdminExtended\Core\Http\AbstractController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\FeaturesSettingsConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Buttons\Breadcrumb;
use \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Pages\SshKeyEditPage;
class SshKey extends AbstractController
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ClassNameComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\BreadcrumbComponent;

    public function index()
    {
        return Helper\view()
                        ->addElement(new Breadcrumb($this->getClassName(), $this->getClassMethods()))
                        ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\Description($this->getClassName()))
                        ->addElement(\ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SshKey\Pages\SshKeyTabs::class);
    }
    public function downloadKey()
    {
        $d=$this->getRequestValue('keydata', "");
        if($d){
            $keydetail=json_decode(base64_decode($d));
            $keyname=$keydetail->keyname;
            $filedata="ssh-rsa ".$keydetail->data." ".$keydetail->comment;
            $size   = strlen($filedata);
            $filename=$keyname.".pub";
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Content-Transfer-Encoding: binary');
            header('Connection: Keep-Alive');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . $size);
                    echo $filedata;

                    exit;

        }


    }
    public function edit()
    {
        if ($this->isFeatureEnabled(FeaturesSettingsConstants::USER_MANAGER) === false)
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

             ->addElement(SshKeyEditPage::class)
             ->addElement(new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Pages\EndBox($this->getClassName()."Ends"));

            $this->loadUserApi();

            if(in_array($userName, $this->getUserList())){
                return Helper\view()->addElement(UserEdit::class);
            }
        }

    }


}
