<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\Settings\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Settings extends ProviderApi
{

    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {

        $this->loadLang();

        $this->loadAdminApi();

        $result     = $this->adminApi->settings->all();

        if(isset($result->admin_settings)){
            $admin_settings=$result->admin_settings;
            $this->data['auto_update']=($admin_settings->auto_update=="yes")?"on":"";
            $this->data['demo_admin']=($admin_settings->demo_admin=="yes")?"on":"";
            $this->data['demo_reseller']=($admin_settings->demo_reseller=="yes")?"on":"";
            $this->data['demo_user']=($admin_settings->demo_user=="yes")?"on":"";
            $this->data['oversell']=($admin_settings->oversell=="yes")?"on":"";
            $this->data['service_email_active']=($admin_settings->service_email_active=="yes")?"on":"";
            $this->data['suspend']=($admin_settings->suspend=="yes")?"on":"";
            $this->data['user_backup']=($admin_settings->user_backup=="yes")?"on":"";
            $this->data['backup_threshold']=$admin_settings->backup_threshold;
        }

        if(isset($result->server_settings)){
            $server_settings=$result->server_settings;
            $this->data['check_partitions']=$server_settings->check_partitions;
            $this->data['demodocsroot']=$server_settings->demodocsroot;
            $this->data['logs_to_keep']=$server_settings->logs_to_keep;
            $this->data['max_username_length']=$server_settings->max_username_length;
            $this->data['maxfilesize']=$server_settings->maxfilesize;
            $this->data['maxfilesize_units']=$server_settings->maxfilesize_units;
            $this->data['ns1']=$server_settings->ns1;
            $this->data['ns2']=$server_settings->ns2;
            $this->data['partition_usage_threshold']=$server_settings->partition_usage_threshold;
            $this->data['servername']=$server_settings->servername;
            $this->data['session_minutes']=$server_settings->session_minutes;
            $this->data['timeout']=$server_settings->timeout;
            $this->data['timezone']=$server_settings->timezone;

        }
        if(isset($result->security_settings)){
            $security_settings=$result->security_settings;
            $this->data['HAVE_BF_UNBLOCK_AFTER_TIME']=$security_settings->HAVE_BF_UNBLOCK_AFTER_TIME;
            $this->data['brute_dos_count']=$security_settings->brute_dos_count;
            $this->data['brute_force_log_scanner']=($security_settings->brute_force_log_scanner=="yes")?"on":"";
            $this->data['brute_force_scan_apache_logs']=$security_settings->brute_force_scan_apache_logs;
            $this->data['brute_force_time_limit']=$security_settings->brute_force_time_limit;
            $this->data['brutecount']=$security_settings->brutecount;
            $this->data['bruteforce']=($security_settings->bruteforce=='yes')?"on":"";
            $this->data['check_subdomain_owner']=($security_settings->check_subdomain_owner=='1')?"on":"";;
            $this->data['clear_blacklist_ip_time']=$security_settings->clear_blacklist_ip_time;
            $this->data['clear_brute_log_entry_time']=$security_settings->clear_brute_log_entry_time;
            $this->data['clear_brute_log_time']=$security_settings->clear_brute_log_time;
            $this->data['enforce_difficult_passwords']=$security_settings->enforce_difficult_passwords;
            $this->data['exempt_local_block']=($security_settings->exempt_local_block=='yes')?"on":"";
            $this->data['ip_brutecount']=$security_settings->ip_brutecount;
            $this->data['lost_password']=($security_settings->lost_password=='yes')?"on":"";
            $this->data['unblock_brute_ip_time']=$security_settings->unblock_brute_ip_time;
            $this->data['user_brutecount']=$security_settings->user_brutecount;
        }
        if(isset($result->email_settings)){
            $email_settings=$result->email_settings;
            $this->data['HAVE_BLACKLIST_USERNAMES']=($email_settings->HAVE_BLACKLIST_USERNAMES=="yes")?"on":"";
            $this->data['blacklist_script_usernames']=$email_settings->blacklist_script_usernames;
            $this->data['blacklist_smtp_usernames']=$email_settings->blacklist_smtp_usernames;
            $this->data['dovecot']=($email_settings->dovecot=='yes')?"":"";
            $this->data['max_per_email_send_limit']=$email_settings->max_per_email_send_limit;
            $this->data['per_email_limit']=$email_settings->per_email_limit;
            $this->data['purge_spam_days']=$email_settings->purge_spam_days;
            $this->data['rbl_enabled']=$email_settings->rbl_enabled;
            $this->data['user_can_set_email_limit']=$email_settings->user_can_set_email_limit;
            $this->data['virtual_limit']=$email_settings->virtual_limit;

        }
        if(isset($result->timezones)){
            $timezones=$result->timezones;
            foreach ($timezones as $key => $value) {
                   $timezonelist[$key]= $key;
            }
            $this->availableValues['timezone']=$timezonelist;
        }

        $uploadsize=$this->bytesToHuman($this->data['maxfilesize']);
        $this->data['maxfilesize']=$uploadsize[0];
        $this->data['unit']=$uploadsize[1];
    }

    public function update()
    {
        parent::update();

        $this->formData=$_POST['formData'];
        $data = [
            "brute_force_log_scanner"=> ($this->formData['brute_force_log_scanner']=="on")?"yes":"no",
            "brute_force_scan_apache_logs"=> $this->formData['brute_force_scan_apache_logs'],
            "brute_force_time_limit"=> $this->formData['brute_force_time_limit'],
            "brutecount"=> $this->formData['brutecount'],
            "brute_dos_count"=> $this->formData['brute_dos_count'],
            "bruteforce"=> ($this->formData['bruteforce']=="on")?"yes":"no",
            "check_partitions"=> $this->formData['check_partitions'],
            "check_subdomain_owner"=> ($this->formData['check_subdomain_owner']=="on")?"yes":"no",
            "clear_blacklist_ip_time"=> $this->formData['clear_blacklist_ip_time'],
            "clear_brute_log_entry_time"=> $this->formData['clear_brute_log_entry_time'],
            "clear_brute_log_time"=> $this->formData['clear_brute_log_time'],
            "demodocsroot"=> $this->formData['demodocsroot'],
            "enforce_difficult_passwords"=> ($this->formData['enforce_difficult_passwords']=="on")?"yes":"no",
            "exempt_local_block"=> ($this->formData['exempt_local_block']=="on")?"yes":"no",
            "ip_brutecount"=> $this->formData['ip_brutecount'],
            "logs_to_keep"=> $this->formData['logs_to_keep'],
            "lost_password"=> ($this->formData['lost_password']=="on")?"yes":"no",
            "max_per_email_send_limit"=> $this->formData['max_per_email_send_limit'],
            "maxfilesize"=> $this->formData['maxfilesize'],
            "ns1"=> $this->formData['ns1'],
            "ns2"=> $this->formData['ns2'],
            "partition_usage_threshold"=> $this->formData['partition_usage_threshold'],
            "per_email_limit"=> $this->formData['per_email_limit'],
            "purge_spam_days"=> $this->formData['purge_spam_days'],
            "rbl_enabled"=> ($this->formData['rbl_enabled']=="on")?"yes":"no",
            "servername"=> $this->formData['servername'],
            "session_minutes"=> $this->formData['session_minutes'],
            "timeout"=> $this->formData['timeout'],
            "user_brutecount"=> $this->formData['user_brutecount'],
            "user_can_set_email_limit"=> ($this->formData['user_can_set_email_limit']=="on")?"yes":"no",
            "virtual_limit"=> $this->formData['virtual_limit'],
            "unblock_brute_ip_time"=> $this->formData['unblock_brute_ip_time'],
            "timezone"=> $this->formData['timezone'],
            "max_username_length"=> $this->formData['max_username_length'],
            "json"=> "yes",
            "action"=> "config"
        ];

        $this->loadAdminApi();
        $response=$this->adminApi->settings->update($data);
        if(isset($response->success)){
            return (new ResponseTemplates\RawDataJsonResponse([
            ]))->setMessageAndTranslate('settingsUpdatedSuccessfully');
        }
        return (new ResponseTemplates\RawDataJsonResponse([

        ]))->setMessageAndTranslate('settingsUpdateFailed');
    }

    public function delete()
    {
        parent::delete();

        $data = [
            'user' => $this->formData['user']
        ];
         $this->loadResellerApi();
         $response=$this->resellerApi->reseller->delete(new Models\Command\User($data));
        debug($response);
        die();
        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('userHasBeenDeleted');
    }

    public function deleteMany()
    {
        parent::delete();

        $data        = [];
        $domainsName = $this->getRequestValue('massActions', []);
        $this->loadLang();
        foreach ($domainsName as $name)
        {
            if($name === $this->getWhmcsParamByKey('domain'))
            {
                return (new ResponseTemplates\RawDataJsonResponse())
                    ->setStatusError()
                    ->setMessage($this->lang->addReplacementConstant('domain',$name)->absoluteTranslate('domainCannotBeDeleted'));
            }
            $data[] = new Models\Command\Domain(['name' => $name]);
        }
        $this->userApi->domain->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('userssHaveBeenDeleted');
    }


    public function reload()
    {
        $this->data['user']='dj3';
        $this->data['id']='dj3';
        $this->data['domain']                   = (is_null($this->formData['domain'])) ? $this->data['domain'] : $this->formData['domain'] ;
        $this->data['bandwidth']                = (is_null($this->formData['bandwidth'])) ? $this->data['bandwidth'] : $this->formData['bandwidth'];
        $this->data['bandwidthCustom']          = (is_null($this->formData['bandwidthCustom'])) ? $this->data['bandwidthCustom'] : $this->formData['bandwidthCustom'];
        $this->data['diskspace']                = (is_null($this->formData['diskspace'])) ? $this->data['diskspace'] : $this->formData['diskspace'];
        $this->data['diskspaceCustom']          = (is_null($this->formData['diskspaceCustom'])) ? $this->data['diskspaceCustom'] : $this->formData['diskspaceCustom'];
        $this->data['ssl']                      = (is_null($this->formData['ssl'])) ? $this->data['ssl'] : $this->formData['ssl'];
        $this->data['cgi']                      = (is_null($this->formData['cgi'])) ? $this->data['cgi'] : $this->formData['cgi'];
        $this->data['php']                      = (is_null($this->formData['php'])) ? $this->data['php'] : $this->formData['php'];
        $this->data['localMail']                = (is_null($this->formData['localMail'])) ? $this->data['localMail'] : $this->formData['localMail'];
    }

    public function suspendUnsuspend()
    {
        parent::suspendUnsuspend();

        $data = [
            'name' => $this->formData['domain']
        ];
        $this->loadResellerApi();
        $user = $this->resellerApi->reseller->config(new Models\Command\User([
                'username'  => $data['name']
            ]));
        if($user['suspended']=='no'){
            $action='suspend';
            $messagestring='singleUserSuspend';
        }else{
            $action='unsuspend';
            $messagestring='singleUserUnsuspend';
        }

        $response=$this->resellerApi->reseller->suspendUnsuspend(new Models\Command\User($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate($messagestring);
    }

    public function suspendUnsuspendMany()
    {
        parent::suspendUnsuspend();

        $data        = [];
        $domainsName = $this->getRequestValue('massActions', []);
        foreach ($domainsName as $name) {
            $data[] = new Models\Command\Domain([
                'name' => $name
            ]);
        }

        $this->userApi->domain->suspendUnsuspendMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('multipleToggleSuspend');
    }
    protected function getUsersIndexURL()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'UserManager',
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
    protected function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return [round($bytes, 2) , $units[$i]];


    }
}
