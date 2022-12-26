<?php
namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Api;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\Models\Whmcs\Admins;

class Whmcs
{

    /**
     * @var Admins
     */
    protected $admins;
    
    /**
     * @var string
     */
    protected $username;

    /**
     * @param Admins $admins
     * @throws \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\ApiWhmcsException
     */
    public function __construct(Admins $admins)
    {
        $this->admins = $admins;
        $this->getAdminUserName();
        
        if (function_exists('localAPI') === false)
        {
            throw new \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\ApiWhmcsException(self::class, "Please load the WHMCS files.");
        }
    }
            
    /**
     * @return string
     */
    protected function getAdminUserName()
    {
        if (isset($this->username) === false)
        {
            $this->username = $this->admins->first()->toArray()['username'];
        }
        
        return $this->username;
    }

    public function call($command, $config = [])
    {
        
        $result = localAPI($command, $config, $this->getAdminUserName());

        if ($result['result'] == 'error')
        {
            throw new \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\ApiWhmcsException(self::class, $result['message']);
        }
        unset($result['result']);
        
        return $result;
    }

    public function getAdminDetails($adminId)
    {
        $data = $this->admins->where("id","LIKE",$adminId)->first();
        
        if ($data === null)
        {
            throw new \Exception("There is no admin with id equal to `{$adminId}`.");
        }
        $result = localAPI("getadmindetails", [], $data->toArray()['username']);
        
        if ($result['result'] == 'error')
        {
            throw new \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\ApiWhmcsException(self::class, $result['message']);
        }
        
        $result['allowedpermissions'] = explode(",", $result['allowedpermissions']);
        unset($result['result']);
        
        return $result;
    }
}
