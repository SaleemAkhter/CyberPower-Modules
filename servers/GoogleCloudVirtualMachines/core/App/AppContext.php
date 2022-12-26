<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\WhmcsErrorManagerWrapper;

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
            spl_autoload_register(array('\ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\App\AppContext', 'loadClassLoader'), true, false);
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
        if ($errorToken)
        {
            echo '<input type="hidden" id="mg-sh-h-492318-64534" value="' . $errorToken . '" mg-sh-h-492318-64534-end >';
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
        $pos = strpos($rawClass, 'ModulesGarden\Servers\GoogleCloudVirtualMachines');
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
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\BaseMassActionButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonMassAction',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\AddIconModalButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonCreate',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\BaseSubmitButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonSubmitForm',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\BaseButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonBase',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\BaseDatatableModalButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonDatatableShowModal',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\BaseModalDataTableActionButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonDataTableModalAction',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\RedirectButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonRedirect',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\BaseModalButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonModal',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\RedirectWithOutTooltipButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonRedirect',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\OnOffAjaxSwitch' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonSwitchAjax',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\CustomActionButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonCustomAction',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\CustomAjaxActionButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonAjaxCustomAction',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\DatatableModalButtonContextLang' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonDatatableModalContextLang',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\DropdownButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonDropdown',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\MassActionButtonContextLang' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonMassActionContextLang',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\Submit' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonSubmitForm',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\WhmcsRegisterLoggin' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\WhmcsLogsHandler',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\ButtonDropdown' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdown',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemCustonAjaxButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemCustonButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemDivider' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemModalButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\Dropdowntems\DropdownItemRedirectButton' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Buttons\DropdawnButtonWrappers\ButtonDropdownItem',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\ApiException' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\ApiWhmcsException' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\ControllerException' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\DependencyInjectionException' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\MGModuleException' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\RegisterException' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\ServiceLocatorException' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\Exception',
        'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\SmartyException' => 'ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\HandlerError\Exceptions\Exception',
    ];
}
