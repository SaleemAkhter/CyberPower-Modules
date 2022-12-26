<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\ErrorManager;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Logger;

/**
 * Use as base for other exceptions
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class MGModuleException extends \Exception
{
    private $_token;
    
    /**
     * @var \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\ErrorManager
     */
    protected static $errorManager;
    
    /**
     * @var string
     */
    protected $class;
    
    /**
     * @var \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang
     */
    protected $lang;

    public function __construct($class = '', $message = '', $code = 0, $previous = null)
    {
        
        $this->lang = Lang::getInstance();
        $this->_token = md5(microtime());
        $message .= $this->lang->absoluteT('token') . $this->_token;
        parent::__construct($message, $code, $previous);
        $this->class = $class;
        $this->getErrorManager()->addError($class, $message, $this->getTrace());
        $this->getErrorManager()->setLastErrorToken($this->_token);
        
    }
    
    protected function getErrorManager()
    {
        if (!self::$errorManager)
        {
            $logger = ServiceLocator::call('logger');
            self::$errorManager = new ErrorManager($logger);
        }
        
        return self::$errorManager;
    }

    public function getToken()
    {
        return $this->_token;
    }
    
    public function writeMassage()
    {
        return (string)$this->errorManager;
    }
    
    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}
