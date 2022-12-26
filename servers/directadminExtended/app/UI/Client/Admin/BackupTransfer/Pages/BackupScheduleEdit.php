<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Pages;

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
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Providers;
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

class BackupScheduleEdit extends BaseStandaloneForm implements ClientArea,AjaxElementInterface
{
    use DirectAdminAPIComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\Forms;

    protected $id = 'backupScheduleForm';
    protected $name = 'backupScheduleForm';
    protected $title = 'backupScheduleForm';
    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-backup-schedule';

    public function initContent()
    {
        $this->loadLang();
        $this->addReplacement();
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\ScheduleEdit());
        $this->dataProvider->read();
        $data=$this->dataProvider->getData();
    }
    public function returnAjaxData()
    {

        $data = [];
        $this->loadRequestObj();
        if(isset($_POST['formData'])){
            return $this->dataProvider->update();
        }else{
            $optionsactivefields=[];
            $data=$this->dataProvider->getData();

            $dataoptions=[
                "option0"=>[
                    "text"=>$this->lang->T("domain"),
                    "value"=>"domain"
                ],
                "option1"=>[
                    "text"=>$this->lang->T("subdomain"),
                    "value"=>"subdomain"
                ],
                "option2"=>[
                    "text"=>$this->lang->T("ftp"),
                    "value"=>"ftp"
                ],
                "option3"=>[
                    "text"=>$this->lang->T("ftpsettings"),
                    "value"=>"ftpsettings"
                ],
                "option4"=>[
                    "text"=>$this->lang->T("database"),
                    "value"=>"database"
                ],
                "option5"=>[
                   "text"=> $this->lang->T("database_data"),
                   "value"=> "database_data"
                ],
                "option6"=>[
                    "text"=>$this->lang->T("forwarder"),
                    "value"=>"forwarder"
                ],
                "option7"=>[
                    "text"=>$this->lang->T("email"),
                    "value"=>"email"
                ],
                "option8"=>[
                    "text"=>$this->lang->T("email_data"),
                    "value"=>"email_data"
                ],
                "option9"=>[
                    "text"=>$this->lang->T("emailsettings"),
                    "value"=>"emailsettings"
                ],
                "option10"=>[
                    "text"=>$this->lang->T("vacation"),
                    "value"=>"vacation"
                ],
                "option11"=>[
                    "text"=>$this->lang->T("autoresponder"),
                    "value"=>"autoresponder"
                ],
                "option12"=>[
                    "text"=>$this->lang->T("list"),
                    "value"=>"list"
                ],
                "option13"=>[
                    "text"=>$this->lang->T("trash"),
                    "value"=>"trash"
                ]
            ];
            $this->data->ftp_ip='';
            $this->data->ftp_username='';
            $this->data->ftp_password='';
            $this->data->ftp_path='';
            $this->data->ftp_port='';
            // debug($data);die();

            $this->data=$data['who'];
            $where=$data['where'];
            if($this->data->who->who!="all"){
                $this->data->who=$this->data->who->who;
                foreach ($this->data->who->users as $key => $user) {
                    $this->data->selectedusers[]=$user->user;
                }
            }else{
                $this->data->who="all";
                $this->data->selectedusers=[];
            }
            $this->data->selecteddataoptions=['domain','subdomain','ftp','ftpsettings','database','database_data','forwarder','email','email_data','vacation','autoresponder','list','trash','emailsettings'];
            if($this->data->what->what!="all"){
                 $this->data->selecteddataoptions=[];
                foreach ($this->data->what->select as $key => $opt) {
                    $this->data->selecteddataoptions[]=$opt;
                }
                $this->data->what=$this->data->what->what;
            }else{
                $this->data->what="all";

            }

            $this->data->dataoptions=$dataoptions;
            $this->data->where=$this->data->where;
            if($this->data->where=='local'){
                $this->data->localpath=$where->files_location;
            }else{
                if(isset($this->data->settings->ftp_ip)){
                    $this->data->ftp_ip=$this->data->settings->ftp_ip;
                    $this->data->ftp_username=$this->data->settings->ftp_username;
                    $this->data->ftp_password=$this->data->settings->ftp_password;
                    $this->data->ftp_path=$this->data->settings->ftp_path;
                    $this->data->ftp_port=$this->data->settings->ftp_port;
                }

            }
            $this->data->append_to_pathoption='nothing';
            foreach ($this->data->append_to_path as $key => $opt) {
                if(isset($opt->selected)){
                    $this->data->append_to_pathoption=$opt->value;
                }
            }



            $this->data->minute=$this->data->when->minute;
            $this->data->hour=$this->data->when->hour;
            $this->data->dayofmonth=$this->data->when->dayofmonth;
            $this->data->month=$this->data->when->month;
            $this->data->dayofweek=$this->data->when->dayofweek;
            $this->data->custom_append='';

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


}
