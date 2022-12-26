<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Account;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;

class AccountDetails extends DataTableApi implements ClientArea
{
    use \ModulesGarden\Servers\DirectAdminExtended\App\Traits\ProductsFeatureComponent,
        \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    protected $id    = 'accountDetailsTable';
    protected $name  = 'accountDetailsTable';
    protected $title = 'accountDetails';

    protected $data = [];

    protected function loadHtml()
    {
        $this->addColumn((new Column('name')))
        ->addColumn((new Column('used'))->setClass('lu-text-center'))
        ->addColumn((new Column('max'))->setClass('lu-text-center'));
    }

    public function initContent()
    {
        $this->disabledViewTopBody()->disabledViewFooter();
    }

    protected function loadData()
    {
        $this->loadUserApi();
        $this->loadFeaturesSettingsList($this->getWhmcsParamByKey('packageid'));
        $this->prepareDataToShow();

        $provider = new ArrayDataProvider();
        $provider->setData($this->data);

        $this->setDataProvider($provider);

    }

    private function prepareDataToShow()
    {

        $info = $this->userApi->account->usage(new Account(['username' => $this->getWhmcsParamByKey('username')]));
        $config = $this->userApi->account->config(new Account(['username' => $this->getWhmcsParamByKey('username')]));

        $this->getDiskSpace($info['quota'], $config['quota']);
        $this->getBandwidth($info['bandwidth'], $config['bandwidth']);
        $this->getEmails($info['nemails'], $config['nemails']);
        $this->getFTPAccount($info['ftp'], $config['ftp']);
        $this->getDatabase($info['mysql'], $config['mysql']);

    }

    private function getBandwidth($used, $max)
    {
        if($this->featuresSettingsList->usage_bandwidth == "on")
        {
            $this->setTableData('bandwidth', $used, $max);
        }
    }
    private function getEmails($used, $max)
    {
        if($this->featuresSettingsList->usage_email == "on")
        {
            $this->setTableData('email', $used, $max);
        }
    }
    private function getDiskSpace($used, $max)
    {
        if($this->featuresSettingsList->usage_disk == "on")
        {
            $this->setTableData('disk', $used, $max);
        }
    }
    private function getFTPAccount($used, $max)
    {
        if($this->featuresSettingsList->usage_ftp == "on")
        {
            $this->setTableData('ftp', $used, $max);
        }
    }
    private function getDatabase($used, $max)
    {
        if($this->featuresSettingsList->usage_database == "on")
        {
            $this->setTableData('database', $used, $max);
        }
    }

    private function setTableData($name, $used, $max)
    {
        $this->loadLang();

        $this->data[] = [
            'name' => $this->lang->translate($name),
            'used' => $used,
            'max' => ($max === 'unlimited')?  $this->lang->translate($max) : $max
        ];
    }
}
