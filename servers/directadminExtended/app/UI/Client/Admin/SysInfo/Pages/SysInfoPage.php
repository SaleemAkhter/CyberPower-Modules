<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SysInfo\Pages;

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
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SysInfo\Providers;
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

class SysInfoPage extends BaseStandaloneForm implements ClientArea,AjaxElementInterface
{
    use DirectAdminAPIComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\Forms;

    protected $id = 'editSshKeyForm';
    protected $name = 'editSshKeyForm';
    protected $title = 'keydata';
    protected $vueComponent            = false;
    protected $defaultVueComponentName = 'mg-edit-sshkey';
    public $info;
    public function initContent()
    {

        $this->addReplacement();
        // $this->setRawTitle("<h6 class='pt-20'><strong>Key Data</strong></h6>");
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\SysInfo());
        $this->dataProvider->read();
        $this->info= $this->data=$this->dataProvider->getData();
    }
    public function returnAjaxData()
    {

        $data = [];
        $this->loadRequestObj();
        // $this->reset();
        if(isset($_POST['formData'])){
            return $this->dataProvider->update();
        }else{
            $optionsactivefields=[];
            $data=$this->dataProvider->getData();
            // debug($data);die();
            $options=[
                'environment'=>'environment',
                'from'=>'from',
                'nox11forwarding'=>'no-X11-forwarding',
                'noagentforwarding'=>'no-agent-forwarding',
                'noportforwarding'=>'no-port-forwarding',
                'nopty'=>'no-pty',
                'permitopen'=>'permitopen',
                'tunnel'=>'tunnel',
            ];
            $this->data=[
                'fingerprint'=>$data['fingerprint'],
                'comment'=>$data['comment'],
                'type'=>$data['type'],
                'size'=>$data['size'],
                'users'=>$data['users'],
                'selectedusers'=>[],
                'optionsactivefields'=>[],
                'applyto'=>'',
                'globalkey'=>(isset($data['global_keys']->{$data['fingerprint']}))?"on":""
            ];
            $globaldata=[
                'selectedusers'=>[]

            ];
            if(isset($data['global_keys']->{$data['fingerprint']})){
                $this->data['applyto']=$data['global_keys']->{$data['fingerprint']}->who;
                foreach ($data['global_keys']->{$data['fingerprint']}->users as $user => $d) {
                    if($d->enabled=="yes"){
                        $this->data['selectedusers'][]=$user;
                    }

                }
            }
            foreach ($data['options'] as $key => $option) {
                $fieldkey=strtolower(str_replace("-", "", $option->name));
                $optionsactivefields[]=$fieldkey;
                $this->data[$fieldkey]=$option->value;
                unset($options[$option->name]);
            }
             $this->data['options']=$options;
             $this->data['optionsactivefields']=$optionsactivefields;
            // die();

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

    public function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }

}
