<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Backup extends AbstractCommand
{
    const BACKUP           = 'backup';
    const CMD_SITE_BACKUP  = 'CMD_API_SITE_BACKUP';
    const CMD_API_FILE_MANAGER = 'CMD_API_FILE_MANAGER';
    const CMD_API_FILE_MANAGER_V2 = 'CMD_FILE_MANAGER';
    const CMD_API_ADMIN_BACKUP = 'CMD_API_ADMIN_BACKUP';
    const CMD_API_FTP   = 'CMD_API_FTP';
    const BACKUP_ITEMS     = [
        'domain',
        'subdomain',
        'email',
        'email_data',
        'emailsettings',
        'forwarder',
        'autoresponder',
        'vacation',
        'list',
        'ftp',
        'ftpsettings',
        'database',
        'database_data',
        'dns'
    ];

    /**
     * get backups list
     *
     * @param Models\Command\Backup $backup
     * @return mixed
     */
    public function lists(Models\Command\Backup $backup)
    {
        $response = $this->curl->request(self::CMD_SITE_BACKUP, [
            'domain' => $backup->getDomain()
        ]);

        return $this->loadResponse(new Models\Command\Backup(), $response);
    }

    public function getLocalList(Models\Command\Backup $backup)
    {

        return $this->curl->request(self::CMD_API_ADMIN_BACKUP, [
            'local_path' => $backup->getLocalPath()
        ]);

    }

    public function getListFromFTP(Models\Command\Backup $backup)
    {
        return $this->curl->request(self::CMD_API_ADMIN_BACKUP, [
            'action'        => 'update_files',
            'where'         => 'ftp',
            'ftp_ip'        => $backup->getFtpIp(),
            'ftp_username'  => $backup->getFtpUsername(),
            'ftp_password'  => $backup->getFtpPassword(),
            'ftp_path'      => $backup->getFtpPath(),
            'ftp_port'      => $backup->getFtpPort(),
        ]);
    }

    public function restoreLocal(Models\Command\Backup $backup)
    {
        return $this->curl->request(self::CMD_API_ADMIN_BACKUP, [
            'domain' => $backup->getBackupDomain(),
            'action' => 'restore',
            'where'  => 'local',
            'local_path' => $backup->getLocalPath(),
            $backup->getSelectID() => $backup->getFile(),
        ]);

    }



    public function restoreFTP(Models\Command\Backup $backup)
    {
        return $this->curl->request(self::CMD_API_ADMIN_BACKUP, [
            'domain' => $backup->getBackupDomain(),
            'action' => 'restore',
            'where'  => 'ftp',
            'ftp_ip' => $backup->getFtpIp(),
            'ftp_username' => $backup->getFtpUsername(),
            'ftp_password' => $backup->getFtpPassword(),
            'ftp_path' => $backup->getFtpPath(),
            'ftp_port' => $backup->getFtpPort(),
            'local_path' => $backup->getLocalPath(),
            $backup->getSelectID() => $backup->getFile(),
        ]);
    }

    /**
     * create backup
     *
     * @param Models\Command\Backup $backup
     * @return mixed
     */
    public function create(Models\Command\Backup $backup)
    {
        $data = [];
        foreach(self::BACKUP_ITEMS as $key => $item)
        {
            if (property_exists($backup, $item) && $backup->{'get' . ucfirst($item)}() === 'on')
            {
                $data['select'. $key] = strtolower($item);
            }
        }

        return $this->curl->request(self::CMD_SITE_BACKUP, array_merge([
            'action'    => self::BACKUP,
            'domain'    => $backup->getBackupDomain(),
        ], $data));
    }

    /**
     * @param Models\Command\Backup $backup
     * @return mixed
     */
    public function restore(Models\Command\Backup $backup)
    {
        $data = [
            'domain' => $backup->getBackupDomain(),
            'action' => 'restore',
            'file'   => $backup->getFile()
        ];
        foreach(self::BACKUP_ITEMS as $key => $item)
        {
            if (property_exists($backup, $item) && $backup->{'get' . ucfirst($item)}() === 'on')
            {
                $data['select'. $key] = strtolower($item);
            }
        }

        return $this->curl->request(self::CMD_SITE_BACKUP, $data);
    }

    public function viewBackup(Models\Command\Backup $backup)
    {
        return $this->curl->request(self::CMD_SITE_BACKUP, [
            'domain' => $backup->getBackupDomain(),
            'action' => 'view',
            'file'   => $backup->getFile()
        ]);

    }

    /**
     * @param Models\Command\Backup $backup
     * @return mixed
     */
    public function delete(Models\Command\Backup $backup)
    {
        return $this->curl->request(self::CMD_API_FILE_MANAGER, [
            "action"    => "multiple",
            "button"    => "delete",
            "path"      => "/backups",
            "select0"   => "/backups/".$backup->getFile()
        ]);
    }

    public function deleteMany($backup)
    {
        $data = [];
        foreach ($backup as $key => $item)
        {
            $data['select' . $key] = '/backups/'.$item->getBackupName();
        }

        return $this->curl->request( self::CMD_API_FILE_MANAGER_V2 ,array_merge([
            'action'    => 'multiple',
            'button'    => 'delete',
            'json'      => 'yes'
        ], $data));

    }
}
