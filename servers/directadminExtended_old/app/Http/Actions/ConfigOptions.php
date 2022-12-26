<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Actions;

use ModulesGarden\Servers\DirectAdminExtended\App\Http\Admin\ProductConfiguration;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Pages\ConfigForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\OutputBuffer;
use function ModulesGarden\DirectAdminExtended\Core\Helper\di;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;

/**
 * Class ConfigOptions
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ConfigOptions extends AddonController
{
    use OutputBuffer;

    public function execute($params = null)
    {

        if (($this->getRequestValue('action') === 'module-settings' && !$this->getRequestValue('loadData') ))
        {
            if(empty($this->getRequestValue('magic')))
            {
                return [
                    "configoption" => [
                        "Type" => "",
                        "Description" => $this->getJsCode(),
                    ],
                ];
            }

            if (isset($_REQUEST['magic']))
            {
                $this->cleanOutputBuffer();

                return [ProductConfiguration::class, 'index'];
            }
        }
        else if ($this->getRequestValue('action') === 'save')
        {
            $form = new ConfigForm();
            $form->runInitContentProcess();
            $form->returnAjaxData();
        }
        else if (($this->getRequestValue('loadData') && $this->getRequestValue('ajax') == '1'))
        {
            return [ProductConfiguration::class, 'index'];
        }


    }

    private function getJsCode()
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
//                        
                    });
                </script>";


    }
}
