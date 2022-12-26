<?php

namespace ModulesGarden\WordpressManager\Core\App
{
    use ModulesGarden\WordpressManager\Core\HandlerError\WhmcsErrorManagerWrapper;

    class AppContext
    {
        protected $debugMode = true;

        public function __construct()
        {
            require_once __DIR__ . DIRECTORY_SEPARATOR . 'ErrorHandler.php';

            register_shutdown_function([$this, 'handleShutdown']);
            set_error_handler([$this, 'handleError'], E_ALL);

            $this->loadDebugState();

            //require app bootstrap
            require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Bootstrap.php';

            if ($this->debugMode)
            {
                spl_autoload_register(array('\ModulesGarden\WordpressManager\Core\App\AppContext', 'loadClassLoader'), true, false);        
            }
        }

        public function runApp($callerName = null, $params = [])
        {
            try
            {
                $app = new Application();
                $result = $app->run($callerName, $params);

                restore_error_handler();
            }
            catch (\Exception $exc)
            {
                restore_error_handler();

                return [
                    'status' => 'error',
                    'message' => $exc->getMessage()
                ];
            }

            return $result;
        }

        public function handleError($errno, $errstr, $errfile, $errline, $errcontext = null)
        {
            if ($this->debugMode || (!in_array($errno, ErrorHandler::WARNINGS) && !in_array($errno, ErrorHandler::NOTICES)))
            {
                $handler = new ErrorHandler();
                $errorToken = md5(time());
                $handler->logError($errorToken, $errno, $errstr, $errfile, $errline, $errcontext);
            }

            return true;
        }

        public function handleShutdown()
        {
            $errorInstance = null;
            $errManager = WhmcsErrorManagerWrapper::getErrorManager();
            if (is_object($errManager) && method_exists($errManager, 'getRunner'))
            {
                $runner = $errManager->getRunner();
                if (is_object($runner) && method_exists($runner, 'getHandlers'))
                {
                    $handlers = $runner->getHandlers();
                    foreach ($handlers as $handler)
                    {
                        $rfHandler = new \ReflectionClass($handler);
                        $method = $rfHandler->getMethod('getException');
                        $method->setAccessible(true);
                        $error = $method->invoke($handler);
                        if (is_object($error))
                        {
                            $errorInstance = $error;
                            break;
                        }
                    }
                }
            }

            if ($errorInstance === null)
            {
                $errorInstance = error_get_last();
                if ($errorInstance === null)
                {
                    return;
                }

                $this->handleError($errorInstance['type'], $errorInstance['message'], $errorInstance['file'], $errorInstance['line'], '');

                return;
            }

            $handler = new ErrorHandler();
            $errorToken = md5(time());
            $handler->logError($errorToken, $errorInstance->getCode(), $errorInstance->getMessage(), $errorInstance->getFile(), $errorInstance->getLine(), $errorInstance->getTrace());
        }

        public function loadDebugState()
        {
            $path = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . '.debug';
            if (file_exists($path))
            {
                $this->debugMode = true;
                
                return;
            }

            $this->debugMode = false;
        }

        public static function loadClassLoader($class)
        {
            $rawClass = trim($class, '\\');
            $pos = strpos($rawClass, 'ModulesGarden\WordpressManager');
            if ($pos === 0)
            {
                if (!class_exists($class) && self::DEPRECATED[$rawClass])
                {
                    echo 'This class no longer exists: ' . $class . '<br>';
                    echo 'Use: ' . self::DEPRECATED[$rawClass];
                    die();
                }
            }
        }

        const DEPRECATED = [
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\BaseMassActionButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassAction',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\AddIconModalButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonCreate',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\BaseSubmitButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonSubmitForm',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\BaseButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonBase',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\BaseDatatableModalButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDatatableShowModal',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\BaseModalDataTableActionButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDataTableModalAction',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\RedirectButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\BaseModalButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonModal',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\RedirectWithOutTooltipButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect',   
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\OnOffAjaxSwitch' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonSwitchAjax',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\CustomActionButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonCustomAction',  
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\CustomAjaxActionButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonAjaxCustomAction',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\DatatableModalButtonContextLang' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDatatableModalContextLang',  
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\DropdownButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDropdown',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\MassActionButtonContextLang' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonMassActionContextLang',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\Submit' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonSubmitForm',
            'ModulesGarden\WordpressManager\Core\HandlerError\WhmcsRegisterLoggin' => 'ModulesGarden\WordpressManager\Core\HandlerError\WhmcsLogsHandler',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonDropdown' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdown',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemCustonAjaxButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemCustonButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemDivider' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemModalButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem',
            'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemRedirectButton' => 'ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem',
            'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\ApiException' => 'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\Exception',
            'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\ApiWhmcsException' => 'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\Exception',
            'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\ControllerException' => 'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\Exception',
            'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\DependencyInjectionException' => 'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\Exception',
            'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\MGModuleException' => 'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\Exception',
            'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\RegisterException' => 'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\Exception',
            'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\ServiceLocatorException' => 'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\Exception',
            'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\SmartyException' => 'ModulesGarden\WordpressManager\Core\HandlerError\Exceptions\Exception',
        ];
    }
}
