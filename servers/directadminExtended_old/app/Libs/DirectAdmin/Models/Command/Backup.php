<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 16:37
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class Backup extends AbstractModel implements ResponseLoad
{
    public $file;
    protected $backupDomain;
    protected $domain;
    protected $subdomain;
    protected $email;
    protected $email_data;
    protected $forwarder;
    protected $autoresponder;
    protected $vacation;
    protected $list;
    protected $emailsettings;
    protected $ftp;
    protected $ftpsettings;
    protected $database;
    protected $database_data;
    protected $dns;

    protected $ftpIp;
    protected $ftpUsername;
    protected $ftpPassword;
    protected $ftpPath;
    protected $ftpPort;
    protected $ftpSecure;
    protected $selectID;

    protected $backupName;

    protected $localPath;

    public function loadResponse($response, $function = null)
    {
        foreach($response['list'] as $backup)
        {
            $this->addResponseElement(new self(['file' => $backup]));
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param mixed $domain
     * @return Backup
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * @param mixed $subdomain
     * @return Backup
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return Backup
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getForwarder()
    {
        return $this->forwarder;
    }

    /**
     * @param mixed $forwarder
     * @return Backup
     */
    public function setForwarder($forwarder)
    {
        $this->forwarder = $forwarder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAutoresponder()
    {
        return $this->autoresponder;
    }

    /**
     * @param mixed $autoresponder
     * @return Backup
     */
    public function setAutoresponder($autoresponder)
    {
        $this->autoresponder = $autoresponder;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVacation()
    {
        return $this->vacation;
    }

    /**
     * @param mixed $vacation
     * @return Backup
     */
    public function setVacation($vacation)
    {
        $this->vacation = $vacation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param mixed $list
     * @return Backup
     */
    public function setList($list)
    {
        $this->list = $list;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailsettings()
    {
        return $this->emailsettings;
    }

    /**
     * @param mixed $emailsettings
     * @return Backup
     */
    public function setEmailsttings($emailsettings)
    {
        $this->emailsettings = $emailsettings;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFtp()
    {
        return $this->ftp;
    }

    /**
     * @param mixed $ftp
     * @return Backup
     */
    public function setFtp($ftp)
    {
        $this->ftp = $ftp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFtpsettings()
    {
        return $this->ftpsettings;
    }

    /**
     * @param mixed $ftpsettings
     * @return Backup
     */
    public function setFtpsettings($ftpsettings)
    {
        $this->ftpsettings = $ftpsettings;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param mixed $database
     * @return Backup
     */
    public function setDatabase($database)
    {
        $this->database = $database;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return Backup
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBackupDomain()
    {
        return $this->backupDomain;
    }

    /**
     * @param mixed $backupDomain
     * @return Backup
     */
    public function setBackupDomain($backupDomain)
    {
        $this->backupDomain = $backupDomain;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail_data()
    {
        return $this->email_data;
    }

    /**
     * @param mixed $email_data
     * @return Backup
     */
    public function setEmailData($email_data)
    {
        $this->email_data = $email_data;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getDatabase_data()
    {
        return $this->database_data;
    }

    /**
     * @param mixed $databaseData
     * @return Backup
     */
    public function setDatabaseData($database_data)
    {
        $this->database_data = $database_data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFtpIp()
    {
        return $this->ftpIp;
    }

    /**
     * @param mixed $ftpIp
     * @return Backup
     */
    public function setFtpIp($ftpIp)
    {
        $this->ftpIp = $ftpIp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFtpUsername()
    {
        return $this->ftpUsername;
    }

    /**
     * @param mixed $ftpUsername
     * @return Backup
     */
    public function setFtpUsername($ftpUsername)
    {
        $this->ftpUsername = $ftpUsername;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFtpPassword()
    {
        return $this->ftpPassword;
    }

    /**
     * @param mixed $ftpPassword
     * @return Backup
     */
    public function setFtpPassword($ftpPassword)
    {
        $this->ftpPassword = $ftpPassword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFtpPath()
    {
        return $this->ftpPath;
    }

    /**
     * @param mixed $ftpPath
     * @return Backup
     */
    public function setFtpPath($ftpPath)
    {
        $this->ftpPath = $ftpPath;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFtpPort()
    {
        return $this->ftpPort;
    }

    /**
     * @param mixed $ftpPort
     * @return Backup
     */
    public function setFtpPort($ftpPort)
    {
        $this->ftpPort = $ftpPort;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFtpSecure()
    {
        return $this->ftpSecure;
    }

    /**
     * @param mixed $ftpSecure
     * @return Backup
     */
    public function setFtpSecure($ftpSecure)
    {
        $this->ftpSecure = $ftpSecure;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocalPath()
    {
        return $this->localPath;
    }

    /**
     * @param mixed $localPath
     * @return Backup
     */
    public function setLocalPath($localPath)
    {
        $this->localPath = $localPath;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSelectID()
    {
        return 'select'.$this->selectID;
    }

    /**
     * @param mixed $selectID
     * @return Backup
     */
    public function setSelectID($selectID)
    {
        $this->selectID = $selectID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDns()
    {
        return $this->dns;
    }

    /**
     * @param mixed $dns
     */
    public function setDns($dns)
    {
        $this->dns = $dns;
    }

   public function setBackupName($backupName)
   {
       $this->backupName = $backupName;
   }

   public function getBackupName()
   {
       return $this->backupName;
   }

}