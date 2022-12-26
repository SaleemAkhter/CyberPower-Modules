<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class AdminPackage extends AbstractCommand
{
    const CMD_SHOW_USERS          = 'CMD_API_SHOW_USERS';
    const CMD_USER_USAGE    = 'CMD_API_SHOW_USER_USAGE';
    const CMD_USER_CONFIG   = 'CMD_API_SHOW_USER_CONFIG';
    const CMD_RESELLER      = 'CMD_API_ACCOUNT_RESELLER';
    const CMD_RESELLER_IPS  = 'CMD_API_SHOW_RESELLER_IPS';
    const CMD_MODIFY_USER   = 'CMD_MODIFY_USER';
    const CMD_RESELLER_PACKAGES ='CMD_API_PACKAGES_USER';
    const CMD_ADMIN_PACKAGES ='CMD_MANAGE_USER_PACKAGES';
    const CMD_ADMIN_SHOW_USER_PACKAGE='CMD_SHOW_USER_PACKAGE';
    const CMD_RESELLER_PACKAGES_MODIFY ='CMD_API_MANAGE_USER_PACKAGES';
    const CMD_MANAGE_RESELLER_PACKAGES='CMD_MANAGE_RESELLER_PACKAGES';
    const CMD_SHOW_RESELLER_PACKAGE='CMD_SHOW_RESELLER_PACKAGE';

    /**
     * list reseller packages
     *
     * @return mixed
     */

    public function list()
    {
        $response = $this->curl->request(self::CMD_ADMIN_PACKAGES,[],['json'=>'yes','ipp'=>500,'bytes'=>'yes']);

        $info=$response->info;
        unset($response->info);

        $list=[];
        foreach ($response as $key => $package) {
            $list[] = array_merge(['name' => $package->package,'id'=>$package->package],$this->info(new Models\Command\AdminPackage(['name' => $package->package])));
        }
        $_SESSION['packages']=$list;
        return $list;

    }

/**
     * list reseller packages
     *
     * @return mixed
     */

    public function listResellerPackages()
    {
        $response = $this->curl->request(self::CMD_MANAGE_RESELLER_PACKAGES,[],['json'=>'yes','ipp'=>500,'bytes'=>'yes']);
        $info=$response->info;
        unset($response->info);

        $list=[];
        foreach ($response as $key => $package) {
            $info=json_decode(json_encode($this->inforesellerpackage(new Models\Command\AdminPackage(['name' => $package->package]))),true);
            $list[] = array_merge(['name' => $package->package,'id'=>$package->package],$info);
        }
        $_SESSION['packages']=$list;
        return $list;

    }




    /**
     * create package
     *
     * @param Models\Command\ResellerPackage $package
     * @return mixed
     */
    public function create( $package)
    {
        return $this->curl->request(self::CMD_RESELLER_PACKAGES_MODIFY, [
            'action'    => 'create',
            'add'       => 'Save',
            'packagename'  => $package['name'],
            'aftp'          => strtoupper($package['aftp']),
            'cgi'           => strtoupper($package['cgi']),
            // 'dns'           =>$package['aftp'],
            'dnscontrol'    => strtoupper($package['dnscontrol']),
            'bandwidth'     => $package['bandwidth'],
            'domainptr'     => $package['domainptr'],
            'ftp'           => $package['ftp'],
            'uemail_daily_limit'=>strtoupper($package['uemail_daily_limit']),
            'email_daily_limit'=>$package['email_daily_limit'],
            'ips'           => $package['ip'],
            'mysql'         => $package['mysql'],
            'nemailf'       => $package['nemailf'],
            'nemailml'      => $package['nemailml'],
            'nemailr'       => $package['nemailr'],
            'nemails'       => $package['nemails'],
            'nsubdomains'   => $package['nsubdomains'],
            'quota'         => $package['quota'],
            'serverip'      =>strtoupper($package['serverIp']),
            'ssh'           => strtoupper($package['ssh']),
            'userssh'       => strtoupper($package['userSsh']),
            'ssl'           => strtoupper($package['ssl']),
            'sysinfo'       => strtoupper($package['sysinfo']),
            'login_keys'    => strtoupper($package['login_keys']),
            'vdomains'      => $package['vdomains'],
            'php'           => strtoupper($package['php']),
            'spam'          => strtoupper($package['spam']),
            'catchall'      => strtoupper($package['catchall']),
            'dnscontrol'    => strtoupper($package['dnscontrol']),
            'suspend_at_limit'=> strtoupper($package['suspend_at_limit']),
            'cron'          => strtoupper($package['cron']),
            'jail'          =>strtoupper($package['jail'])

        ]);
    }

    /**
     * create user account with custom settings
     *
     * @param Models\Command\Account $package
     * @return mixed
     */
    public function createCustomUser(Models\Command\ResellerPackage $package)
    {
        return $this->curl->request(self::CMD_USER, [
            'action'        => $package->getAction(),
            'add'           => $package->getAdd(),
            'username'      => $package->getUsername(),
            'email'         => $package->getEmail(),
            'passwd'        => $package->getPassword(),
            'passwd2'       => $package->getPassword(),
            'domain'        => $package->getDomain(),
            'bandwidth'     => $package->getBandwidth(),
            'quota'         => $package->getQuota(),
            'vdomains'      => $package->getVdomains(),
            'nsubdomains'   => $package->getNsubdomains(),
            'nemails'       => $package->getNemails(),
            'nemailf'       => $package->getNemailf(),
            'nemailml'      => $package->getNemailml(),
            'nemailr'       => $package->getNemailr(),
            'mysql'         => $package->getMysql(),
            'domainptr'     => $package->getDomainptr(),
            'ftp'           => $package->getFtp(),
            'aftp'          => strtoupper($package->getAftp()),
            'cgi'           => strtoupper($package->getCgi()),
            'php'           => strtoupper($package->getPhp()),
            'spam'          => strtoupper($package->getSpam()),
            'cron'          => strtoupper($package->getCron()),
            'catchall'      => strtoupper($package->getCatchall()),
            'ssl'           => strtoupper($package->getSsl()),
            'ssh'           => strtoupper($package->getSsh()),
            'sysinfo'       => strtoupper($package->getSysinfo()),
            'dnscontrol'    => strtoupper($package->getDnscontrol()),
            'ip'            => $package->getIp(),
            'notify'        => $package->getNotify()
        ]);
    }

    public function createReseller(Models\Command\ResellerPackage $package)
    {

        return $this->curl->request(self::CMD_RESELLER, [
            'action'    => $package->getAction(),
            'add'       => $package->getAdd(),
            'username'  => $package->getUsername(),
            'email'     => $package->getEmail(),
            'passwd'    => $package->getPassword(),
            'passwd2'   => $package->getPassword(),
            'domain'    => $package->getDomain(),
            'package'   => $package->getPackage(),
            'ip'        => $package->getIp(),
            'notify'    => $package->getNotify()
        ]);
    }

    public function createCustomReseller(Models\Command\ResellerPackage $package)
    {
        return $this->curl->request(self::CMD_RESELLER, [
            'action'        => $package->getAction(),
            'add'           => $package->getAdd(),
            'name'      => $package->getName(),
            'email'         => $package->getEmail(),
            'passwd'        => $package->getPassword(),
            'passwd2'       => $package->getPassword(),
            'domain'        => $package->getDomain(),
            'bandwidth'     => $package->getBandwidth(),
            'quota'         => $package->getQuota(),
            'vdomains'      => $package->getVdomains(),
            'nsubdomains'   => $package->getNsubdomains(),
            'nemails'       => $package->getNemails(),
            'nemailf'       => $package->getNemailf(),
            'nemailml'      => $package->getNemailml(),
            'nemailr'       => $package->getNemailr(),
            'mysql'         => $package->getMysql(),
            'domainptr'     => $package->getDomainptr(),
            'ftp'           => $package->getFtp(),
            'aftp'          => strtoupper($package->getAftp()),
            'cgi'           => strtoupper($package->getCgi()),
            'php'           => strtoupper($package->getPhp()),
            'spam'          => strtoupper($package->getSpam()),
            'cron'          => strtoupper($package->getCron()),
            'catchall'      => strtoupper($package->getCatchall()),
            'ssl'           => strtoupper($package->getSsl()),
            'ssh'           => strtoupper($package->getSsh()),
            'sysinfo'       => strtoupper($package->getSysinfo()),
            'dnscontrol'    => strtoupper($package->getDnscontrol()),
            'ip'            => $package->getIp(),
            'notify'        => $package->getNotify()
        ]);
    }

    public function usage(Models\Command\ResellersUser $package)
    {

        return $this->curl->request(self::CMD_USER_USAGE,[], [
            'user'  => $package->getUsername()
        ]);
    }

    public function info(Models\Command\AdminPackage $package)
    {
        return $this->curl->request(self::CMD_RESELLER_PACKAGES,[], [
            'package'  => $package->getName()
        ]);
    }
    public function inforesellerpackage(Models\Command\AdminPackage $package)
    {
        return $this->curl->request(self::CMD_SHOW_RESELLER_PACKAGE,[], [
            'package'  => $package->getName(),
            'json'=>'yes',
            'ipp'=>100

        ]);
    }
    public function delete(Models\Command\ResellerPackage $package)
    {
        return $this->curl->request(self::CMD_RESELLER_PACKAGES_MODIFY,[
            'delete'    => 'anything',
            'confirmed' => 'anything',
            'delete0'   => $package->getName()
        ]);
    }
    public function deleteResellerPackage(Models\Command\ResellerPackage $package)
    {
        return $this->curl->request(self::CMD_MANAGE_RESELLER_PACKAGES,[
            'delete'    => 'anything',
            'confirmed' => 'anything',
            'delete0'   => $package->getName()
        ]);
    }


    public function update( $package)
    {

        return $this->curl->request(self::CMD_RESELLER_PACKAGES_MODIFY,[
            'action'    => 'modify',
            'add'       => 'Save',
            'old_packagename'=>$package['old_packagename'],
            'packagename'  => $package['name'],
            'aftp'          => strtoupper($package['aftp']),
            'cgi'           => strtoupper($package['cgi']),
            // 'dns'           =>$package['aftp'],
            'dnscontrol'    => strtoupper($package['dnscontrol']),
            'bandwidth'     => $package['bandwidth'],
            'domainptr'     => $package['domainptr'],
            'ftp'           => $package['ftp'],
            'uemail_daily_limit'=>strtoupper($package['uemail_daily_limit']),
            'email_daily_limit'=>$package['email_daily_limit'],
            'ips'           => $package['ip'],
            'mysql'         => $package['mysql'],
            'nemailf'       => $package['nemailf'],
            'nemailml'      => $package['nemailml'],
            'nemailr'       => $package['nemailr'],
            'nemails'       => $package['nemails'],
            'nsubdomains'   => $package['nsubdomains'],
            'quota'         => $package['quota'],
            'serverip'      =>strtoupper($package['serverIp']),
            'ssh'           => strtoupper($package['ssh']),
            'userssh'       => strtoupper($package['userSsh']),
            'ssl'           => strtoupper($package['ssl']),
            'sysinfo'       => strtoupper($package['sysinfo']),
            'login_keys'    => strtoupper($package['login_keys']),
            'vdomains'      => $package['vdomains'],
            'php'           => strtoupper($package['php']),
            'spam'          => strtoupper($package['spam']),
            'catchall'      => strtoupper($package['catchall']),
            'dnscontrol'    => strtoupper($package['dnscontrol']),
            'suspend_at_limit'=> strtoupper($package['suspend_at_limit']),
            'cron'          => strtoupper($package['cron']),
            'jail'          =>strtoupper($package['jail']),
            'rename'=>$package['rename']
        ]);
    }
    public function createResellerPackage( $package)
    {

        return $this->curl->request(self::CMD_MANAGE_RESELLER_PACKAGES,[
            'json'    => 'yes',
            'add'       => 'Save',
            'old_packagename'=>$package['old_packagename'],
            'packagename'  => $package['name'],
            'aftp'          => strtoupper($package['aftp']),
            'cgi'           => strtoupper($package['cgi']),
            // 'dns'           =>$package['aftp'],
            'dnscontrol'    => strtoupper($package['dnscontrol']),
            'bandwidth'     => $package['bandwidth'],
            'domainptr'     => $package['domainptr'],
            'ftp'           => $package['ftp'],
            'uemail_daily_limit'=>strtoupper($package['uemail_daily_limit']),
            'email_daily_limit'=>$package['email_daily_limit'],
            'ips'           => $package['ips'],
            'mysql'         => $package['mysql'],
            'nemailf'       => $package['nemailf'],
            'nemailml'      => $package['nemailml'],
            'nemailr'       => $package['nemailr'],
            'nemails'       => $package['nemails'],
            'nsubdomains'   => $package['nsubdomains'],
            'quota'         => $package['quota'],
            'serverip'      =>strtoupper($package['serverIp']),
            'oversell'      =>strtoupper($package['oversell']),
            'nusers'=>$package['nusers'],
            'unusers'=>$package['unusers'],
            'ssh'           => strtoupper($package['ssh']),
            'userssh'       => strtoupper($package['userssh']),
            'ssl'           => strtoupper($package['ssl']),
            'sysinfo'       => strtoupper($package['sysinfo']),
            'login_keys'    => strtoupper($package['login_keys']),
            'vdomains'      => $package['vdomains'],
            'php'           => strtoupper($package['php']),
            'spam'          => strtoupper($package['spam']),
            'catchall'      => strtoupper($package['catchall']),
            'dnscontrol'    => strtoupper($package['dnscontrol']),
            'suspend_at_limit'=> strtoupper($package['suspend_at_limit']),
            'cron'          => strtoupper($package['cron']),
            'jail'          =>strtoupper($package['jail']),
        ],['json'=>'yes']);
    }
    public function updateResellerPackage( $package)
    {

        return $this->curl->request(self::CMD_MANAGE_RESELLER_PACKAGES,[
            'json'    => 'yes',
            'add'       => 'Save',
            'old_packagename'=>$package['old_packagename'],
            'packagename'  => $package['name'],
            'aftp'          => strtoupper($package['aftp']),
            'cgi'           => strtoupper($package['cgi']),
            // 'dns'           =>$package['aftp'],
            'dnscontrol'    => strtoupper($package['dnscontrol']),
            'bandwidth'     => $package['bandwidth'],
            'domainptr'     => $package['domainptr'],
            'ftp'           => $package['ftp'],
            'uemail_daily_limit'=>strtoupper($package['uemail_daily_limit']),
            'email_daily_limit'=>$package['email_daily_limit'],
            'ips'           => $package['ips'],
            'mysql'         => $package['mysql'],
            'nemailf'       => $package['nemailf'],
            'nemailml'      => $package['nemailml'],
            'nemailr'       => $package['nemailr'],
            'nemails'       => $package['nemails'],
            'nsubdomains'   => $package['nsubdomains'],
            'quota'         => $package['quota'],
            'serverip'      =>strtoupper($package['serverIp']),
            'oversell'      =>strtoupper($package['oversell']),
            'nusers'=>$package['nusers'],
            'unusers'=>$package['unusers'],
            'ssh'           => strtoupper($package['ssh']),
            'userssh'       => strtoupper($package['userssh']),
            'ssl'           => strtoupper($package['ssl']),
            'sysinfo'       => strtoupper($package['sysinfo']),
            'login_keys'    => strtoupper($package['login_keys']),
            'vdomains'      => $package['vdomains'],
            'php'           => strtoupper($package['php']),
            'spam'          => strtoupper($package['spam']),
            'catchall'      => strtoupper($package['catchall']),
            'dnscontrol'    => strtoupper($package['dnscontrol']),
            'suspend_at_limit'=> strtoupper($package['suspend_at_limit']),
            'cron'          => strtoupper($package['cron']),
            'jail'          =>strtoupper($package['jail']),
            'rename'=>$package['rename']
        ],['json'=>'yes']);
    }



    public function upgradeConfigOptions(Models\Command\ResellerPackage $package)
    {
        $data = [
            'json'          => 'yes',
            'action'        => $package->getAction(),
            'bytes'         => 'yes',
            'user'          => $package->getUsername(),
            'bandwidth'     => $package->getBandwidth(),
            'ubandwidth'    => $package->getUBandwidth(),
            'domainptr'     => $package->getDomainptr(),
            'udomainptr'    => $package->getUdomainptr(),
            'ftp'           => $package->getFtp(),
            'uftp'          => $package->getUftp(),
            'inode'         => $package->getInode(),
            'uinode'        => $package->getUinode(),
            'mysql'         => $package->getMysql(),
            'umysql'        => $package->getUmysql(),
            'nemailf'       => $package->getNemailf(),
            'unemailf'      => $package->getUnemailf(),
            'nemailml'      => $package->getNemailml(),
            'unemailml'     => $package->getUnemailml(),
            'nemailr'       => $package->getNemailr(),
            'unemailr'      => $package->getUnemailr(),
            'nemails'       => $package->getNemails(),
            'unemails'      => $package->getUnemails(),
            'nsubdomains'   => $package->getNsubdomains(),
            'unsubdomains'  => $package->getUnsubdomains(),
            'quota'         => $package->getQuota(),
            'uquota'        => $package->getUquota(),
            'vdomains'      => $package->getVdomains(),
            'uvdomains'     => $package->getUvdomains(),
            'aftp'          => strtoupper($package->getAftp()),
            'catchall'      => strtoupper($package->getCatchall()),
            'cgi'           => strtoupper($package->getCgi()),
            'cron'          => strtoupper($package->getCron()),
            'dnscontrol'    => strtoupper($package->getDnscontrol()),
            'php'           => strtoupper($package->getPhp()),
            'spam'          => strtoupper($package->getSpam()),
            'ssh'           => strtoupper($package->getSsh()),
            'ssl'           => strtoupper($package->getSsl()),
            'suspend_at_limit' => strtoupper($package->getSuspendAtLimit()),
            'sysinfo'       => strtoupper($package->getSysinfo())
        ];

        return  $this->curl->request(self::CMD_MODIFY_USER, $data);
    }
}
