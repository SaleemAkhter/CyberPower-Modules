<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Models\MailBoxRead;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Models\ProductConfiguration;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use function ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\sl;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\WhmcsVersionComparator;

/**
 * Description of ConfigOptions
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ConfigOptions
{

    private function createCustomFields()
    {
        CustomFields::create($_REQUEST['id'], 'serverID|Server ID');
        CustomFields::create($_REQUEST['id'], 'firewalls|Firewalls');
        CustomFields::create($_REQUEST['id'], 'sshKey|SSH Public Key', 'textarea', '', 'on', 'If you want to log in to the VM without using a password, you can enter your public key in the OpenSSH format here (e.g. ssh-rsa).', '#ssh-rsa AAAA[0-9A-Za-z+/]+[=]{0,3}( [^@]+@[^@]+)?#');
    }

    /*
     * Create Database table if not exist
     * 
     */

    private function createTable()
    {
        $productConfig = new ProductConfiguration();
        $productConfig->createTableIfNotExists();

        $taskManager = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Models\CronTasks();
        $taskManager->createTableIfNotExists();

        $mailbox = new MailBoxRead();
        $mailbox->createTableIfNotExists();
    }

    /*
     * Update Database Table if exists
     *
     */

    private function updateTable()
    {
        $mailbox = new MailBoxRead();
        $mailbox->addColumnIfNotExists('mail', 'string');

        if($mailbox->checkColumnExist('mail')){
            $this->updateOldMailRecords();
        }
    }

    private function updateOldMailRecords(){
        $searchOldRecords = MailBoxRead::where('mail', '');

        if($searchOldRecords->count() > 0){
            $emailAddress = $this->getClientMailForConfiguration();

            if(is_null($emailAddress)){
                      return;
            }

            $searchOldRecords->update([
                'mail' => $emailAddress
            ]);
        }

    }
    private function getClientMailForConfiguration(){
        $fields = new FieldsProvider($_REQUEST['id']);
        return $fields->getField('username', null);
    }

    private function checkServer()
    {
        if (empty($_REQUEST['servergroup']))
        {
            $data = [];
            $alertDiv = '<div style="width=100%; margin-bottom: 0px;" class="alert alert-danger">' . Lang::getInstance()->T('emptyServerGroup') . '</div>';
            if(WhmcsVersionComparator::isWhmcsVersionHigherOrEqual('8.3.0'))
            {
                $data['content'] = '<div style="border: 3px solid #e2e7e9; margin: 12px 0 12px 0; border-collapse: separate; border-radius: 4px;">
                    <div style="padding: 1px 3px 1px 3px; margin: 2px; background-color: #efefef;">
                    ' . $alertDiv.'
                    </div>
                </div>';
            }
            else
            {
                $data['content'] = $alertDiv;
            }
            
           echo json_encode($data);
           die();
           
//            throw new Exception(Lang::getInstance()->T('emptyServerGroup'));
        }
    }

    /*
     * Conf options array
     * 
     * @return array
     */

    private function createEmailTemplate()
    {
        $email = new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\EmailTemplate();
        $email->create();
    }

    public function getConfig()
    {
        $this->checkServer();
        $this->createTable();
        $this->updateTable();
        $this->createCustomFields();
        $this->createEmailTemplate();

        $content = sl('adminProductPage')->execute();
        echo json_encode(['content' => "<tr><td>" . $content . "</td></tr>", 'mode' => 'advanced']);
        die();
    }

    private function getActionButtonParams()
    {
        return [
            'action'      => $this->params['action'],
            'module'      => $this->params['module'],
            'servergroup' => $this->params['servergroup'],
            'id'          => $this->params['id'],
            'mode'        => $this->params['mode'],
        ];
    }

}
