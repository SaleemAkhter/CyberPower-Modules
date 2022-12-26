<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class MxRecord extends AbstractCommand
{
    const CMD_DOMAIN        = 'CMD_API_DOMAIN';
    const CMD_DOMAIN_V2     = 'CMD_DOMAIN';
    const CMD_SHOW_DOMAINS  = 'CMD_API_SHOW_DOMAINS';
    const CMD_ADDITIONAL    = 'CMD_API_ADDITIONAL_DOMAINS';
    const CMD_ADDITIONAL_V2 = 'CMD_ADDITIONAL_DOMAINS';
    const CMD_STATS_DOMAIN  = 'CMD_API_SHOW_USER_DOMAINS';
    const CMD_SHOW_LOG      = 'CMD_SHOW_LOG';
    const CMD_DNS_MX        = 'CMD_DNS_MX';

    const UBANDWIDTH        = 'ubandwidth';
    const UQUOTA            = 'uquota';
    /**
     * list domains
     *
     * @return mixed
     */
    public function lists()
    {
        $response = $this->curl->request(self::CMD_SHOW_DOMAINS);

        return $this->loadResponse(new Models\Command\Domain(), $response);
    }

    /**
     * create domain
     *
     * @param Models\Command\Domain $domain
     * @return mixed
     */
    public function create(Models\Command\Domain $domain)
    {
        $this->curl->request(self::CMD_DOMAIN, [
            'action'    => __FUNCTION__,
            'domain'    => $domain->getName(),
            'bandwidth' => $domain->getBandwidth(),
            'quota'     => $domain->getQuota(),
            'uquota'    => $domain->getUquota(),
            'ssl'       => $domain->getSsl(),
            'cgi'       => $domain->getCgi(),
            'php'       => $domain->getPhp()
        ]);

        return $this->updateForceSsl($domain);
    }

    /**
     * @param Models\Command\Domain $domain
     */
    public function update(Models\Command\Domain $domain)
    {
        $this->curl->request(self::CMD_DOMAIN_V2, [
            'action'        => 'modify',
            'domain'        => $domain->getName(),
            'bandwidth'     => $domain->getBandwidth(),
            'quota'         =>  $domain->getQuota(),
            ($domain->getBandwidth() === 'unlimited') ? self::UBANDWIDTH : '' => $domain->getBandwidth(),
            ($domain->getQuota()  === 'unlimited') ?  self::UQUOTA : ''  => $domain->getQuota(),
            'ssl'           =>  ($domain->getSsl() == 'ON') ? $domain->getSsl() : '',
            'cgi'           =>  ($domain->getCgi() == 'ON') ? $domain->getCgi() : '',
            'php'           =>  ($domain->getPhp() == 'ON') ? $domain->getPhp() : '',
            'json'          => 'yes',
            'www_pointers'  => $domain->getRedirect(),
            'form_version'  =>  '1.1'
        ]);

        return  $this->updateForceSsl($domain);

    }

    public function updatePhpVersion(Models\Command\Domain $domain)
    {
        $this->curl->request(self::CMD_DOMAIN_V2, [
            'php1_select'   => $domain->getPhp1Select(),
            'php2_select'   => $domain->getPhp2Select(),
            'domain'        => $domain->getName(),
            'json'          => 'yes',
            'action'        => 'php_selector',
            'save'          => 'yes'
        ]);
    }

    public function updateForceSsl(Models\Command\Domain $domain)
    {
       return $this->curl->request(self::CMD_DOMAIN_V2, [
            'action'        => 'private_html',
            'json'          => 'yes',
            'val'           => 'symlink',
            'domain'        => $domain->getName(),
            'force_ssl'     => $domain->getForceSsl()
        ]);
    }

    /**
     * delete domain
     *
     * @param Models\Command\Domain $domain
     * @return mixed
     */
    public function delete(Models\Command\Domain $domain)
    {
        return $this->curl->request(self::CMD_DOMAIN, [
            'delete'    => 'anything',
            'confirmed' => 'anything',
            'select0'   => $domain->getName(),
        ]);
    }

    /**
     * delete many domains
     *
     * @param array $domains
     * @return mixed
     */
    public function deleteMany(array $domains)
    {
        $data = [
            'delete'    => 'anything',
            'confirmed' => 'anything'
        ];
        foreach($domains as $key => $domain)
        {
            $data['select'.$key] = $domain->getName();
        }

        return $this->curl->request(self::CMD_DOMAIN, $data);
    }

    /**
     * view domain
     *
     * @param Models\Command\Domain $domain
     * @return mixed
     */
    public function view(Models\Command\Domain $domain)
    {
        return $this->curl->request(self::CMD_ADDITIONAL, [
            'action'    => __FUNCTION__,
            'domain'    => $domain->getName()
        ]);
    }

    /**
     * @param Models\Command\Domain $domain
     * @return mixed
     */
    public function getStats(Models\Command\Domain $domain)
    {
        $response  = $this->curl->request(self::CMD_ADDITIONAL, [], [
            'domain'    => $domain->getName()
        ]);

        return $this->loadResponse(new Models\Command\DomainDetails(), $response);

    }

    public function getStatsToEdit(Models\Command\Domain $domain)
    {
        return $this->curl->request(self::CMD_ADDITIONAL_V2, [], [
            'domain'    => $domain->getName(),
            'json'      => 'yes',
            'action'    => 'view'
        ]);
    }

    public function getLocalMail(Models\Command\Domain $domain)
    {
        $response  = $this->curl->request(self::CMD_ADDITIONAL, [], [
            'domain'    => $domain->getName()
        ]);

        return $this->loadResponse(new Models\Command\DomainDetails(), $response);
    }

    public function getLogs(Models\Command\Domain $domain, $type = 'error')
    {
        $response = $this->curl->customRequest(self::CMD_SHOW_LOG, [], [
            'domain' => $domain->getName(),
            'type' => $type
        ]);

        return $this->loadResponse(new Models\Command\Logs(), $response);
    }

    public function setLocalMail(Models\Command\Domain $domain)
    {
        return  $this->curl->request(self::CMD_DNS_MX, [
            'action'   => 'internal',
            'domain'    => $domain->getName(),
            'internal' =>  $domain->getLocalMail()
        ]);
    }

    public function viewInJson(Models\Command\Domain $domain)
    {
        return $this->curl->request(self::CMD_ADDITIONAL, [], [
            'json'      => 'yes',
            'action'    => 'view',
            'domain'    => $domain->getName()
        ]);
    }

    public function suspendUnsuspend(Models\Command\Domain $domain)
    {
        return $this->curl->request(self::CMD_DOMAIN_V2, [], [
            'json'      => 'yes',
            'action'    => 'select',
            'select0'    => $domain->getName(),
            'suspend'   => 'yes'
        ]);
    }

    public function suspendUnsuspendMany(array $suspendData)
    {
        $data = [
            'json'    => 'yes',
            'action' => 'select',
            'suspend' => 'yes',

        ];
        foreach($suspendData as $key => $value)
        {
            $data['select'.$key] = $value->getName();
        }

        return $this->curl->request(self::CMD_DOMAIN_V2, $data);
    }
}
