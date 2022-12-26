<?php
namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\Request;

/**
 * Description of WhmcsRegister
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class WhmcsRegisterLoggin
{
    /**
     * @var Addon
     */
    private $addon;
    
    /**
     * @var Request
     */
    private $request;
    
    /**
     * @param Addon $addon
     * @param Request $request
     */
    public function __construct(Addon $addon, Request $request)
    {
        $this->addon   = $addon;
        $this->request = $request;
    }
    
    /**
     * @param mixed $responseData
     * @param array $replaceVars
     * @return $this
     */
    public function addModuleLog($responseData = [], array $replaceVars = [])
    {
        if ($this->isDebugin())
        {
            if (is_array($responseData) === false)
            {
                if (is_object($responseData))
                {
                    $responseData = print_r($responseData, true);
                }
            }
      logModuleCall(
                $this->getModuleName(),
                $this->getFullAction(),
                $this->getRequestData(),
                $responseData,
                print_r($responseData, true),
                $replaceVars
            );
        }
        return $this;
    }
    
    /**
     * @param mixed $message
     * @param int $userId
     * @return $this
     */
    public function addActiveLog($message, $userId = 0)
    {
        if ($this->isDebugin())
        {
            if (is_string($message) === false)
            {
                $message = print_r($message, true);
            }

            logActivity($message,$userId);
        }
        return $this;
    }
    
    /**
     * @return array
     */
    private function getRequestData()
    {
        return array_merge($this->request->request->all() ,$this->request->query->all());
    }
    
    /**
     * @return string
     */
    private function getModuleName()
    {
        return $this->addon->getConfig("name", 'DigitalOceanDroplets');
    }
    
    /**
     * @return bool
     */
    private function isDebugin()
    {
        return (bool)((int)$this->addon->getConfig("debug", "0"));
    }
    
    /**
     * @return string
     */
    private function getFullAction()
    {
        return $this->request->get('mg-page', 'Home') . $this->request->get('mg-action', 'Index');
    }
}
