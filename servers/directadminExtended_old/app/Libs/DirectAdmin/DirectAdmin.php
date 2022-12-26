<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 12:48
 */
namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Account;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\ApacheHandler;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Autoresponder;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Backup;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Cron;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Database;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Domain;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\DomainForwarder;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\DomainPointer;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Email;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\EmailFilter;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\EmailForwarder;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\FileManager;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Ftp;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Ip;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\MailingList;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Package;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\PerlModules;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Spamassassin;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Ssl;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Subdomain;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\SystemInfo;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command\Vacation;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Rule;

/**
 * Class DirectAdmin
 * @package ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin
 * @property Account $account
 * @property ApacheHandler $apacheHandler
 * @property Autoresponder $autoresponder
 * @property Backup $backup
 * @property Cron $cron
 * @property Database $database
 * @property Domain $domain
 * @property DomainForwarder $domainForwarder
 * @property DomainPointer $domainPointer
 * @property Email $email
 * @property EmailFilter $emailFilter
 * @property EmailForwarder $emailForwarder
 * @property FileManager $fileManager
 * @property Ftp $ftp
 * @property Ip $ip
 * @property MailingList $mailingList
 * @property Package $package
 * @property PerlModules $perlModules
 * @property Spamassassin $spamassassin
 * @property Ssl $ssl
 * @property Subdomain $subdomain
 * @property SystemInfo $systemInfo
 * @property Vacation $vacation
 *
 */
class DirectAdmin
{
    private $params;
    private $paramsRule;
    private $apiRule;
    private $userMode = false;

    public function __construct(array $params)
    {
        $this->paramsRule = new Rule\ConnectionParams();
        $this->apiRule    = new Rule\CommandExists();
        $this->setParams($params);
    }

    private function setParams($params)
    {
        $this->paramsRule->isSatisfiedBy($params);
        $this->params = $params;

        return $this;
    }

    public function __get($command)
    {
        if(!is_null($this->$command))
        {
            return $this->$command;
        }

        return $this->getCommand($command);
    }

    private function getCommand($command)
    {
        $this->apiRule->isSatisfiedBy($command);
        $class = __NAMESPACE__ . '\Command\\' . ucfirst($command);
        $this->$command = new $class;
        $this->$command->setConnection($this->params, $this->userMode);

        return $this->$command;
    }

    public function setUserMode($userMode = true)
    {
        if(is_bool($userMode))
        {
            $this->userMode = $userMode;
        }

        return $this;
    }
}