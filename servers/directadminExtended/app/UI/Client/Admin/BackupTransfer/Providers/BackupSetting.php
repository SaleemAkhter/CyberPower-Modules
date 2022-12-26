<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class BackupSetting extends ProviderApi
{

    public function read()
    {
        $this->data['id'] = $this->actionElementId;
        if($this->data['id']){
            $this->loadAdminApi();
            $who=$this->adminApi->backupTransfer->admin_getStep('who');
                $settings=$who->settings;

                $this->data=[];
                $this->data['message']=($settings->message=='yes')?"on":"";
                $this->data['local_ns']=$settings->local_ns;
                $this->data['restore_spf']=$settings->restore_spf;
                $this->data['confirm_with_domainowners']=($settings->confirm_with_domainowners=='yes')?"on":"";
                $this->data['backup_ftp_pre_test']=($settings->directadmin_conf->backup_ftp_pre_test->value)?"on":"";
                $this->data['backup_ftp_md5']=($settings->directadmin_conf->backup_ftp_md5->value)?"on":"";
                $this->data['allow_backup_encryption']=($settings->directadmin_conf->allow_backup_encryption->value)?"on":"";
                $this->data['restore_database_as_admin']=($settings->directadmin_conf->restore_database_as_admin->value)?"on":"";
                $this->data['tally_after_restore']=($settings->directadmin_conf->tally_after_restore->value)?"on":"";
                $this->data['backup_hard_link_check']=($settings->directadmin_conf->backup_hard_link_check->value)?"on":"";
                $this->data['webmail_backup_is_email_data']=($settings->directadmin_conf->webmail_backup_is_email_data->value)?"on":"";

        }else{
            if($this->getWhmcsParamByKey('producttype')  == "server" )
            {
               $this->loadAdminApi();
                $who=$this->adminApi->backupTransfer->admin_getStep('who');
                $settings=$who->settings;

                $this->data=[];
                $this->data['message']=($settings->message=='yes')?"on":"";
                $this->data['local_ns']=($settings->local_ns=='yes')?"on":"";
                $this->data['restore_spf']=($settings->restore_spf=="yes")?"on":"";
                $this->data['confirm_with_domainowners']=$settings->confirm_with_domainowners;
                $this->data['backup_ftp_pre_test']=($settings->directadmin_conf->backup_ftp_pre_test->value)?"on":"";
                $this->data['backup_ftp_md5']=($settings->directadmin_conf->backup_ftp_md5->value)?"on":"";
                $this->data['allow_backup_encryption']=($settings->directadmin_conf->allow_backup_encryption->value)?"on":"";
                $this->data['restore_database_as_admin']=($settings->directadmin_conf->restore_database_as_admin->value)?"on":"";
                $this->data['tally_after_restore']=($settings->directadmin_conf->tally_after_restore->value)?"on":"";
                $this->data['backup_hard_link_check']=($settings->directadmin_conf->backup_hard_link_check->value)?"on":"";
                $this->data['webmail_backup_is_email_data']=($settings->directadmin_conf->webmail_backup_is_email_data->value)?"on":"";

            }else{
                $this->loadUserApi();
                $result=[];
            }
        }

    }

    public function update()
    {
        // {"message":"yes","local_ns":"yes","restore_spf":"yes","confirm_with_domainowners":"yes","json":"yes","action":"setting","backup_ftp_pre_test":"1","backup_ftp_md5":"1","allow_backup_encryption":"1","restore_database_as_admin":"1","tally_after_restore":"1","backup_hard_link_check":"1","webmail_backup_is_email_data":"1"}:
        parent::update();
        $formData=$this->formData;
        $selectedUsers=explode(",",$formData['selectedUsers']);
        $data=[
            "message"=>($formData['message']=="on")?"yes":"",
            "restore_spf"=>$formData['restore_spf'],
            "confirm_with_domainowners"=>($formData['confirm_with_domainowners']=="on")?"yes":"",
            "local_ns"=>$formData['local_ns'],
            "json"=>"yes",
            "action"=>"setting",
            "backup_ftp_pre_test"=>($formData['backup_ftp_pre_test']=="on")?1:0,
            "backup_ftp_md5"=>($formData['backup_ftp_md5']=="on")?1:0,
            "allow_backup_encryption"=>($formData['allow_backup_encryption']=="on")?1:0,
            "restore_database_as_admin"=>($formData['restore_database_as_admin']=="on")?1:0,
            "tally_after_restore"=>($formData['tally_after_restore']=="on")?1:0,
            "backup_hard_link_check"=>($formData['backup_hard_link_check']=="on")?1:0,
            "webmail_backup_is_email_data"=>($formData['webmail_backup_is_email_data']=="on")?1:0
        ];

        $this->loadAdminApi();
        $response=$this->adminApi->backupTransfer->massAction($data);
        if(isset($response->success)){
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupSettingsBeenSaved');
        }else{
            return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupSettingsSaveFailed');
        }
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('BackupSettingsSaveFailed');
    }
}


