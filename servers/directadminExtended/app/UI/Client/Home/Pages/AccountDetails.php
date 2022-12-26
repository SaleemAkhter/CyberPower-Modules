<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Account;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\User;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;
use ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;

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
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount" ){
            $this->loadResellerApi([],false);
            $this->loadFeaturesSettingsList($this->getWhmcsParamByKey('packageid'));
            $this->prepareResellerDataToShow();
        }elseif($this->getWhmcsParamByKey('producttype')  == "server" && Helper\isAdminLoggedInAsReseller()){
            $this->loadResellerApi([],true);
            $this->loadFeaturesSettingsList($this->getWhmcsParamByKey('packageid'));
            $this->prepareResellerDataToShow();
        }else{
            $this->loadUserApi();
            $this->loadFeaturesSettingsList($this->getWhmcsParamByKey('packageid'));
            $this->prepareDataToShow();
        }

        $provider = new ArrayDataProvider();
        $provider->setData($this->data);

        $this->setDataProvider($provider);

    }
    private function prepareResellerDataToShow()
    {

        if(Helper\isResellerLevel()){

            $info = json_decode(json_encode($this->resellerApi->reseller->usage(new User(['username' => $this->getWhmcsParamByKey('username')]))),true);
            $config = json_decode(json_encode($this->resellerApi->reseller->config(new User(['username' => $this->getWhmcsParamByKey('username')]))),true);


        }elseif(Helper\isAdminLevel() && Helper\isAdminLoggedInAsReseller()){

            $infodata = json_decode(json_encode($this->resellerApi->reseller->adminresellerusage(new User(['username' => $this->getWhmcsParamByKey('username')]))),true);
            foreach ($infodata['stats'] as $key => $stat) {
                $info[$stat['setting']]=$stat['usage'];
                $usagelimit[$stat['setting']]=$stat['allocated'];
            }

            $config = json_decode(json_encode($this->resellerApi->reseller->config(new User(['username' => $this->getWhmcsParamByKey('username')]))),true);
            // debug($info);debug($usagelimit);debug($config);die("slkjaslklkj");

        }else{
            $this->resellerApi->reseller->listTickets();
            $detail = json_decode(json_encode($this->resellerApi->reseller->resellerusage(new User(['username' => $this->getWhmcsParamByKey('username')]))),true);
            $ips=$this->resellerApi->Ip->getIpListWithDetail();

            $mainip='';
            foreach ($ips as $key => $ip) {
                if($ip['status']=='server'){
                    $mainip=$ip['address'];
                    break;
                }
            }
            $info=$detail['usage'];
            $config=$detail['limit'];
            $config['ip']=$mainip;
        }

        // debug($config);
        //     debug($info);
        //     die();
        if(!Helper\isResellerLevel()){
            $this->getLoggedInAs($this->getWhmcsParamByKey('username'),$config['ip']);
            $this->getLabels('currentUsage', 'allocated');
            $this->getDiskSpace($this->toMegabyte($info['quota']), $config['quota']);
            $this->getBandwidth($this->toMegabyte($info['bandwidth']), $config['bandwidth']);
            $this->getEmails($info['nemails'], $config['nemails']);
            $this->getFTPAccount($info['ftp'], $config['ftp']);
            $this->getDatabase($info['mysql'], $config['mysql']);
            $this->getDomain($info['vdomains'], $config['vdomains']);
            $this->getUser($info['nusers'], $config['nusers']);

        }elseif(!Helper\isAdminLevel() && Helper\isAdminLoggedInAsReseller()){
            $this->getLoggedInAs($this->getWhmcsParamByKey('username'),$config['ip']);
            $this->getLabels('currentUsage', 'allocated');
            $this->getDiskSpace($this->toMegabyte($info['quota']), $config['quota']);
            $this->getBandwidth($this->toMegabyte($info['bandwidth']), $config['bandwidth']);
            $this->getEmails($info['nemails'], $config['nemails']);
            $this->getFTPAccount($info['ftp'], $config['ftp']);
            $this->getDatabase($info['mysql'], $config['mysql']);
            $this->getDomain($info['vdomains'], $config['vdomains']);
            $this->getUser($info['nusers'], $usagelimit['nusers']);

        }else{

            $this->getLoggedInAs($this->getWhmcsParamByKey('username'),$config['ip']);
            $this->getLabels('currentUsage', 'allocated');
            $this->getDiskSpace($info['quota'], $config['quota']);
            $this->getBandwidth($info['bandwidth'], $config['bandwidth']);
            $this->getEmails($info['nemails'], $config['nemails']);
            $this->getFTPAccount($info['ftp'], $config['ftp']);
            $this->getDatabase($info['mysql'], $config['mysql']);

        }
    }
    private function prepareDataToShow()
    {

        $info = $this->userApi->account->usage(new Account(['username' => $this->getWhmcsParamByKey('username')]));
        $config = $this->userApi->account->config(new Account(['username' => $this->getWhmcsParamByKey('username')]));
        if(is_object($config)){
            $config=json_decode(json_encode($config),true);
        }
        if(is_object($info)){
            $info=json_decode(json_encode($info),true);
        }
        $this->getLoggedInAs($this->getWhmcsParamByKey('username'),$config['ip']);
        // $this->getIp($config['ip'], $config['ip']);
        $this->getDiskSpace($info['quota'], $config['quota']);
        $this->getBandwidth($info['bandwidth'], $config['bandwidth']);
        $this->getEmails($info['nemails'], $config['nemails']);
        $this->getFTPAccount($info['ftp'], $config['ftp']);
        $this->getDatabase($info['mysql'], $config['mysql']);


    }
    private function getLabels($used, $allocated)
    {
        $this->loadLang();
        $this->setTableData('', $this->lang->translate($used), $this->lang->translate($allocated));
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
            $this->setTableData('email', $used, $this->formatLimitValue($max));
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
            $this->setTableData('ftp', $used, $this->formatLimitValue($max));
        }
    }
    private function getDatabase($used, $max)
    {
        if($this->featuresSettingsList->usage_database == "on")
        {
            $this->setTableData('database', $used, $this->formatLimitValue($max));
        }
    }
    private function getDomain($used, $max)
    {
        if($this->featuresSettingsList->usage_domain == "on")
        {
            $this->setTableData('domain', $used, $this->formatLimitValue($max));
        }
    }
    private function getUser($used, $max)
    {
        if($this->featuresSettingsList->usage_user == "on")
        {
            $this->setTableData('user', $used, $this->formatLimitValue($max));
        }
    }
    private function getLoggedInAs($username, $ip)
    {
        $this->loadLang();
        $ip='<span class="iplabel">'.$this->lang->translate('ip').'</span><span class="ip">'. $ip.'</span>';
        $loggedinAs='<span class="userlabel">'.$this->lang->translate('loggedInAs').'</span><span class="username">'. $username.'</span>';
        $this->setTableData($loggedinAs, $ip, "",true);
    }
    private function getIp($ip)
    {
            $this->setTableData('ip', $ip,$this->formatLimitValue($max));
    }

    protected function formatLimitValue($value)
    {
        if((int) $value > 0 || empty($value))
        {
            return $value;
        }

        return di(Lang::class)->absoluteTranslate('unlimited');
    }

    protected function toMegabyte($number)
    {
        if(is_numeric($number))
        {
            return round($number / 1024 / 1024, 3);
        }
        return $number;
    }

    private function setTableData($name, $used, $max,$skiptranslate=false)
    {
        $this->loadLang();

        $this->data[] = [
            'name' => ($skiptranslate || empty($name))?$name:$this->lang->translate($name),
            'used' => $used,
            'max' => ($max === 'unlimited')?  $this->lang->translate($max) : $max
        ];
    }
}

