<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class BackupTransfer extends AbstractCommand
{
    const CMD_ADMIN_BACKUP  = 'CMD_ADMIN_BACKUP';
    const CMD_ADMIN_BACKUP_MODIFY='CMD_ADMIN_BACKUP_MODIFY';
    const CMD_SITE_BACKUP='CMD_SITE_BACKUP';

    public function admin_getStep($step,$id='')
    {

        $response = $this->curl->request(self::CMD_ADMIN_BACKUP, [], [
            'ipp' => '100000',
            'json'=>'yes',
            'step'=>$step,
            'id'=>$id
        ]);
        return $response;
    }

    public function create($data)
     {
        return $this->curl->request(self::CMD_ADMIN_BACKUP, $data,[
                'json'=>'yes'
            ] );
    }
    public function update($data)
     {
        return $this->curl->request(self::CMD_ADMIN_BACKUP, $data,[
                'json'=>'yes'
            ] );
    }
    public function massAction($data)
     {
        return $this->curl->request(self::CMD_ADMIN_BACKUP, $data,[
                'json'=>'yes'
            ] );
    }
    public function siteBackup($data)
     {
        return $this->curl->request(self::CMD_SITE_BACKUP, $data,[
                'json'=>'yes'
            ] );
    }
    public function siteRestore($data)
     {
        return $this->curl->request(self::CMD_ADMIN_BACKUP, $data,[
                'json'=>'yes'
            ] );
    }
    public function admin_getBackupDetail($id,$step='who')
    {

        $response = $this->curl->request(self::CMD_ADMIN_BACKUP_MODIFY, [], [
            'ipp' => '100000',
            'json'=>'yes',
            'id'=>$id,
            // 'step'=>$step
        ]);
        return $response;
    }


}
