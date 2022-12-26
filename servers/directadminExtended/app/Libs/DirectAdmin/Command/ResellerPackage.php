<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class ResellerPackage extends AbstractCommand
{
    const CMD_SHOW_USERS          = 'CMD_API_SHOW_USERS';
    const CMD_USER_USAGE    = 'CMD_API_SHOW_USER_USAGE';
    const CMD_USER_CONFIG   = 'CMD_API_SHOW_USER_CONFIG';
    const CMD_RESELLER      = 'CMD_API_ACCOUNT_RESELLER';
    const CMD_RESELLER_IPS  = 'CMD_API_SHOW_RESELLER_IPS';
    const CMD_MODIFY_USER   = 'CMD_MODIFY_USER';
    const CMD_RESELLER_PACKAGES ='CMD_API_PACKAGES_USER';
    const CMD_RESELLER_PACKAGES_MODIFY ='CMD_API_MANAGE_USER_PACKAGES';



    /**
     * list reseller packages
     *
     * @return mixed
     */

    public function list()
    {
        $response = $this->curl->request(self::CMD_RESELLER_PACKAGES);
        $list=[];
        foreach ($response['list'] as $key => $name) {
            $list[] = array_merge(['name' => $name,'id'=>$name],$this->info(new Models\Command\ResellerPackage(['name' => $name])));
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
    public function create(Models\Command\ResellerPackage $package)
    {
        return $this->curl->request(self::CMD_RESELLER_PACKAGES_MODIFY, [
            'action'    => 'create',
            'add'       => 'Save',
            'packagename'  => $package->getName(),
            'aftp'          => strtoupper($package->getAftp()),
            'cgi'           => strtoupper($package->getCgi()),
            'dns'           =>$package->getAftp(),
            'dnscontrol'    => strtoupper($package->getDnscontrol()),
            'bandwidth'     => $package->getBandwidth(),
            'domainptr'     => $package->getDomainptr(),
            'ftp'           => $package->getFtp(),
            'ips'           => $package->getIp(),
            'mysql'         => $package->getMysql(),
            'nemailf'       => $package->getNemailf(),
            'nemailml'      => $package->getNemailml(),
            'nemailr'       => $package->getNemailr(),
            'nemails'       => $package->getNemails(),
            'nsubdomains'   => $package->getNsubdomains(),
            'quota'         => $package->getQuota(),
            'serverip'      =>strtoupper($package->getServerIp()),
            'ssh'           => strtoupper($package->getSsh()),
            'userssh'       => strtoupper($package->getUserSsh()),
            'ssl'           => strtoupper($package->getSsl()),
            'vdomains'      => $package->getVdomains(),
            'php'           => strtoupper($package->getPhp()),
            'spam'          => strtoupper($package->getSpam()),
            'catchall'      => strtoupper($package->getCatchall()),
            'dnscontrol'    => strtoupper($package->getDnscontrol()),
            'suspend_at_limit'=> strtoupper($package->getSuspendAtLimit()),
            'cron'          => strtoupper($package->getCron()),

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

    public function info(Models\Command\ResellerPackage $package)
    {
        return $this->curl->request(self::CMD_RESELLER_PACKAGES,[], [
            'package'  => $package->getName()
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
    public function update(Models\Command\ResellerPackage $package)
    {

        return $this->curl->request(self::CMD_RESELLER_PACKAGES_MODIFY,[
            'action'    => 'modify',
            'add'       => 'Save',
            'packagename'  => $package->getName(),
            'aftp'          => strtoupper($package->getAftp()),
            'cgi'           => strtoupper($package->getCgi()),
            'dns'           =>$package->getAftp(),
            'dnscontrol'    => strtoupper($package->getDnscontrol()),
            'bandwidth'     => $package->getBandwidth(),
            'domainptr'     => $package->getDomainptr(),
            'ftp'           => $package->getFtp(),
            'ips'           => $package->getIp(),
            'mysql'         => $package->getMysql(),
            'nemailf'       => $package->getNemailf(),
            'nemailml'      => $package->getNemailml(),
            'nemailr'       => $package->getNemailr(),
            'nemails'       => $package->getNemails(),
            'nsubdomains'   => $package->getNsubdomains(),
            'quota'         => $package->getQuota(),
            'serverip'      =>strtoupper($package->getServerIp()),
            'ssh'           => strtoupper($package->getSsh()),
            'userssh'       => strtoupper($package->getUserSsh()),
            'ssl'           => strtoupper($package->getSsl()),
            'vdomains'      => $package->getVdomains(),
            'php'           => strtoupper($package->getPhp()),
            'spam'          => strtoupper($package->getSpam()),
            'catchall'      => strtoupper($package->getCatchall()),
            'dnscontrol'    => strtoupper($package->getDnscontrol()),
            'jail'    => strtoupper($package->getJail()),
            'cron'          => strtoupper($package->getCron()),
            'suspend_at_limit'=> strtoupper($package->getSuspendAtLimit()),
        ]);
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
