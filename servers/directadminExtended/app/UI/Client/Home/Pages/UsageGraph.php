<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Home\Pages;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Builder\BaseContainer;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Account;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\User;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;

class UsageGraph extends BaseContainer implements ClientArea
{
    use DirectAdminAPIComponent;
    protected $id    = 'startAccountSection';
    protected $name  = 'startAccountSection';
    protected $title = 'startAccountSection';
    protected $description="";
    protected $diskpercent=300;
    protected $diskusage=0;
    protected   $disklimit=1000;
    protected   $bwusage=200;
    protected   $bwlimit=1000;
    protected   $bwpercent=200;

    public function __construct($baseId = null, $hasAlert = false,$type = null)
    {
        parent::__construct($baseId);
        if($this->getWhmcsParamByKey('producttype')  == "reselleraccount"){
            $this->loadResellerApi([],false);
            $this->prepareResellerDataToShow();
        }elseif($this->getWhmcsParamByKey('producttype')  == "server" && Helper\isAdminLoggedInAsReseller()){
            $this->loadResellerApi([],true);
            $this->prepareResellerDataToShow();
        }else{
            $this->loadUserApi();
            $this->prepareDataToShow();
        }


    }
    private function prepareResellerDataToShow()
    {
        if(Helper\isResellerLevel()){
            $info = json_decode(json_encode($this->resellerApi->reseller->usage(new User(['username' => $this->getWhmcsParamByKey('username')]))),true);
            $config = json_decode(json_encode($this->resellerApi->reseller->config(new User(['username' => $this->getWhmcsParamByKey('username')]))),true);
            $userinfo=$this->resellerApi->messageSystem->info();
            if(isset($userinfo->NEWMESSAGES)){
                Request::build()->addSession('unreadmessagecount', $userinfo->NEWMESSAGES);
            }
        }elseif(Helper\isAdminLevel() && Helper\isAdminLoggedInAsReseller()){
            $infodata = json_decode(json_encode($this->resellerApi->reseller->adminresellerusage(new User(['username' => $this->getWhmcsParamByKey('username')]))),true);
            foreach ($infodata['stats'] as $key => $stat) {
                $info[$stat['setting']]=$stat['usage'];
                $usagelimit[$stat['setting']]=$stat['allocated'];
            }
            $config = json_decode(json_encode($this->resellerApi->reseller->config(new User(['username' => $this->getWhmcsParamByKey('username')]))),true);
            $this->loadResellerApi([],false);
            $userinfo=$this->resellerApi->messageSystem->info();
            if(isset($userinfo->NEWMESSAGES)){
                Request::build()->addSession('unreadmessagecount', $userinfo->NEWMESSAGES);
            }
        }else{
            $detail = json_decode(json_encode($this->resellerApi->reseller->resellerusage(new User(['username' => $this->getWhmcsParamByKey('username')]))),true);
            $info=$detail['usage'];
            $config=$detail['limit'];
            $userinfo=$this->resellerApi->messageSystem->info();
            if(isset($userinfo->NEWMESSAGES)){
                Request::build()->addSession('unreadmessagecount', $userinfo->NEWMESSAGES);
            }
        }
        if(!Helper\isResellerLevel()){
            $this->setDisklimit($config['quota']);
            $this->setDiskusage($this->toMegabyte($info['quota']));
            $this->setBwlimit($config['bandwidth']);
            $this->setBwusage($this->toMegabyte($info['bandwidth']));
        }else{
            $this->setDisklimit($config['quota']);
            $this->setDiskusage($info['quota']);
            $this->setBwlimit($config['bandwidth']);
            $this->setBwusage($info['bandwidth']);
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
        $this->setDisklimit($config['quota']);
        $this->setDiskusage($info['quota']);
        $this->setBwlimit($config['bandwidth']);
        $this->setBwusage($info['bandwidth']);

    }
    public function isImpersonated()
    {
        if ((isset($_SESSION['resellerloginas'],$_SESSION['resellerloginas'][$this->getWhmcsParamByKey('serviceid')]) &&  !empty($_SESSION['resellerloginas'])) || (isset($_SESSION['adminloginas'],$_SESSION['adminloginas'][$this->getWhmcsParamByKey('serviceid')]) &&  !empty($_SESSION['adminloginas'][$this->getWhmcsParamByKey('serviceid')]))){
            $oldsessionsrole=Request::build()->getSession('adminloginasrole');
            return $oldsessionsrole[$this->getWhmcsParamByKey('serviceid')];
        }

        return false;
    }
    public function impersonationLabel()
    {

        $oldsessionsrole=Request::build()->getSession('adminloginasrole');
        $currentrole=$oldsessionsrole[$this->getWhmcsParamByKey('serviceid')];

        $oldsessions=Request::build()->getSession('resellerloginas');
        if($currentrole=='admin' || ($currentrole=='user' && !isset($oldsessions[$this->getWhmcsParamByKey('serviceid')]))){
            return 'backtoadminlevel';
        }else{
            return 'backtoresellerlevel';
        }

        return false;
    }
    public function getMessagesCount()
    {
        return (isset($_SESSION['unreadmessagecount']))?$_SESSION['unreadmessagecount']:"";
    }
    public function getDisklimit()
    {
        return $this->disklimit;
    }

    public function setDisklimit($limit)
    {
        if($limit=="unlimited"){
            $limit="∞";
        }
        $this->disklimit = $limit;
        return $this;
    }
    public function getDiskpercent()
    {
        if(!is_numeric($this->disklimit)){
            return 0;
        }
        return (int)($this->diskusage*100/$this->disklimit);
    }

    public function setDiskpercent($diskpercent)
    {
        $this->diskpercent = $diskpercent;
        return $this;
    }
    public function getDiskusage()
    {
        return $this->diskusage;
    }

    public function setDiskusage($usage)
    {
        $this->diskusage = $usage;
        return $this;
    }
    public function getBwlimit()
    {
        return $this->bwlimit;
    }

    public function setBwlimit($limit)
    {
        if($limit=="unlimited"){
            $limit="∞";
        }
        $this->bwlimit = $limit;
        return $this;
    }
    public function getBwusage()
    {
        return $this->bwusage;
    }

    public function setBwusage($bwusage)
    {
        $this->bwusage = $bwusage;
        return $this;
    }
    public function getBwpercent()
    {
        if(!is_numeric($this->bwlimit)){
            return 0;
        }
        return (int)($this->bwusage*100/$this->bwlimit);
    }

    public function setBwpercent($bwpercent)
    {
        $this->bwpercent = $bwpercent;
        return $this;
    }

    public function getAssetsUrl()
    {
        return BuildUrl::getBaseUrl();
    }
    protected function toMegabyte($number)
    {
        if(is_numeric($number))
        {
            return round($number / 1024 / 1024, 3);
        }
        return $number;
    }
    public function mainUrl()
    {
        return BuildUrl::getUrl();
    }
}
