<?php

namespace ModulesGarden\DirectAdminExtended\Core\App;

use ModulesGarden\DirectAdminExtended\Core\HandlerError\WhmcsErrorManagerWrapper;

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
            spl_autoload_register(array('\ModulesGarden\DirectAdminExtended\Core\App\AppContext', 'loadClassLoader'), true, false);
        }
    }

    public function runApp($callerName = null, $params = [])
    {
        try
        {
            /**
             * fix if other module in the WHMCS set status as 500
             */
            http_response_code(200);
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
        if ($errorToken)
        {
            //echo '<input type="hidden" id="mg-sh-h-492318-64534" value="' . $errorToken . '" mg-sh-h-492318-64534-end >';
        }
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
        $pos = strpos($rawClass, 'ModulesGarden\DirectAdminExtended');
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
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\BaseMassActionButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassAction',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\AddIconModalButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCreate',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\BaseSubmitButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonSubmitForm',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\BaseButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonBase',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\BaseDatatableModalButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDatatableShowModal',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\BaseModalDataTableActionButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDataTableModalAction',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\RedirectButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\BaseModalButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonModal',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\RedirectWithOutTooltipButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\OnOffAjaxSwitch' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonSwitchAjax',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\CustomActionButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonCustomAction',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\CustomAjaxActionButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonAjaxCustomAction',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\DatatableModalButtonContextLang' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDatatableModalContextLang',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDropdown',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\MassActionButtonContextLang' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonMassActionContextLang',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\Submit' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonSubmitForm',
        'ModulesGarden\DirectAdminExtended\Core\HandlerError\WhmcsRegisterLoggin' => 'ModulesGarden\DirectAdminExtended\Core\HandlerError\WhmcsLogsHandler',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonDropdown' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdown',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemCustonAjaxButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemCustonButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemDivider' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemModalButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem',
        'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemRedirectButton' => 'ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\DropdownButtonWrappers\ButtonDropdownItem',
        'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\ApiException' => 'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\ApiWhmcsException' => 'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\ControllerException' => 'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\DependencyInjectionException' => 'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\MGModuleException' => 'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\RegisterException' => 'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\ServiceLocatorException' => 'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\SmartyException' => 'ModulesGarden\DirectAdminExtended\Core\HandlerError\Exceptions\Exception',
    ];
}
