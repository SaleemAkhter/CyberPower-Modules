<?php
/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 2018-10-05
 * Time: 15:28
 */

namespace ModulesGarden\DirectAdminExtended\App\Services\Migration\Helpers;


use ModulesGarden\DirectAdminExtended\App\Models\BackupPath;
use ModulesGarden\DirectAdminExtended\App\Models\FTPEndPoints;
use ModulesGarden\DirectAdminExtended\App\Models\FunctionsSettings;
use ModulesGarden\DirectAdminExtended\App\Services\Migration\Traits\OldDataBaseManger;

class Addon
{

    use OldDataBaseManger;

    protected $oldTable = 'directadmin_extended_functions_settings';

    public function run()
    {
        if($this->checkOldConfiguration()){
            $this->migrate();
        }
    }

    private function migrate()
    {
        foreach($this->getOldSettings() as $settings){
            $this->migrateBackups($settings);
            $this->migrateFTPEndpoints($settings);
            $this->migrateFeatures($settings);
        }

        $this->removeTableAfterMigrate();
    }

    private function decodeField($field = ""){
        if(empty($field)){

            return [];
        }

        return unserialize($field);
    }


    private function migrateBackups($settings)
    {
        foreach($this->decodeField($settings->backup_directories) as $key => $singleSetting){
            if($this->isEmptySettingRow($singleSetting)){
                continue;
            }
            if($this->isEmptyField($singleSetting['server']) || $this->isEmptyField($singleSetting['name'])){
                continue;
            }
            $backupModel = new BackupPath();
            $backupModel->product_id = $settings->product_id;
            $backupModel->server_id = $singleSetting['server'];
            $backupModel->name = $singleSetting['name'];
            $backupModel->path = $singleSetting['path'];
            $backupModel->admin_access = $this->replaceSwitchFieldValue($singleSetting['admin_access']);
            $backupModel->enable_restore = $this->replaceSwitchFieldValue($singleSetting['enable_path']);
            $backupModel->save();
        }
    }
    private function migrateFTPEndpoints($settings)
    {

        foreach($this->decodeField($settings->ftp_endpoints) as $singleSetting){
            if($this->isEmptySettingRow($singleSetting)){
                continue;
            }
            if($this->isEmptyField($singleSetting['server']) || $this->isEmptyField($singleSetting['name'])){
                continue;
            }
            $ftpModel = new FTPEndPoints();
            $ftpModel->product_id       = $settings->product_id;
            $ftpModel->server_id        = $singleSetting['server'];
            $ftpModel->name             = $singleSetting['name'];
            $ftpModel->host             = $singleSetting['host'];
            $ftpModel->port             = $singleSetting['port'];
            $ftpModel->user             = $singleSetting['user'];
            $ftpModel->password         = $singleSetting['password'];
            $ftpModel->path             = $singleSetting['path'];
            $ftpModel->admin_access     = $this->replaceSwitchFieldValue($singleSetting['admin_access']);
            $ftpModel->enable_restore   = $this->replaceSwitchFieldValue($singleSetting['enable_restore']);
            $ftpModel->save();

        }

    }
    private function migrateFeatures($setting){
        unset($setting->ftp_endpoints);
        unset($setting->backup_directories);
        
        $featuresModel = new FunctionsSettings();

        if($featuresModel->where('product_id', $setting->product_id)->first() !== null){
            return;
        }

        foreach($setting as $key => $value){
            if(is_null($value)){
                continue;
            }
            $featuresModel->{$key} = $value;
        }

        $featuresModel->save();

    }

    private function replaceSwitchFieldValue($value = ""){
        return ($value == "on")? "on": "off";
    }
    private function isEmptyField($value){
         if($value == "" || is_null($value) || empty($value)){
             return true;
         }
         return false;
    }
    private function isEmptySettingRow($singleSetting){
        foreach($singleSetting as $item){
            if($item != ""){
               return false;
            }
        }
        return true;
    }

}