<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers;

use Exception;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Interfaces\AccountAction;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Servers\Dedicated;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Account\Utilities\ServerStrategyProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\ParamsComponent;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Addon;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\OvhVpsAndDedicated\Core\Helper;



/**
 * Description of AccoutnActions
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class AccoutnActions
{
    use Lang;
    use ParamsComponent;

    /**
     * @var Client
     */
    private $client;


    /**
     * @var AccountAction|Dedicated
     */
    private $account;


    public function __construct($params)
    {
        $this->setParams($params);
        $this->client = new Client($this->params);
        $this->setAccount();
        $this->loadLang();
    }

    /**
     * Create new VM 
     */

    public function create()
    {
        try
        {
            $this->checkServerNameIsEmpty();

            $this->account->create();
        }
        catch (\Exception $ex)
        {
            Helper\errorLog("createError", $this->client->getParams(), ['errorMessage' => $ex->getMessage()]);

            return $ex->getMessage();
        }
        return 'success';
    }


    /**
     * Susspend WHMCS Account and power off VM
     */
    public function suspendAccount()
    {
        try
        {
            $this->checkServerNameIsNotEmpty();
            $this->account->suspend();

            Helper\successLog("suspendAccount", $this->client->getParams());
        }
        catch (Exception $ex)
        {
            Helper\errorLog("suspendAccountError", $this->client->getParams(), ['errorMessage' => $ex->getMessage()]);

            return $ex->getMessage();
        }

        return 'success';
    }

    /*
     * Terminate WHMCS Account and power off VM
     */

    public function terminateAccount()
    {

        try
        {
            $this->checkServerNameIsNotEmpty();

            $this->account->terminate();

            Helper\successLog("terminateAccount", $this->client->getParams());
        }
        catch (Exception $ex)
        {
            Helper\errorLog("terminateAccountError", $this->client->getParams(), ['errorMessage' => $ex->getMessage()]);
            return $ex->getMessage();
        }
        return 'success';
    }

    /*
     * Unsuspend WHMCS Account and power on VM
     */

    public function unsuspendAccount()
    {
        try
        {
            $this->checkServerNameIsNotEmpty();

            $this->account->unsuspend();
            Helper\successLog("unsuspendAccount", $this->client->getParams());
        }
        catch (Exception $ex)
        {
            Helper\errorLog("unsuspendAccountError", $this->client->getParams(), ['errorMessage' => $ex->getMessage()]);
            return $ex->getMessage();
        }
        return 'success';
    }

    /**
     * changePackage
     */
    public function changePackage()
    {

        try
        {
            $this->checkServerNameIsNotEmpty();

            $this->account->changePackage();

            Helper\successLog("changePackage", $this->client->getParams());

        }
        catch (Exception $ex)
        {
            Helper\errorLog("changePackageError", $this->client->getParams(), ['errorMessage' => $ex->getMessage()]);
            return $ex->getMessage();
        }
        return 'success';
    }

    protected function checkServerNameIsEmpty()
    {
        if (!empty($this->client->getServiceName()))
        {
            throw new Exception($this->lang->translate('accountAction', 'serverNameNotEmpty'));
        }
    }

    protected function checkServerNameIsNotEmpty()
    {
        if (empty($this->client->getServiceName()))
        {
            throw new Exception($this->lang->translate('accountAction', 'serverNameNotEmpty'));
        }
    }

    private function setAccount()
    {
        $serverProvider = new ServerStrategyProvider();
        $serverProvider->chooseServer($this->client);
        $this->account = $serverProvider->getServer();
    }


}
