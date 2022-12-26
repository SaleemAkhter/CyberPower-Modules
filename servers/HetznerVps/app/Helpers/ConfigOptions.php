<?php

namespace ModulesGarden\Servers\HetznerVps\App\Helpers;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Models\CronTasks;
use ModulesGarden\Servers\HetznerVps\App\Models\MailBoxRead;
use ModulesGarden\Servers\HetznerVps\App\Models\ProductConfiguration;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

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
        CustomFields::create($_REQUEST['id'], 'sshKey|SSH Public Key', 'textarea', '', 'on', 'If you want to log in to the VM without using a password, you can enter your public key in the OpenSSH format here (e.g. ssh-rsa).', '#ssh-rsa AAAA[0-9A-Za-z+/]+[=]{0,3}( [^@]+@[^@]+)?#');
    }

    /*
     * Create Database table if not exist
     * 
     */

    private function createTable()
    {
        $productConfig = new ProductConfiguration();
        $productConfig->createOrUpdateTable();

        $cronTask = new CronTasks();
        $cronTask->createTableIfNotExists();
    }

    private function checkServer()
    {
        if (empty($_REQUEST['servergroup']))
        {
            throw new Exception(Lang::getInstance()->T('emptyServerGroup'));
        }
    }

    public function getConfig()
    {
        $this->checkServer();
        $this->createTable();
        $this->createCustomFields();

        $content = sl('adminProductPage')->execute();

        if(empty($content))
        {
            throw new Exception(Lang::getInstance()->T('connectionProblem'));
        }
        echo json_encode(['content' => "<tr><td>" . $content . "</td></tr>", 'mode' => 'advanced']);
        die();
    }

    public function getJsCode()
    {
        $dataQuery = http_build_query($_REQUEST);

        return "
                <script>
                    $('#layers').remove();
                    $('.lu-alert').remove();
                    $('#tblModuleSettings').addClass('hidden');
                    $('#tblMetricSettings').before('<img style=\"margin-left: 50%; margin-top: 15px; margin-bottom: 15px; height: 20px\" id=\"mg-configoptionLoader\" src=\"images/loading.gif\">');
                    $.post({
                        url: '{$_SERVER['HTTP_ORIGIN']}{$_SERVER['PHP_SELF']}?$dataQuery&magic=1'
                    })
                    .done(function( data ){
                        
                       
                        $('#mg-configoptionLoader').remove();
                        if ({$_REQUEST['servergroup']} == 0)
                        {
                            $('#serverReturnedError').removeClass('hidden');
                            $('#serverReturnedErrorText').text(data.error);
                        }
                        else
                        {
                            var json = JSON.parse(data);
                            $('#tblModuleSettings').html(json.content).removeClass('hidden');
                        }
//                        
                    });
                </script>";

    }
}
