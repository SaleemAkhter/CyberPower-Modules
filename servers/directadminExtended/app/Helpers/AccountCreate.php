<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command\Account;

class AccountCreate
{
    const ACTION           = 'create';
    const ENABLE_UNLIMITED = 'yes';

    protected $params;
    protected $productSettings;
    protected $ip;


    public function __construct(array $params, array $productSettings)
    {
        $this->params          = $params;
        $this->productSettings = $productSettings;
    }

    public function getBasicData()
    {
        return [
            'add'      => 'Submit',
            'action'   => self::ACTION,
            'username' => $this->params['username'],
            'email'    => $this->params['clientsdetails']['email'],
            'password' => $this->params['password'],
            'domain'   => idn_to_ascii($this->params['domain']),
            'ip'       => $this->getIPAddress(),
            'notify'   => 'no'
        ];
    }

    public function getUserDataModel()
    {
        $data = array_merge($this->getBasicData(), ['package' => $this->getPackage()]);

        return new Account($data);
    }

    private function getPackage()
    {
        $packageName = $this->productSettings['package'];
        if (strtolower($packageName) == "custom")
        {

            return "";
        }

        return $packageName;
    }

    public function getCustomUserDataModel()
    {

        $data = array_merge($this->getBasicData(), $this->getFromConfiugurableOptionsIfExsists());


        return new Account($data);
    }

    public function getIP()
    {
        return $this->ip;
    }


    private function getFromConfiugurableOptionsIfExsists()
    {
        $productSettings = $this->productSettings;
        $configOptions   = $this->params['configoptions'];

        /**
         *
         * quantity details
         */
        $quantity = [
            'bandwidth'   => (isset($configOptions['Bandwidth'])) ? $configOptions['Bandwidth'] : $productSettings['bandwidth'], //QUANTITY
            'domainptr'   => (isset($configOptions['Parked Domains'])) ? $configOptions['Parked Domains'] : $productSettings['domainptr'], //QUANTITY
            'ftp'         => (isset($configOptions['FTP Accounts'])) ? $configOptions['FTP Accounts'] : $productSettings['ftp'], //QUANTITY
            'mysql'       => (isset($configOptions['MySQL Databases'])) ? $configOptions['MySQL Databases'] : $productSettings['mysql'], //QUANTITY
            'nemailf'     => (isset($configOptions['Email Forwards'])) ? $configOptions['Email Forwards'] : $productSettings['nemailf'], //QUANTITY
            'nemailml'    => (isset($configOptions['Mailing Lists'])) ? $configOptions['Mailing Lists'] : $productSettings['nemailml'], //QUANTITY
            'nemailr'     => (isset($configOptions['Auto Responders'])) ? $configOptions['Auto Responders'] : $productSettings['nemailr'], //QUANTITY
            'nsubdomains' => (isset($configOptions['Subdomains'])) ? $configOptions['Subdomains'] : $productSettings['nsubdomains'], //QUANTITY
            'quota'       => (isset($configOptions['Disk Space'])) ? $configOptions['Disk Space'] : $productSettings['quota'], //QUANTITY
            'vdomains'    => (isset($configOptions['Addon Domains'])) ? $configOptions['Addon Domains'] : $productSettings['vdomains'], //QUANTITY
            'nemails'     => (isset($configOptions['Email Accounts'])) ? $configOptions['Email Accounts'] : $productSettings['nemails'], //QUANTITY
        ];

        /**
         *
         * set quantity as unlimited if is set as `-1`
         */
        foreach ($quantity as $key => $value)
        {
            if ((int)$value === SizeHelper::UNLIMITED)
            {
                /**
                 * need prefix `u` & value as `yes`
                 */
                $quantity['u' . $key] = self::ENABLE_UNLIMITED;
                $quantity[$key]       = null;
            }

            if ($value === null)
            {
                $quantity[$key] = 0;
            }
        }

        /**
         *
         * checkbox details
         */
        $checkbox = [
            'spam'             => (isset($configOptions['Spam Assassin'])) ? $this->changeCheckboxValue($configOptions['Spam Assassin']) : $productSettings['spam'], //CHECKBOX
            'php'              => (isset($configOptions['PHP'])) ? $this->changeCheckboxValue($configOptions['PHP']) : $productSettings['php'], //CHECKBOX
            'aftp'             => (isset($configOptions['Anon FTP'])) ? $this->changeCheckboxValue($configOptions['Anon FTP']) : $productSettings['aftp'], //CHECKBOX
            'ssh'              => (isset($configOptions['Shell Access'])) ? $this->changeCheckboxValue($configOptions['Shell Access']) : $productSettings['ssh'], //CHECKBOX
            'ssl'              => (isset($configOptions['SSL'])) ? $this->changeCheckboxValue($configOptions['SSL']) : $productSettings['ssl'], //CHECKBOX
            'suspend_at_limit' => (isset($configOptions['Suspend At Limit'])) ? $this->changeCheckboxValue($configOptions['Suspend At Limit']) : $productSettings['suspend_at_limit'], //CHECKBOX
            'sysinfo'          => (isset($configOptions['System Info'])) ? $this->changeCheckboxValue($configOptions['System Info']) : $productSettings['sysinfo'], //CHECKBOX
            'catchall'         => (isset($configOptions['Catch All'])) ? $this->changeCheckboxValue($configOptions['Catch All']) : $productSettings['catchall'], //CHECKBOX
            'cgi'              => (isset($configOptions['CGI Access'])) ? $this->changeCheckboxValue($configOptions['CGI Access']) : $productSettings['cgi'], //CHECKBOX
            'cron'             => (isset($configOptions['Cron Jobs'])) ? $this->changeCheckboxValue($configOptions['Cron Jobs']) : $productSettings['cron'], //CHECKBOX
            'dedicated_ip'     => (isset($configOptions['Dedicated IP'])) ? $this->changeCheckboxValue($configOptions['Dedicated IP']) : $productSettings['dedicated_ip'], // ?
            'dnscontrol'       => (isset($configOptions['DNS Control'])) ? $this->changeCheckboxValue($configOptions['DNS Control']) : $productSettings['dnscontrol'], //CHECKBOX

        ];

        /**
         * merge quantity & checkbox fields
         */
        $data = array_merge($quantity, $checkbox);

        return $data;
    }


    private function getIPAddress()
    {
        if ($this->params['producttype'] === "reselleraccount")
        {
            $this->ip = $this->productSettings['reseller_ip'];
        }
        else
        {
            $ip = null;
            if ($this->productSettings['dedicated_ip'] || $this->params['configoptions']['Dedicated IP'])
            {
                $ip = IP::getDirectAdminIP($this->params);
            }

            $this->ip = $ip ? $ip : $this->params['serverip'];
        }

        unset($this->productSettings['reseller_ip']);

        return $this->ip;
    }

    private function changeCheckboxValue($value)
    {
        return ($value == 1) ? "on" : "off";
    }
}
