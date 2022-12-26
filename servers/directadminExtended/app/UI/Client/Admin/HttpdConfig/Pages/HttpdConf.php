<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ApplicationInstaller;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\AriaField;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\AppBox;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\RawSection;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormGroupSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\HttpdConfig\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\ListSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\TableSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\FileShow;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface;


use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;

class HttpdConf extends BaseContainer implements ClientArea,AjaxElementInterface
{
    use DirectAdminAPIComponent;

    protected $id = 'editIpForm';
    protected $name = 'editIpForm';
    protected $title = '';
    protected $data=[];
    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-fileEdit';
    public function initContent()
    {

        $provider=new Providers\HttpdConfFile();
        $provider->read();
        $data=$provider->getData();
        $this->data['domain']=$data['domain'];
        $this->data['httpd']=$data['httpd'];
        $this->data['result']=$data['result'];
        $this->data['templates']=$this->getTemplates();
    }
    public function returnAjaxData(){

        return (new RawDataJsonResponse(['data' => $this->data]));
    }
    public function getDomainName()
    {
        return $this->data['domain'];
    }
    public function getConfFile()
    {
        return $this->data['httpd'];
    }
    public function getTemplates()
    {
        $templates=[];
        foreach ($this->data['result'] as $key => $value) {
            if(substr( $key, 0, 2 ) === "VH"){
                $templates[]=$value;
            }
        }
        return $templates;
    }
    public function getConfData()
    {
        return $this->data['result'];
    }
    protected function getPackageIpFieldsSection($htttpd)
    {
        $data=preg_split("/\r\n|\n|\r/", $htttpd);
        // $html='<ol class="config limited">';
        // foreach ($data as $key => $line) {
        //     $html.='<li class="line"><pre>'.$line.'</pre></li>';
        // }
        // $html.='</ol>';

        $selectFieldsSection =(new FormGroupSection('httpd.conf'))
            ->addField((new FileShow('httpd'))->setLines($data));
        return $selectFieldsSection;
    }

    public function addReplacement()
    {
        $userName = $this->getRequestValue('actionElementId' ,false);
        ServiceLocator::call('lang')->addReplacementConstant('username', $userName);
    }

}
