<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\WhmcsParams;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Providers\File;
// use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AjaxElementInterface;

class BackupFiles extends RawDataTableApi implements ClientArea
{
    use WhmcsParams;
    protected $id = 'backupFileForm';
    protected $name = 'backupFileForm';
    protected $title = 'backupFileForm';
    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-backupFile';

    protected function loadHtml()
    {
        $this->addColumn((new Column('file'))->setSearchable(false)->setOrderable('ASC'));
    }

    public function initContent()
    {
        $this->addMassActionButton(new Buttons\MassAction\DeleteFiles());
    }

    protected function loadData()
    {
        if(isset($_POST['formData'])){
            $dataProvider=new File();
            $response=  $dataProvider->create();
            $response= $response->getData();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['data'=>$response]);
            exit;
        }
        $this->loadAdminApi();
        $result=$this->adminApi->systemBackup->admin_getStep('files');

        $rows=[];
        $total=null;
        foreach($result->files as $service=>$file){
            $rows[]=[
                'id' =>$file,
                'file' =>$file
            ];
        }

        $result = $this->toArray($rows);
        $provider   = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('directory', 'ASC');
        $this->setDataProvider($provider);
    }

    private function toArray($result)
    {
        $resultArray = [];
        foreach($result as $keyRow => $row)
        {
            if(is_object($row))
            {
                foreach($row as $key => $value)
                {
                    $resultArray[$keyRow][$key] = $value;
                }

                continue;
            }
            $resultArray[$keyRow] = $row;
        }

        return $resultArray;
    }

    public function getDirectoryUrl()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'SystemBackup',
            'mg-action'     => 'backupFiles',

        ];

        return 'clientarea.php?'. \http_build_query($params);
    }
    public function getFileUrl()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'SystemBackup',
            'mg-action'     => 'backupFiles',

        ];

        return 'clientarea.php?'. \http_build_query($params);
    }
    public function getSettingUrl()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'SystemBackup',

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
