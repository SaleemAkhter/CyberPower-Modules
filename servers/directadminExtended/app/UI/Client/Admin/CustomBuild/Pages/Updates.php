<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CustomBuild\Pages;

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
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\CustomBuild\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\ListSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\FormTableSection;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Users\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\Select;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\CustomBuild\Modals;

class Updates extends BaseStandaloneForm implements ClientArea,AjaxElementInterface
{
    use DirectAdminAPIComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\Forms;

    protected $id = 'updatesForm';
    protected $name = 'updatesForm';
    protected $title = 'updatesForm';
    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-updates';

    public function initContent()
    {
        $this->loadLang();
        $this->addReplacement();
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\CustomBuild());
        $this->dataProvider->read();
        $data=$this->dataProvider->getData();
    }
    public function returnAjaxData()
    {
        $data = [];
        $this->loadRequestObj();
        if(isset($_GET['index']) && $_GET['index']=='loadProgressModal'){
            $this->setModal(new Modals\Progress());
            return parent::returnAjaxData();
        }

        if(isset($_GET['software'])){
            return $this->dataProvider->update();
        }else{
            $optionsactivefields=[];
            $data=$this->dataProvider->getData();
            foreach ($data->data as $key => $d) {
                $data->data->{$key}->id=str_replace("=","",base64_encode(str_replace(" ","", $key)));
            }
            $this->data['records']=$data->data;
        }
        return (new RawDataJsonResponse(['data' => $this->data]));
    }
    protected function getPackageIpFieldsSection()
    {
        $selectFieldsSection =(new FormGroupSection('packagesSection'))
            ->addField((new Select('ip'))->setFormId($this->id)->setContainerClasss(['lu-col-md-8']));
        return $selectFieldsSection;
    }

    public function addReplacement()
    {
        $userName = $this->getRequestValue('actionElementId' ,false);
        ServiceLocator::call('lang')->addReplacementConstant('username', $userName);
    }
    protected function reloadFormStructure()
    {
        $this->dataProvider->reload();
        $this->loadDataToForm();
    }



    function addFieldAfter($fieldId, $newField)
    {
        $array = $this->getFields();

        $index = array_search($fieldId, array_keys($array)) + 1;

        $size = count($array);
        if (!is_int($index) || $index < 0 || $index > $size) {
            return -1;
        } else {
            $temp = array_slice($array, 0, $index);
            $temp[$newField->getId() . $index] = $newField;
            $this->fields = array_merge($temp, array_slice($array, $index, $size));
        }
    }

    public function getDirectoryUrl()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'SystemBackup',
            'mg-action'     => 'backupDirectory',

        ];

        return 'clientarea.php?'. \http_build_query($params);
    }
    public function getUpdateUrl()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'CustomBuild',
            'mg-action'     => 'index',

        ];

        return 'clientarea.php?'. \http_build_query($params);
    }
    public function getBuildUrl()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'CustomBuild',
            'mg-action'     => 'build',

        ];

        return 'clientarea.php?'. \http_build_query($params);
    }
    public function getEditUrl()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'CustomBuild',
            'mg-action'     => 'edit',

        ];

        return 'clientarea.php?'. \http_build_query($params);
    }
    public function getFormAction()
    {

        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'SshKey',
            'loadData'=>'editSshKeyForm',
            'namespace'=>'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Admin_SshKey_Pages_SshKeyEditPage',
            'mg-action'     => 'edit',
            'index'=>'editSshKeyForm',
            'ajax'=>1,
            'mgformtype'=>'sshKey'

        ];

        return 'clientarea.php?'. \http_build_query($params);
    }


}
