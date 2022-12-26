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

class Backup extends BaseStandaloneForm implements ClientArea,AjaxElementInterface
{
    use DirectAdminAPIComponent;
    use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\Forms;

    protected $id = 'backupForm';
    protected $name = 'backupForm';
    protected $title = 'backupForm';
    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-backup';

    public function initContent()
    {
        $this->loadLang();
        $this->addReplacement();
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new Providers\Backup());
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
            $this->loadUserApi();
            $domains=[];
            $result     = $this->userApi->domain->lists()->response;

            foreach($result as $items){
                $domains[]= $items->name;
            }
            $dataoptions=[
                "domain"=>[
                    "text"=>$this->lang->T("domainGroup"),
                    "value"=>"domain",
                    'children'=>[
                        "option0"=>[
                            "text"=>$this->lang->T("backupForm","domainBackupForm"),
                            "value"=>"domain"
                        ],
                        "option1"=>[
                            "text"=>$this->lang->T("backupForm","subdomainBackupForm"),
                            "value"=>"subdomain"
                        ],
                    ]
                ],
                "email"=>[
                    "text"=>$this->lang->T("emailGroup"),
                    "value"=>"email",
                    'children'=>[
                        "option6"=>[
                            "text"=>$this->lang->T("backupForm","emailBackupForm"),
                            "value"=>"email"
                        ],
                        "option7"=>[
                            "text"=>$this->lang->T("backupForm","email_dataBackupForm"),
                            "value"=>"email_data"
                        ],
                         "option8"=>[
                            "text"=>$this->lang->T("backupForm","emailsettingsBackupForm"),
                            "value"=>"emailsettings"
                        ],
                        "option9"=>[
                            "text"=>$this->lang->T("backupForm","forwarderBackupForm"),
                            "value"=>"forwarder"
                        ],
                        "option10"=>[
                            "text"=>$this->lang->T("backupForm","autoresponderBackupForm"),
                            "value"=>"autoresponder"
                        ],
                        "option11"=>[
                            "text"=>$this->lang->T("backupForm","vacationBackupForm"),
                            "value"=>"vacation"
                        ],

                        "option12"=>[
                            "text"=>$this->lang->T("backupForm","listBackupForm"),
                            "value"=>"list"
                        ],

                    ]
                ],
                "ftp"=>[
                    "text"=>$this->lang->T("ftpGroup"),
                    "value"=>"ftp",
                    'children'=>[
                        "option2"=>[
                            "text"=>$this->lang->T("backupForm","ftpBackupForm"),
                            "value"=>"ftp"
                        ],
                        "option3"=>[
                            "text"=>$this->lang->T("backupForm","ftpsettingsBackupForm"),
                            "value"=>"ftpsettings"
                        ],
                    ]
                ],
                "database"=>[
                    "text"=>$this->lang->T("databaseGroup"),
                    "value"=>"database",
                    'children'=>[
                        "option4"=>[
                            "text"=>$this->lang->T("backupForm","databaseBackupForm"),
                            "value"=>"database"
                        ],
                        "option5"=>[
                           "text"=> $this->lang->T("backupForm","database_dataBackupForm"),
                           "value"=> "database_data"
                        ],
                    ]
                ],
                "trash"=>[
                    "text"=>$this->lang->T("trashGroup"),
                    "value"=>"trash",
                    'children'=>[
                        "option13"=>[
                            "text"=>$this->lang->T("backupForm","trashBackupForm"),
                            "value"=>"trash"
                        ]
                    ]
                ]

            ];

            $data['domains']='all';
            $data['domains_list']=$domains;
            $data['settingroups']=['domain','email','ftp','database','trash'];
            $data['selectedDomains']=[];
            $data['settings']=$dataoptions;
            $data['selecteddataoptions']=[
                'domain','subdomain','ftp','ftpsettings','database','database_data','forwarder','email','email_data','vacation','autoresponder','list','trash','emailsettings'
            ];
            $data['minute']=0;
            $data['hour']=5;
            $data['dayofmonth']='*';
            $data['month']='*';
            $data['dayofweek']='*';
        }

        return (new RawDataJsonResponse(['data' => $data]));
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
