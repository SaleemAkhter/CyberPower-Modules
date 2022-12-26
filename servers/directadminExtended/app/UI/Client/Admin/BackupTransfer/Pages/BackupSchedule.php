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

class BackupSchedule extends BaseStandaloneForm implements ClientArea,AjaxElementInterface
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
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new Providers\Schedule());
        $this->dataProvider->read();
        $data=$this->dataProvider->getData();
    }
    public function returnAjaxData()
    {

        $data = [];
        $this->loadRequestObj();
        // $this->reset();
        if(isset($_POST['formData'])){
            return $this->dataProvider->create();
        }else{
            $optionsactivefields=[];
            $data=$this->dataProvider->getData();
            // debug($data);die();

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
                    "text"=>$this->lang->T("vacation"),
                    "value"=>"vacation"
                ],
                "option10"=>[
                    "text"=>$this->lang->T("autoresponder"),
                    "value"=>"autoresponder"
                ],
                "option11"=>[
                    "text"=>$this->lang->T("list"),
                    "value"=>"list"
                ],
                "option12"=>[
                    "text"=>$this->lang->T("trash"),
                    "value"=>"trash"
                ],
                "option13"=>[
                    "text"=>$this->lang->T("emailsettings"),
                    "value"=>"emailsettings"
                ]
            ];

            $this->data=$data['who'];
            $this->data->who=$this->data->who->who;
            $this->data->dataoptions=$dataoptions;
            $this->data->what='all';
            $this->data->when='now';
            $this->data->where='local';
            $this->data->selecteddataoptions=['domain','subdomain','ftp','ftpsettings','database','database_data','forwarder','email','email_data','vacation','autoresponder','list','trash','emailsettings'];
            $this->data->append_to_pathoption='nothing';
            $this->data->selectedusers=[];
            $this->data->localpath=$this->data->files_location;
            $this->data->minute=0;
            $this->data->hour=5;
            $this->data->dayofmonth='*';
            $this->data->month='*';
            $this->data->dayofweek='*';
            $this->data->custom_append='';
            $this->data->ftp_ip='';
            $this->data->ftp_username='';
            $this->data->ftp_password='';
            $this->data->ftp_path='';
            $this->data->ftp_port='';
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
