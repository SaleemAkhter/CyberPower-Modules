<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Http\Actions;

use ModulesGarden\Servers\DirectAdminExtended\Core\App\Controllers\Instances\AddonController;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ApplicationDefaultInstallModel;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\DirectAdmin;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs\Hosting;
use ModulesGarden\DirectAdminExtended\App\Models\FunctionsSettings;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;

/**
 * Class CreateAccount
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CreateAccount extends AddonController
{
    public function execute($params = null)
    {
        try
        {

        }
        catch(\Exception $ex)
        {

        }
        \ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DirectAdminExtendedAddon::isActive(true);


        Helpers\DirectAdminWHMCS::load();

        $settingsRespository = new Repository();
        $productSettings = $settingsRespository->getProductSettings($params['pid']);

        $directAdmin     = new DirectAdmin($params);
        $accountCreate   = new Helpers\AccountCreate($params, $productSettings);

        /**
         * User creation
         */
        if($productSettings['package'] == 'custom')
        {
            if($params['producttype'] === "reselleraccount")
            {
                $directAdmin->account->createCustomReseller($accountCreate->getCustomUserDataModel());
            } else{
                $directAdmin->account->createCustomUser($accountCreate->getCustomUserDataModel());
            }
        }
        else
        {
            if($params['producttype'] === "reselleraccount")
            {
                $directAdmin->account->createReseller($accountCreate->getUserDataModel());
            } else{
                $directAdmin->account->createUser($accountCreate->getUserDataModel());
            }
        }

        if($params['producttype'] === "reselleraccount"){
            return 'success';
        }

        /**
         * Update IP
         */
        $isUpdateIp = false;
        if(isset($params['configoptions']['Dedicated IP']))
        {
            $isUpdateIp = $params['configoptions']['Dedicated IP'] == 1;
        }
        elseif(isset($productSettings['dedicated_ip']))
        {
            $isUpdateIp = $productSettings['dedicated_ip'] === 'on';
        }

        if($isUpdateIp)
        {
            Hosting::where('id', $params['serviceid'])->update(['dedicatedip' => $accountCreate->getIP()]);
        }

        /**
         * Install application
         */
        $config = FunctionsSettings::where('product_id', '=', $params['pid'])->first();
        if($config->apps != 'on')
        {
            return 'success';

        }

        $appName    = $config->apps_order_assign == 1 ? $params['configoptions']['Installation App'] : $config->apps_app_name;

        if (!$appName || !is_numeric($config->apps_installer_type) || $appName === 0)
        {
            return 'success';
        }

        /**
         * @var \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Softaculous
         */
        $helper             = new Helpers\ApplicationInstaller($params);
        $installer          = $helper->getInstaller();
        $modelHelper        = new ApplicationDefaultInstallModel();


        $params['customfields']['Auto Update']  = ($config->apps_auto_apps_backups_default === 'on' || $params['configoptions']['Auto Backup On Update']) ? 'yes' : 0;
        $installModel                           = $modelHelper->getWithParams($params, $helper->getInstallerName());
        $installtionScript                      = $helper->getScriptByName($appName);

        if(is_null($installtionScript))
        {
            return 'success';
        }

        $installModel->setApplication($installtionScript->getSid());

        $autoupFieldValue = ($config->auto_apps_backups == "on")? 1 : 0;
        $installModel->addField((new \ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Models\Field())->setName('autoup_backup')->setValue($autoupFieldValue));

        $installer->installationCreate($installtionScript->getSid(), $installModel);

        return 'success';
    }
}
