<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Pages;

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
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\ListSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\TableSection;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\UserManager\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\Select;

class UserInfo extends BaseStandaloneForm implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id = 'userInfo';
    protected $name = 'userInfo';
    protected $title = '';

    public function initContent()
    {

        $username=$_GET['actionElementId'];
        /*TODO add validation so that only admin can access this page*/
        $userinfo=[];
        $this->loadAdminApi();

        $result     = $this->adminApi->reseller->userinfo($username);
        $skipsettings=['ip','language','skin','additional_bandwidth'];
        if(isset($result->stats)){
            foreach ($result->stats as $key => $row) {
                if(in_array($row->setting, $skipsettings)  || !isset($row->usage)){
                    continue;
                }
                $userinfo[$row->setting]=$row;
            }
        }
        // debug($userinfo);die();
        $userinfo=json_decode(json_encode($userinfo),true);

        $bandwidthlimit=($this->bytesToHuman($userinfo['bandwidth']['max_usage']=="unlimited"))?'Unlimited':$this->bytesToHuman($userinfo['bandwidth']['max_usage']);

        $userdetail=[
            [
                'bandwidth',
                $this->bytesToHuman($userinfo['bandwidth']['usage']),
                ($userinfo['bandwidth']['max_usage']=="unlimited")?'Unlimited':$this->bytesToHuman($userinfo['bandwidth']['max_usage'])
            ],
            [
                'diskusage',
                $this->bytesToHuman($userinfo['quota']['usage']),
                ($userinfo['quota']['max_usage']=="unlimited")?'Unlimited':$this->bytesToHuman($userinfo['quota']['max_usage'])
            ],
            [
                'email_quota',
                $this->bytesToHuman($userinfo['email_quota']['usage']),
                ($userinfo['email_quota']['max_usage']=="unlimited")?'Unlimited':$this->bytesToHuman($userinfo['email_quota']['max_usage'])
            ],
            [
                'db_quota',
                $this->bytesToHuman($userinfo['db_quota']['usage']),
                ($userinfo['db_quota']['max_usage']=="unlimited")?'Unlimited':$userinfo['db_quota']['max_usage']
            ],
            [
                'inode',
                $userinfo['inode']['usage'],
                ($userinfo['inode']['max_usage']=="unlimited")?'Unlimited':$userinfo['inode']['max_usage']
            ],
            [
                'vdomains',
                $userinfo['vdomains']['usage'],
                ($userinfo['vdomains']['max_usage']=="unlimited")?'Unlimited':$userinfo['vdomains']['max_usage']
            ],
            [
                'nsubdomains',
                $userinfo['nsubdomains']['usage'],
                ($userinfo['nsubdomains']['max_usage']=="unlimited")?'Unlimited':$userinfo['nsubdomains']['max_usage']
            ],
            [
                'nemails',
                $userinfo['nemails']['usage'],
                ($userinfo['nemails']['max_usage']=="unlimited")?'Unlimited':$userinfo['nemails']['max_usage']
            ],
            [
                'nemailf',
                $userinfo['nemailf']['usage'],
                ($userinfo['nemailf']['max_usage']=="unlimited")?'Unlimited':$userinfo['nemailf']['max_usage']
            ],
            [
                'nemailml',
                $userinfo['nemailml']['usage'],
                ($this->bytesToHuman($userinfo['nemailml']['max_usage']=="unlimited"))?'Unlimited':$userinfo['nemailml']['max_usage']
            ],
            [
                'nemailr',
                $userinfo['nemailr']['usage'],
                ($userinfo['nemailr']['max_usage']=="unlimited")?'Unlimited':$userinfo['nemailr']['max_usage']
            ],
            [
                'email_deliveries_outgoing',
                $userinfo['email_deliveries_outgoing']['usage'],
                (($userinfo['email_deliveries_outgoing']['max_usage']=="unlimited")?'Unlimited':$userinfo['email_deliveries_outgoing']['max_usage'])."/ Day"
            ],
            [
                'email_deliveries_incoming',
                $userinfo['email_deliveries_incoming']['usage'],
                ($userinfo['email_deliveries_incoming']['max_usage']=="unlimited")?'Unlimited':$userinfo['email_deliveries_incoming']['max_usage']
            ],
            [
                'mysql',
                $userinfo['mysql']['usage'],
                ($userinfo['mysql']['max_usage']=="unlimited")?'Unlimited':$userinfo['mysql']['max_usage']
            ],
            [
                'domainptr',
                $userinfo['domainptr']['usage'],
                ($userinfo['domainptr']['max_usage']=="unlimited")?'Unlimited':$userinfo['domainptr']['max_usage']
            ],
            [
                'ftp',
                $userinfo['ftp']['usage'],
                ($userinfo['ftp']['max_usage']=="unlimited")?'Unlimited':$userinfo['ftp']['max_usage']
            ],
        ];


        $this->addReplacement();
        $this->setRawTitle("<h6 class='pt-20'><strong>View User :   $username</strong></h6>");
        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\UserInfo());
        $this->dataProvider->read();
        $data=$this->dataProvider->getData();




        $tableSection=new TableSection('currentips' );

        $tableSection->setMainContainer($formBox)->setContainerClasss(['lu-col-md-8']);
        $tableSection->setHeaders([
            'Setting',
            'Usage',
            'Limit'
        ]);
        // debug($userinfo);die();
        $tableSection->setItems($userdetail);

        $this->addSection($formBox)->addSection($tableSection);
        // $this->addSection($this->getSkinFieldsSection());


        $this->loadDataToForm();


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
    protected function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }

}
