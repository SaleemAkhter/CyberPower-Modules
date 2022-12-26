<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Http;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleNamespace;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\Lang;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\Request;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\View;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\DependencyInjection;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AdminArea;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Description of AbstractController
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class AbstractController
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var \ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\View\Smarty
     */
    protected $smarty;

    /**
     * @var \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\ErrorManager
     */
    protected $errorManager;

    /**
     * @var array
     */
    protected static $params = [];

    /**
     * @var Lang
     */
    protected $lang;

    /**
     * @var bool
     */
    protected $isAdmin;

    /**
     * @param Request $request
     * @param Lang $lang
     */
    public function __construct(Request $request, Lang $lang)
    {
        $this->request = $request;
        $this->lang    = $lang;


        if (!$this->request->hasSession())
        {
            $this->request->setSession(new Session());
        }

        $this->isAdmin = $this->isAdmin();
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        self::$params = $params;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return self::$params;
    }

    /**
     * @param string $key
     * @param string|null $default
     * @return mixed
     */
    public function getParamByKey($key, $default = null)
    {

        if (isset(self::$params[$key]))
        {
            return self::$params[$key];
        }

        return $default;
    }

    /**
     * @return string
     */
    public function getControllerClass()
    {
        return 'ModulesGarden\Servers\DigitalOceanDroplets\App\Http\\' . ucfirst(str_replace(DIRECTORY_SEPARATOR, '\\', $this->getControllerType())) . '\\' . ucfirst($this->getController());
    }

    /**
     * Run controller for Admin.
     * Remember, this function work only for AdminArea !!!
     */
    public function execute()
    {
        if(!$this->isValidNamespace())
        {
            return;
        }

        $this->loadContectLang();


        try
        {

            $this->getSmarty()
                    ->setLang($this->lang)
                    ->setTemplateDir($this->getTemplateDir());

            $this->lang->stagCurrentContext('generate' . $this->getController());
            $this->lang->addToContext(lcfirst($this->getController()));

            $result = DependencyInjection::create(
                            $this->getControllerClass(), $this->getAction()
            );

            if ($result)
            {
                $this->showPage($result);
            }
            else
            {
                $this->showPageNotFound();
            }
        }
        catch (\ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException $exc)
        {
            $this->showFatalError($exc);
        }
        catch (\Exception $exc)
        {
            $newExc = new \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException(self::class, $exc->getMessage(), $exc->getCode());

            $this->showFatalError($newExc);
        }
    }

    /**
     *
     * @param Response $result
     */
    protected function showPage($result)
    {
        if ($result instanceof View)
        {
            /**
             * @var View $result
             */
            $result->validateAcl($this->isAdmin);
            $result = $result->genResponse();
        }

        $result->setLang($this->lang);
        $this->getSmarty()->setTemplateDir($this->getTemplateDir(true));

        if ($result instanceof JsonResponse)
        {
            $result->send();
            die();
        }
        elseif ($result instanceof RedirectResponse)
        {
            die($result->send());
        }
        elseif ($result instanceof Response)
        {
            $result->loadlayoutContenct(
                            $this->getPageContext($result), $this->getController(), $this->getAction(), $this->getTemplateDir()
                    )
                    ->sendContent();
        }
    }

    /**
     *
     * @param Response $result
     * @return string
     */
    protected function getPageContext($result)
    {
        try
        {
            $output = $result->getPageContext();
            $this->lang->unstagContext('generate' . $this->getController());
            return $output;
        }
        catch (\ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\ControllerException $exc)
        {
            return $exc->writeMassage();
        }
    }

    /**
     * Show Info Page Not fount
     */
    protected function showPageNotFound()
    {
        $this->lang->unstagContext('generate' . $this->getController());
        $withErrorManager = '';

        if ($this->getErrorManager()->hesError())
        {
            $message          = (string) $this->getErrorManager();
            $withErrorManager = $message;
        }

        $this->getLogger()->addWarning(
                $message, [
            'type'       => $this->getControllerType(),
            'controller' => $this->getController(),
            'action'     => $this->getAction()
                ]
        );
        $message = $this->lang->T('Page not found');
        Helper\response()->setStatusCode(404)
                ->loadlayoutContenct(
                        $withErrorManager, $this->getController(), $this->getAction(), $this->getTemplateDir()
                )
                ->sendContent();
    }

    /**
     * @param \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\Exceptions\MGModuleException $error
     */
    protected function showFatalError($error)
    {
        $this->lang->unstagContext('generate' . $this->getController());
        Helper\response()->setStatusCode(404)->loadlayoutContenct(
                        (string) $error->writeMassage(), $this->getController(), $this->getAction(), $this->getTemplateDir()
                )
                ->sendContent();
    }

    /**
     * @return \ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\ErrorManager
     */
    protected function getErrorManager()
    {
        if (empty($this->errorManager))
        {
            $this->errorManager = ServiceLocator::call('errorManager');
        }
        return $this->errorManager;
    }

    /**
     * @return \ModulesGarden\Servers\DigitalOceanDroplets\Core\Interfaces\LoggerInterface
     */
    protected function getLogger()
    {
        return ServiceLocator::call('logger');
    }

    /**
     * @return View\Smarty
     */
    protected function getSmarty()
    {
        if (empty($this->smarty))
        {
            $this->smarty = ServiceLocator::call('smarty');
        }

        return $this->smarty;
    }

    /**
     * @param bool $withController
     * @return string
     */
    public function getTemplateDir($withController = false, $fullpathWithClient = false)
    {
        $root = (($this->isAdmin || $fullpathWithClient) ? (ModuleConstants::getTemplateDir() . DS) : '');

        $return = $root . strtolower($this->getTemplateType())
                . (($this->isAdmin) ? '' : (DS . $this->getClientTemplate()))
                . (($withController) ? ( DS . 'pages' . DS . lcfirst($this->getController())) : '');

        return $return;
    }

    /**
     * @return string
     */
    protected function getClientTemplate()
    {
        $templateName = ServiceLocator::call(Addon::class)->getConfig('template', 'default');

        if (is_dir(ModuleConstants::getModuleRootDir() . DS . 'templates' . DS . strtolower($this->getControllerType()) . DS . $templateName))
        {
            return $templateName;
        }

        return 'default';
    }

    /**
     * @return \ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\Request
     */
    protected function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return filter_var($this->request->get('mg-page', 'Home'), FILTER_SANITIZE_SPECIAL_CHARS);
    }

    /**
     * @return string
     */
    public static function getModule()
    {
        return \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\getModuleName();
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->request->get('mg-action', 'index');
    }

    /**
     * @return string
     */
    public function getTemplateType()
    {
        return ($this->isAdmin) ? 'Admin' : 'Client';
    }

    public function getControllerType()
    {
        $type           = $this->getType();
        $controllerType = ($this->isAdmin) ? 'Admin' : 'Client';
        $controllerType = ($this->getType() == 'addon') ? $controllerType : 'Server' . DIRECTORY_SEPARATOR . $controllerType;

        return $controllerType;
    }

    /**
     * @return bool
     */
    public static function isAdmin()
    {
        $return = \ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\isAdmin();
        return $return;
    }

    /**
     * Set context lang ( AdminArea or ClientArea )
     */
    protected function loadContectLang()
    {
        $this->lang->setContext(($this->getType() . ($this->isAdmin ? 'AA' : 'CA')));
    }

    /**
     * @return string
     */
    protected function getType()
    {
        return 'server';
    }

    protected function cleanOutputBuffer()
    {
        $outputBuffering = ob_get_contents();
        if($outputBuffering !== FALSE)
        {
            if(!empty($outputBuffering))
            {
                ob_clean();
            }
            else
            {
                ob_start();
            }
        }

        return $this;
    }

    protected function isValidNamespace()
    {
        $namespace  = $this->request->get('namespace');
        if(!$namespace)
        {
            return true;
        }

        return ModuleNamespace::validate($namespace);
    }

}
