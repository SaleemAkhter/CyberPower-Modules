<?php

namespace ModulesGarden\Servers\HetznerVps\App\Http\Actions;

use Exception;
use ModulesGarden\Servers\HetznerVps\App\Helpers\CustomFields;
use ModulesGarden\Servers\HetznerVps\App\Models\CronTasks;
use ModulesGarden\Servers\HetznerVps\App\Models\MailBoxRead;
use ModulesGarden\Servers\HetznerVps\App\Models\ProductConfiguration;
use ModulesGarden\Servers\HetznerVps\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\HetznerVps\Core\Helper\Lang;
use function ModulesGarden\Servers\HetznerVps\Core\Helper\sl;

class ConfigOptions extends AddonController
{

    public function execute($params = null)
    {
        if(!$this->getRequestValue('namespace') && !$this->getRequestValue('magic'))
        {
            return [
                "configoption" => [
                    "Type" => "",
                    "Description" => $this->getJsCode(),
                ],
            ];
        }

        return [\ModulesGarden\Servers\HetznerVps\App\Http\Admin\Server\Product::class, 'index'];
    }


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

//        $content = sl('adminProductPage')->execute();

//        if(empty($content))
//        {
//            throw new Exception(Lang::getInstance()->T('connectionProblem'));
//        }
//        echo json_encode(['content' => "<tr><td>" . $content . "</td></tr>", 'mode' => 'advanced']);
//        die();
    }

    public function getJsCode()
    {
        $params = array_merge($this->request->request->all(), $this->request->query->all());
        $dataQuery = http_build_query($params);

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

                        var json = JSON.parse(data);

                        $('#mg-configoptionLoader').remove();
                        if ({$this->getRequestValue('servergroup')} == 0)
                        {
                              $('#tblModuleSettings').html(json.content).removeClass('hidden');
                        }
                        else
                        {
                            $('#tblModuleSettings').html(json.content).removeClass('hidden');
                        }
                    });
                </script>";
    }
}
