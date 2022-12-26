<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Reseller extends AbstractCommand
{
    const CMD_RESELLER_STATS       ='CMD_RESELLER_STATS';
    const CMD_SHOW_USERS          = 'CMD_API_SHOW_USERS';
    const CMD_USER_USAGE    = 'CMD_API_SHOW_USER_USAGE';
    const CMD_USER_CONFIG   = 'CMD_API_SHOW_USER_CONFIG';
    const CMD_RESELLER      = 'CMD_API_ACCOUNT_RESELLER';
    const CMD_RESELLER_IPS  = 'CMD_API_SHOW_RESELLER_IPS';
    const CMD_MODIFY_USER   = 'CMD_API_MODIFY_USER';
    const CMD_USER_PASSWD   = 'CMD_API_USER_PASSWD';
    const CMD_ACCOUNT_USER  = 'CMD_API_ACCOUNT_USER';
    const CMD_SELECT_USERS  = 'CMD_API_SELECT_USERS';
    const CMD_RESELLER_PACKAGES ='CMD_API_PACKAGES_USER';
    const CMD_TICKETS   ='CMD_API_TICKET';
    const CMD_NAME_SERVER       ='CMD_NAME_SERVER';
    const CMD_ALL_USER_SHOW     ='CMD_ALL_USER_SHOW';
    const CMD_SHOW_USER ='CMD_SHOW_USER';
    const CMD_MOVE_USERS='CMD_MOVE_USERS';
    const CMD_ACCOUNT_RESELLER='CMD_ACCOUNT_RESELLER';
    const CMD_RESELLER_SHOW='CMD_RESELLER_SHOW';
    const CMD_MODIFY_RESELLER='CMD_MODIFY_RESELLER';
    const CMD_SELECT_RESELLER_USERS='CMD_SELECT_USERS';
    const CMD_SHOW_RESELLER    = 'CMD_SHOW_RESELLER';

    /**
     * list reseller users
     *
     * [
            'json'=>'yes',
            'ipp'=>'500',
            'bytes'=>'yes',
            'key'=>'username',
            'order'=>'ASC',
            'sort1'=>'1'
        ]
     * @return mixed
     */

        public function listUsers()
        {

            $response = $this->curl->request(self::CMD_SHOW_USERS,[]);
            $list=[];
            foreach ($response['list'] as $key => $username) {
                $usage = $this->usage(new Models\Command\User(['username' => $username]));
                $userinfo = $this->config(new Models\Command\User(['username' => $username]));
                $list[]=array_merge($userinfo,[
                    'username'=>$username,
                    'usage'=>$usage
                ]);
            }
            return $list;

        }
        public function moveuserlist()
        {
            return  $this->curl->request(self::CMD_MOVE_USERS,[],['json'=>'yes','ipp'=>500]);
        }
    /**
     * create user account
     *
     * @param Models\Command\Account $account
     * @return mixed
     */
    public function moveUser( $data)
    {
        // {"creator":"admin","json":"yes","action":"moveusers","select0":"maomao"}:
        // json: yes
        // Request URL: http://193.31.29.55:2222/CMD_MOVE_USERS?json=yes
        $userstomove=$data['users'];
        $postdata=[
            'action'    => 'moveusers',
            'json'=>'yes',
            'creator'  => $data['creator'],
        ];
        unset($data['users']);
        foreach ($userstomove as $key => $user) {
            $postdata['select'.$key]=$user;
        }

        return $this->curl->request(self::CMD_MOVE_USERS, $postdata);
    }
    public function listAllUsers()
    {
        $sortcolmap=[
            'username'=>1,
            'bandwidth'=>3,
            'disk'=>4,
            'vdomains'=>5,
            'domain'=>8,
            'sent_emails'=>9
        ];
        if(isset($_GET['iSortCol_0'])){
            $sorby=$sortcolmap[$_GET['iSortCol_0']];
            $sortorder=(isset($_GET['sSortDir_0']))?$_GET['sSortDir_0']:"asc";
        }else{
            $sorby=1;
            $sortorder='asc';
        }
        if($sortorder=='desc'){
            $sorby=(-$sorby);
        }

        $data= $this->curl->request(self::CMD_ALL_USER_SHOW,[],['json'=>'yes','ipp'=>100,'order'=>$sortorder,'sort1'=>$sorby]);
        if(isset($data->add_leave_dns)){
            unset($data->add_leave_dns);
        }
        if(isset($data->info)){
            unset($data->info);
        }
        if(isset($data->reasons)){
            unset($data->reasons);
        }
        return $data;

    }
    public function listResellerUsers()
    {

        $data= $this->curl->request(self::CMD_RESELLER_SHOW,[],['json'=>'yes','ipp'=>100]);
        if(isset($data->add_leave_dns)){
            unset($data->add_leave_dns);
        }
        if(isset($data->info)){
            unset($data->info);
        }
        if(isset($data->reasons)){
            unset($data->reasons);
        }
        return $data;

    }
    public function listTickets()
    {

        $response = $this->curl->request(self::CMD_TICKETS,[],[
            'json'=>'yes',
            'action'=>'view',
            'last_messages'=>5,
            'new'=>1
        ]);
        $response=json_decode(json_encode($response),true);
        $list=[];
        foreach ($response['list'] as $key => $username) {
            $usage = $this->usage(new Models\Command\User(['username' => $username]));
            $userinfo = $this->config(new Models\Command\User(['username' => $username]));
            $list[]=array_merge($userinfo,[
                'username'=>$username,
                'usage'=>$usage
            ]);
        }
        return $list;

    }


    public function userinfo($username)
    {

        return  $this->curl->request(self::CMD_SHOW_USER,[],[
            'json'=>'yes',
            'user'=>$username,
            'bytes'=>'yes',
            'tab'=>'usage',
            'ipp'=>100
        ]);


    }
    /**
     * list reseller packages
     *
     * @return mixed
     */

    public function listPackages()
    {

        $response = $this->curl->request(self::CMD_RESELLER_PACKAGES);
        $list=[];
        foreach ($response['list'] as $key => $name) {
            $userinfo = $this->config(new Models\Command\ResellersPackage(['username' => $name]));
            $list[]=array_merge($userinfo,[
                'username'=>$username,
                'usage'=>$usage
            ]);
        }
        return $list;

    }
    public function getIPs()
    {
        if(isset($_SESSION['userips'])){
            return $_SESSION['userips'];
        }
        $response = $this->curl->request(self::CMD_RESELLER_IPS, [], [
            'ipp' => '100000'
        ]);
        $iplist=[];
        foreach ($response['list'] as $key => $ip) {
            $ipdetail = $this->curl->request(self::CMD_RESELLER_IPS, [], [
                'ip' => $ip
            ]);
            if($ipdetail){
                $iplist[]=array_merge(['ip'=>$ip],$ipdetail);
            }
        }
        $_SESSION['userips']=$iplist;
        return $iplist;

    }

    // /**
    //  * create user account
    //  *
    //  * @param Models\Command\Account $account
    //  * @return mixed
    //  */
    // public function createReseller(Models\Command\User $account)
    // {

    //     return $this->curl->request(self::CMD_ACCOUNT_RESELLER, [
    //         'action'    => 'create',
    //         'add'       => 'yes',
    //         'json'=>'yes',
    //         'username'  => $account->getUsername(),
    //         'email'     => $account->getEmail(),
    //         'passwd'    => $account->getPassword(),
    //         'passwd2'   => $account->getPassword(),
    //         'domain'    => $account->getDomain(),
    //         'package'   => $account->getPackage(),
    //         'ip'        => $account->getIp(),
    //         'notify'    => $account->getNotify()
    //     ]);
    // }
    /**
     * create user account
     *
     * @param Models\Command\Account $account
     * @return mixed
     */
    public function createUser(Models\Command\User $account)
    {
        return $this->curl->request(self::CMD_ACCOUNT_USER, [
            'action'    => 'create',
            'add'       => 'save',
            'username'  => $account->getUsername(),
            'email'     => $account->getEmail(),
            'passwd'    => $account->getPassword(),
            'passwd2'   => $account->getPassword(),
            'domain'    => $account->getDomain(),
            'package'   => $account->getPackage(),
            'ip'        => $account->getIp(),
            'notify'    => $account->getNotify()
        ]);
    }
    /**
     * modify user account
     *
     * @param Models\Command\Account $account
     * @return mixed
     */
    public function modifyUser(Models\Command\User $account)
    {
        // debug($account);
        // debug([
        //     'action'    => 'modify',
        //     'add'       => 'save',
        //     'user'  => $account->getUsername(),
        //     'name'  => $account->getName(),
        //     'email'     => $account->getEmail(),
        //     'passwd'    => $account->getPassword(),
        //     'passwd2'   => $account->getPassword(),
        //     'domain'    => $account->getDomain(),
        //     'package'   => $account->getPackage(),
        //     'ip'        => $account->getIp(),
        //     'ns1'        => $account->getNs1(),
        //     'ns2'        => $account->getNs2(),
        //     'skin'        => $account->getSkin(),
        //     'language'        => $account->getLanguage(),

        // ]);
        // die();
        return $this->curl->request(self::CMD_MODIFY_USER, [
            'action'    => 'modify',
            'add'       => 'save',
            'user'  => $account->getUsername(),
            'name'  => $account->getName(),
            'email'     => $account->getEmail(),
            'passwd'    => $account->getPassword(),
            'passwd2'   => $account->getPassword(),
            'domain'    => $account->getDomain(),
            'package'   => $account->getPackage(),
            'ip'        => $account->getIp(),
            'ns1'        => $account->getNs1(),
            'ns2'        => $account->getNs2(),
            'skin'        => $account->getSkin(),
            'language'        => $account->getLanguage(),

        ]);
    }
    public function modifyUserPackage(Models\Command\User $account)
    {

        return $this->curl->request(self::CMD_MODIFY_USER, [
            'action'    => 'package',
            'user'  => $account->getUsername(),
            'package'   => $account->getPackage()
        ]);
    }
    public function modifyUserIp(Models\Command\User $account)
    {

        return $this->curl->request(self::CMD_MODIFY_USER, [
            'action'    => 'ip',
            'user'  => $account->getUsername(),
            'ip'   => $account->getIp()
        ]);
    }
    public function modifyUserEmailLimit( $account)
    {

        return $this->curl->request(self::CMD_MODIFY_USER, [
            'action'    => 'single',
            'user'  => $account['username'],
            'email_limit_value'   => $account['email_limit_value'],
            'email_limit'=>'Save Limit'
        ]);
    }

    public function modifyUserName(Models\Command\User $account)
    {
        return $this->curl->request(self::CMD_MODIFY_USER, [
            'action'    => 'single',
            'name'=> "Save Name",
            'user'  => $account->getUsername(),
            'evalue'=> $account->getEmail(),
            'nvalue'   => $account->getName(),
            'ns1'        => $account->getNs1(),
            'ns2'        => $account->getNs2(),
            'lvalue'        => $account->getLanguage(),
        ]);
    }
    public function modifyUserEmail(Models\Command\User $account)
    {
        return $this->curl->request(self::CMD_MODIFY_USER, [
            'action'    => 'single',
            'name'=> "Save E-Mail",
            'user'  => $account->getUsername(),
            'evalue'=> $account->getEmail(),
            'nvalue'   => $account->getName(),
            'ns1'        => $account->getNs1(),
            'ns2'        => $account->getNs2(),
            'lvalue'        => $account->getLanguage(),
        ]);
    }
    public function modifyUserSkin(Models\Command\User $account)
    {
        return $this->curl->request(self::CMD_MODIFY_USER, [
            'action'    => 'single',
            'name'=> "Save Skin",
            'user'  => $account->getUsername(),
            'evalue'=> $account->getEmail(),
            'nvalue'   => $account->getName(),
            'ns1'        => $account->getNs1(),
            'ns2'        => $account->getNs2(),
            'lvalue'        => $account->getLanguage(),
        ]);
    }
    public function modifyUserPassword(Models\Command\User $account)
    {
        return $this->curl->request(self::CMD_USER_PASSWD, [
            'username'  => $account->getUsername(),
            'passwd'=> $account->getPassword(),
            'passwd2'   => $account->getPassword()
        ]);
    }
    public function suspendUnsuspend(Models\Command\User $account)
    {
        return $this->curl->request(self::CMD_SELECT_USERS,  [
            'json'      => 'yes',
            'action'    => 'select',
            'select0'   => $account->getName(),
            'suspend'   => 'yes',
            'dosuspend' =>'Suspend'
        ]);
    }
    public function delete(Models\Command\User $account)
    {
        return $this->curl->request(self::CMD_SELECT_USERS,  [
            'json'      => 'yes',
            'action'    => 'select',
            'select0'   => $account->getName(),
            'suspend'   => 'yes',
            'dosuspend' =>'Suspend'
        ]);
    }
    public function deleteReseller(Models\Command\User $account)
    {

        return $this->curl->request(self::CMD_SELECT_RESELLER_USERS,  [
            'json'      => 'yes',
            'select0'   => $account->getName(),
            'location'=>'CMD_SHOW_RESELLER',
            'delete'=>'yes',
            'confirmed'=>'yes',
        ],['json'=>'yes']);
    }



    public function changePassword($data)
    {
        return $this->curl->request(self::CMD_USER_PASSWD, $data,[]);
    }
    public function getNameservers()
    {

        return $this->curl->request(self::CMD_NAME_SERVER,[],[
           'json'=>'yes',
           'ipp'=> 50
       ]);
    }
    public function changeNameservers($data)
    {

        return $this->curl->request(self::CMD_NAME_SERVER,$data,[]);
    }
    /**
     * create user account with custom settings
     *
     * @param Models\Command\Account $account
     * @return mixed
     */
    public function createCustomUser(Models\Command\ResellersUser $account)
    {
        return $this->curl->request(self::CMD_USER, [
            'action'        => $account->getAction(),
            'add'           => $account->getAdd(),
            'username'      => $account->getUsername(),
            'email'         => $account->getEmail(),
            'passwd'        => $account->getPassword(),
            'passwd2'       => $account->getPassword(),
            'domain'        => $account->getDomain(),
            'bandwidth'     => $account->getBandwidth(),
            'quota'         => $account->getQuota(),
            'vdomains'      => $account->getVdomains(),
            'nsubdomains'   => $account->getNsubdomains(),
            'nemails'       => $account->getNemails(),
            'nemailf'       => $account->getNemailf(),
            'nemailml'      => $account->getNemailml(),
            'nemailr'       => $account->getNemailr(),
            'mysql'         => $account->getMysql(),
            'domainptr'     => $account->getDomainptr(),
            'ftp'           => $account->getFtp(),
            'aftp'          => strtoupper($account->getAftp()),
            'cgi'           => strtoupper($account->getCgi()),
            'php'           => strtoupper($account->getPhp()),
            'spam'          => strtoupper($account->getSpam()),
            'cron'          => strtoupper($account->getCron()),
            'catchall'      => strtoupper($account->getCatchall()),
            'ssl'           => strtoupper($account->getSsl()),
            'ssh'           => strtoupper($account->getSsh()),
            'sysinfo'       => strtoupper($account->getSysinfo()),
            'dnscontrol'    => strtoupper($account->getDnscontrol()),
            'ip'            => $account->getIp(),
            'notify'        => $account->getNotify()
        ]);
    }

    public function createReseller(Models\Command\ResellersUser $account)
    {


        return $this->curl->request(self::CMD_RESELLER, [
            'action'    => $account->getAction(),
            'add'       => $account->getAdd(),
            'username'  => $account->getUsername(),
            'email'     => $account->getEmail(),
            'passwd'    => $account->getPassword(),
            'passwd2'   => $account->getPassword(),
            'domain'    => $account->getDomain(),
            'package'   => $account->getPackage(),
            'ip'        => $account->getIp(),
            'notify'    => $account->getNotify()
        ]);
    }

    public function createCustomReseller(Models\Command\ResellersUser $account)
    {
        return $this->curl->request(self::CMD_RESELLER, [
            'action'        => $account->getAction(),
            'add'           => $account->getAdd(),
            'username'      => $account->getUsername(),
            'email'         => $account->getEmail(),
            'passwd'        => $account->getPassword(),
            'passwd2'       => $account->getPassword(),
            'domain'        => $account->getDomain(),
            'bandwidth'     => $account->getBandwidth(),
            'quota'         => $account->getQuota(),
            'vdomains'      => $account->getVdomains(),
            'nsubdomains'   => $account->getNsubdomains(),
            'nemails'       => $account->getNemails(),
            'nemailf'       => $account->getNemailf(),
            'nemailml'      => $account->getNemailml(),
            'nemailr'       => $account->getNemailr(),
            'mysql'         => $account->getMysql(),
            'domainptr'     => $account->getDomainptr(),
            'ftp'           => $account->getFtp(),
            'aftp'          => strtoupper($account->getAftp()),
            'cgi'           => strtoupper($account->getCgi()),
            'php'           => strtoupper($account->getPhp()),
            'spam'          => strtoupper($account->getSpam()),
            'cron'          => strtoupper($account->getCron()),
            'catchall'      => strtoupper($account->getCatchall()),
            'ssl'           => strtoupper($account->getSsl()),
            'ssh'           => strtoupper($account->getSsh()),
            'sysinfo'       => strtoupper($account->getSysinfo()),
            'dnscontrol'    => strtoupper($account->getDnscontrol()),
            'ip'            => $account->getIp(),
            'notify'        => $account->getNotify()
        ]);
    }

    public function usage(Models\Command\User $account)
    {
        return $this->curl->request(self::CMD_USER_USAGE,[], [
            'user'  => $account->getUsername(),
            'json'=>'yes',
            'bytes'=>'yes'
        ]);
    }
    public function adminresellerusage(Models\Command\User $account)
    {

        return $this->curl->request(self::CMD_SHOW_RESELLER,[], [
            'user'  => $account->getUsername(),
            'json'=>'yes',
            'bytes'=>'yes',
            'tab'=>'usage',
            'ipp'=>50
        ]);
    }
    public function resellerusage(Models\Command\User $account)
    {

        return $this->curl->request(self::CMD_RESELLER_STATS,[], [
            'user'  => $account->getUsername(),
            'json'=>'yes',
            'bytes'=>'yes',
            'tab'=>'usage',
            'ipp'=>50
        ]);
    }

    public function config(Models\Command\User $account)
    {

        return $this->curl->request(self::CMD_USER_CONFIG,[], [
            'user'  => $account->getUsername()
        ]);
    }
    public function resellerConfig(Models\Command\User $account)
    {
        return $this->curl->request(self::CMD_MODIFY_RESELLER,[], [
            'user'  => $account->getUsername(),
            'json'=>'yes',
            'bytes'=>'yes',
            'tab'=>'modify',
            'ipp'=>50
        ]);
    }

    public function upgradeResellerConfigOptions($account)
    {
        $data = [
            'json'          => 'yes',
            'action'        => "customize",
            'bytes'         => 'yes',
            'user'          => $account['username'],
            'bandwidth'     => $account['bandwidth'],
            'ubandwidth'    => $account['ubandwidth'],
            'domainptr'     => $account['domainptr'],
            'udomainptr'    => $account['udomainptr'],
            'ftp'           => $account['ftp'],
            'uftp'          => $account['uftp'],
            'inode'         => $account['inode'],
            'uinode'        => $account['uinode'],
            'mysql'         => $account['mysql'],
            'umysql'        => $account['umysql'],
            'nemailf'       => $account['nemailf'],
            'unemailf'      => $account['unemailf'],
            'nemailml'      => $account['nemailml'],
            'unemailml'     => $account['unemailml'],
            'nemailr'       => $account['nemailr'],
            'unemailr'      => $account['unemailr'],
            'nemails'       => $account['nemails'],
            'unemails'      => $account['unemails'],
            'nsubdomains'   => $account['nsubdomains'],
            'unsubdomains'  => $account['unsubdomains'],
            'quota'         => $account['quota'],
            'uquota'        => $account['uquota'],
            // 'userssh'       => strtoupper($package['userSsh']),
            'vdomains'      => $account['vdomains'],
            'uvdomains'     => $account['uvdomains'],
            'nusers'=>$account['nusers'],
            'unusers'=>$account['unusers'],
            'aftp'          => $account['aftp'],
            'catchall'      => $account['catchall'],
            'cgi'           => $account['cgi'],
            'cron'          => $account['cron'],
            'dnscontrol'    => $account['dnscontrol'],
            'php'           => $account['php'],
            'spam'          => $account['spam'],
            'ssh'           => $account['ssh'],
            'userssh'           => $account['userssh'],
            'oversell'           => $account['oversell'],
            'ssl'           => $account['ssl'],
            'sysinfo'       => $account['sysinfo'],
            'login_keys'       => $account['login_keys'],
            'jail'=>$account['uvdomains']
        ];
        return  $this->curl->request(self::CMD_MODIFY_RESELLER, $data);
    }
    public function upgradeConfigOptions(Models\Command\User $account)
    {
        $data = [
            'json'          => 'yes',
            'action'        => "customize",
            'bytes'         => 'yes',
            'user'          => $account->getUsername(),
            'bandwidth'     => $account->getBandwidth(),
            'ubandwidth'    => $account->getUBandwidth(),
            'domainptr'     => $account->getDomainptr(),
            'udomainptr'    => $account->getUdomainptr(),
            'uemail_daily_limit'=>strtoupper($account->getUemail_daily_limit()),
            'email_daily_limit'=>$account->getEmail_daily_limit(),
            'ftp'           => $account->getFtp(),
            'uftp'          => $account->getUftp(),
            'inode'         => $account->getInode(),
            'uinode'        => $account->getUinode(),
            'mysql'         => $account->getMysql(),
            'umysql'        => $account->getUmysql(),
            'nemailf'       => $account->getNemailf(),
            'unemailf'      => $account->getUnemailf(),
            'nemailml'      => $account->getNemailml(),
            'unemailml'     => $account->getUnemailml(),
            'nemailr'       => $account->getNemailr(),
            'unemailr'      => $account->getUnemailr(),
            'nemails'       => $account->getNemails(),
            'unemails'      => $account->getUnemails(),
            'nsubdomains'   => $account->getNsubdomains(),
            'unsubdomains'  => $account->getUnsubdomains(),
            'quota'         => $account->getQuota(),
            'uquota'        => $account->getUquota(),
            'vdomains'      => $account->getVdomains(),
            'uvdomains'     => $account->getUvdomains(),
            'aftp'          => strtoupper($account->getAftp()),
            'catchall'      => strtoupper($account->getCatchall()),
            'cgi'           => strtoupper($account->getCgi()),
            'cron'          => strtoupper($account->getCron()),
            'dnscontrol'    => strtoupper($account->getDnscontrol()),
            'php'           => strtoupper($account->getPhp()),
            'spam'          => strtoupper($account->getSpam()),
            'ssh'           => strtoupper($account->getSsh()),
            'ssl'           => strtoupper($account->getSsl()),
            'suspend_at_limit' => strtoupper($account->getSuspendAtLimit()),
            'sysinfo'       => strtoupper($account->getSysinfo()),
            'login_keys'       => strtoupper($account->getLogin_keys()),
            'jail'=>$account->getJail()
        ];
        // debug(array_filter($data));die();
        return  $this->curl->request(self::CMD_MODIFY_USER, $data);
    }
}
