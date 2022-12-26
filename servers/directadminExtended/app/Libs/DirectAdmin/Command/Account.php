<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Account extends AbstractCommand
{
    const CMD_USER          = 'CMD_API_ACCOUNT_USER';
    const CMD_USER_USAGE    = 'CMD_API_SHOW_USER_USAGE';
    const CMD_SHOW_RESELLER    = 'CMD_SHOW_RESELLER';
    const CMD_USER_CONFIG   = 'CMD_API_SHOW_USER_CONFIG';
    const CMD_RESELLER      = 'CMD_API_ACCOUNT_RESELLER';
    const CMD_RESELLER_IPS  = 'CMD_API_SHOW_RESELLER_IPS';
    const CMD_MODIFY_USER   = 'CMD_MODIFY_USER';

    /**
     * create user account
     *
     * @param Models\Command\Account $account
     * @return mixed
     */
    public function createUser(Models\Command\Account $account)
    {
        return $this->curl->request(self::CMD_USER, [
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

    /**
     * create user account with custom settings
     *
     * @param Models\Command\Account $account
     * @return mixed
     */
    public function createCustomUser(Models\Command\Account $account)
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

    public function createReseller(Models\Command\Account $account)
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

    public function createCustomReseller(Models\Command\Account $account)
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

    public function usage(Models\Command\Account $account)
    {
        return $this->curl->request(self::CMD_USER_USAGE,[], [
            'user'  => $account->getUsername()
        ]);
    }

    public function config(Models\Command\Account $account)
    {
        return $this->curl->request(self::CMD_USER_CONFIG,[], [
            'user'  => $account->getUsername()
        ]);
    }
    public function resellerusage(Models\Command\Account $account)
    {
        return $this->curl->request(self::CMD_SHOW_RESELLER,[], [
            'user'  => $account->getUsername()
        ]);
    }

    public function resellerconfig(Models\Command\Account $account)
    {
        return $this->curl->request(self::CMD_USER_CONFIG,[], [
            'user'  => $account->getUsername()
        ]);
    }

    public function upgradeConfigOptions(Models\Command\Account $account)
    {
        $data = [
            'json'          => 'yes',
            'action'        => $account->getAction(),
            'bytes'         => 'yes',
            'user'          => $account->getUsername(),
            'bandwidth'     => $account->getBandwidth(),
            'ubandwidth'    => $account->getUBandwidth(),
            'domainptr'     => $account->getDomainptr(),
            'udomainptr'    => $account->getUdomainptr(),
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
            'sysinfo'       => strtoupper($account->getSysinfo())
        ];

        return  $this->curl->request(self::CMD_MODIFY_USER, $data);
    }
}
