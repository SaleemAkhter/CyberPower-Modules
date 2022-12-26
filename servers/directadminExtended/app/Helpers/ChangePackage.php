<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;


use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Account;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

class ChangePackage
{
    use DirectAdminAPIComponent;

    const ACTION            = 'customize';
    const ENABLE_UNLIMITED  = 'yes';
    protected $params;

    public function __construct(array $params, array $productSettings)
    {
        $this->params           = $params;
        $this->produtSettings   = $productSettings;
    }

    public function getPackageName(){

        $settingsRepository = new Repository();
        $productName = $settingsRepository->getProductSettings($this->params['packageid'])['package'];



        return $productName;
    }

    public function upgradeOnConfigOptions()
    {
        $productSettings = $this->produtSettings;
        $configOptions = $this->params['configoptions'];

        $quantity =  [
            'bandwidth' => (isset($configOptions['Bandwidth']))? $configOptions['Bandwidth'] : $productSettings['bandwidth'], //QUANTITY
            'domainptr' => (isset($configOptions['Parked Domains']))? $configOptions['Parked Domains'] : $productSettings['domainptr'], //QUANTITY
            'ftp'=> (isset($configOptions['FTP Accounts']))? $configOptions['FTP Accounts'] : $productSettings['ftp'], //QUANTITY
            'mysql' => (isset($configOptions['MySQL Databases']))? $configOptions['MySQL Databases'] : $productSettings['mysql'], //QUANTITY
            'nemailf' => (isset($configOptions['Email Forwards']))? $configOptions['Email Forwards'] : $productSettings['nemailf'], //QUANTITY
            'nemailml' => (isset($configOptions['Mailing Lists']))? $configOptions['Mailing Lists'] : $productSettings['nemailml'], //QUANTITY
            'nemailr' => (isset($configOptions['Auto Responders']))? $configOptions['Auto Responders'] : $productSettings['nemailr'], //QUANTITY
            'nsubdomains' => (isset($configOptions['Subdomains']))? $configOptions['Subdomains'] : $productSettings['nsubdomains'], //QUANTITY
            'quota' => (isset($configOptions['Disk Space']))? $configOptions['Disk Space'] : $productSettings['quota'], //QUANTITY
            'vdomains' => (isset($configOptions['Addon Domains']))? $configOptions['Addon Domains'] : $productSettings['vdomains'], //QUANTITY
            'nemails' => (isset($configOptions['Email Accounts']))? $configOptions['Email Accounts'] : $productSettings['nemails'], //QUANTITY
        ];

        foreach($quantity as $key => $value)
        {
            if((int) $value === SizeHelper::UNLIMITED)
            {
                /**
                 * need prefix `u` & value as `yes`
                 */
                $quantity['u'.$key] = self::ENABLE_UNLIMITED;
                $quantity[$key]     = null;
            }

            if($value === null)
            {
                $quantity[$key]     = 0;
            }
        }

        $checkbox =  [
            'spam' => (isset($configOptions['Spam Assassin']))? $this->changeCheckboxValue($configOptions['Spam Assassin']) : $productSettings['spam'], //CHECKBOX
            'php' => (isset($configOptions['PHP']))? $this->changeCheckboxValue($configOptions['PHP']) : $productSettings['php'], //CHECKBOX
            'aftp' => (isset($configOptions['Anon FTP']))? $this->changeCheckboxValue($configOptions['Anon FTP']) : $productSettings['aftp'], //CHECKBOX
            'ssh' => (isset($configOptions['Shell Access']))? $this->changeCheckboxValue($configOptions['Shell Access']) : $productSettings['ssh'], //CHECKBOX
            'ssl' => (isset($configOptions['SSL']))? $this->changeCheckboxValue($configOptions['SSL']) : $productSettings['ssl'], //CHECKBOX
            'suspend_at_limit' => (isset($configOptions['Suspend At Limit']))? $this->changeCheckboxValue($configOptions['Suspend At Limit']) : $productSettings['suspend_at_limit'], //CHECKBOX
            'sysinfo' => (isset($configOptions['System Info']))? $this->changeCheckboxValue($configOptions['System Info']) : $productSettings['sysinfo'], //CHECKBOX
            'catchall' => (isset($configOptions['Catch All']))? $this->changeCheckboxValue($configOptions['Catch All']) : $productSettings['catchall'], //CHECKBOX
            'cgi' => (isset($configOptions['CGI Access']))? $this->changeCheckboxValue($configOptions['CGI Access']) : $productSettings['cgi'], //CHECKBOX
            'cron' => (isset($configOptions['Cron Jobs']))? $this->changeCheckboxValue($configOptions['Cron Jobs']) : $productSettings['cron'], //CHECKBOX
            'dedicated_ip' => (isset($configOptions['Dedicated IP']))? $this->changeCheckboxValue($configOptions['Dedicated IP']) : $productSettings['dedicated_ip'], // ?
            'dnscontrol' => (isset($configOptions['DNS Control']))? $this->changeCheckboxValue($configOptions['DNS Control']) : $productSettings['dnscontrol'], //CHECKBOX
        ];

        $data = array_merge($quantity, $checkbox);
        $data = array_merge($data, $this->getBasicData());

        $modelData = new Account($data);
        $this->loadApi();
        $this->api->account->upgradeConfigOptions($modelData);

        return 'success';
    }

    public function upgradeOnPackage($packageName)
    {
        // throw Exception if package name not exists
        $this->params['configoption1'] = $packageName;

        #MGLICENSE_CHECK_RETURN#
        Helpers\DirectAdminWHMCS::load();
        return directadmin_ChangePackage($this->params);
    }

    private function changeCheckboxValue($value){
        return ($value == 1)? "ON" : "OFF";
    }

    private function getBasicData()
    {
        return  [
            'action'    => self::ACTION,
            'username'  => $this->params['username']
        ];
    }
}
