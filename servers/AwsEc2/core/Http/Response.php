<?php

namespace ModulesGarden\Servers\AwsEc2\Core\Http;

use ModulesGarden\Servers\AwsEc2\Core\Http\View\Smarty;
use ModulesGarden\Servers\AwsEc2\Core\Traits\AppParams;
use ModulesGarden\Servers\AwsEc2\Core\ServiceLocator;
use ModulesGarden\Servers\AwsEc2\Core\DependencyInjection;
use ModulesGarden\Servers\AwsEc2\Core\Http\View\MainMenu;
use ModulesGarden\Servers\AwsEc2\Core\Helper\BuildUrl;
use ModulesGarden\Servers\AwsEc2\Core\App\Controllers\Instances\Addon\Config;
use ModulesGarden\Servers\AwsEc2\Core\Helper\WhmcsVersionComparator;

use Symfony\Component\HttpFoundation\Response as SymfonyRespose;

/**
 * Description of Response
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Response extends SymfonyRespose
{
    use \ModulesGarden\Servers\AwsEc2\Core\Traits\Template;
    use \ModulesGarden\Servers\AwsEc2\Core\Traits\IsAdmin;
    use \ModulesGarden\Servers\AwsEc2\Core\UI\Traits\RequestObjectHandler;
    use AppParams;

    protected $data = [];
    protected $lang;
    protected $staticName;
    protected $isBreadcrumbs = true;

    protected $isDebug = null;
    
    /*
     * determines data return type as HTML only
     */
    protected $forceHtml = false;

    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }
    
    public function setBreadcrumbs($isBreadcrumbs)
    {
        $this->isBreadcrumbs = $isBreadcrumbs;
        
        return $this;
    }
    
    public function isBreadcrumbs()
    {
        return $this->isBreadcrumbs;
    }

    public function setName($name)
    {
        $this->staticName = $name;

        return $this;
    }

    public function getName()
    {
        return $this->staticName;
    }

    public function getLang()
    {
        if (empty($this->lang))
        {
            $this->lang = ServiceLocator::call('lang');
        }
        return $this->lang;
    }

    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    public function getError()
    {
        $data = $this->getData();
        if (isset($data['status']) && $data['status'] == 'error')
        {
            return $data['message'];
        }

        return false;
    }

    public function getSuccess()
    {
        $data = $this->getData();
        if (isset($data['status']) && $data['status'] == 'success')
        {
            return $data['message'];
        }

        return false;
    }

    public function getData($key = null, $dafault = null)
    {
        if ($key == null)
        {
            return $this->data;
        }

        if (isset($this->data[$key]) || array_key_exists($key, $this->data))
        {
            return $this->data[$key];
        }

        return $dafault;
    }

    public function withSuccess($message = '')
    {
        $data            = $this->getData();
        $data['status']  = 'success';
        $data['message'] = $message;

        $this->setData($data);

        return $this;
    }

    public function withError($message = '')
    {
        $data            = $this->getData();
        $data['status']  = 'error';
        $data['message'] = $message;

        $this->setData($data);

        return $this;
    }

    public function getPageContext()
    {
        $tpl = $this->getData('tpl', 'home');

        return ServiceLocator::call('smarty')
                        ->setLang($this->getLang())
                        ->view($tpl, $this->getData('data', []), $this->getData('tplDir', false));
    }

    /**
     * @param $responseResolver \ModulesGarden\Servers\AwsEc2\Core\App\Controllers\ResponseResolver
     */
    public function getHtmlResponse($responseResolver)
    {
        $pageController = $responseResolver->getPageController();

        $path = $responseResolver->getTemplateDir();
        $fileName = $pageController->getTemplateName() ? : 'main';
        $controller = $pageController->getControllerClass(true);

        $action = $pageController->getControllerMethod();

        $mainMenu = DependencyInjection::create(MainMenu::class)->buildBreadcrumb($controller,  $action, []);
        $menu = $mainMenu->getMenu();

        $addon = ServiceLocator::call(Config::class);

        $vars = [
            'assetsURL'                => BuildUrl::getAssetsURL(),
            'customAssetsURL'          => BuildUrl::getAssetsURL(true),
            'isCustomIntegrationCss'   => BuildUrl::isCustomIntegrationCss(),
            'isCustomModuleCss'        => BuildUrl::isCustomModuleCss(),
            'mainURL'                  => BuildUrl::getUrl(),
            'mainName'                 => ($this->staticName === null) ? $addon->getConfigValue('name') : $this->staticName,
            'menu'                     => $menu,
            'breadcrumbs'              => ($this->isBreadcrumbs) ? $mainMenu->getBreadcrumb() : null,
            'JSONCurrentUrl'           => BuildUrl::getUrl($controller),
            'currentPageName'          => $controller,
            'mgWhmcsVersionComparator' => new WhmcsVersionComparator(),
            'content'                  => $this->getPageContext(),
            'moduleRequirementsErrors' => $this->checkModuleRequirements(),
            'error'                    => $this->getData('status', false) == 'error' ? $this->getData('message', '') : false,
            'success'                  => $this->getData('status', false) == 'success' ? $this->getData('message', '') : false,
            'tagImageModule'           => $addon->getConfigValue('moduleIcon'),
            'isDebug'                  => (bool)((int)$addon->getConfigValue('debug', "0")),
            'errorPageDetails'         => $this->getErrorPageData($responseResolver)
        ];

        try
        {
            $this->loadLangContext();

            if ((!$responseResolver->isAdmin() && !$this->forceHtml))
            {
                $vars['MGLANG'] = $this->lang;
                if (strpos(trim(self::class, '\\'), 'ModulesGarden\Servers') === 0)
                {
                    return $this->returnClientProvisioning($vars, $path, $fileName);                
                }

                return $this->returnClientAddon($vars, $path, $fileName);
            }

            /**
             * @var Smarty $pageContent
             */
            $pageContent = ServiceLocator::call('smarty')
                ->setLang($this->lang)
                ->setTemplateDir($path)
                ->view($fileName, $vars);

            return $pageContent;
        }
        catch (\Exception $e)
        {
            ServiceLocator::call('errorManager')->addError(self::class, $e->getMessage(), $e->getTrace());
        }
    }

    public function returnClientAddon($vars, $path, $fileName)
    {
        return [
            'vars' => $vars,
            'templatefile' => str_replace(\ModulesGarden\Servers\AwsEc2\Core\ModuleConstants::getTemplateDir() . DIRECTORY_SEPARATOR,
                '', $path . DIRECTORY_SEPARATOR . $fileName),
            'requirelogin' => true,
            'breadcrumb' => ($this->isBreadcrumbs) ? $this->data['data']['breadcrumbs'] : null
        ];        
    }
    
    public function returnClientProvisioning($vars, $path, $fileName)
    {
        $templateVarName = ($this->getRequestValue('a', false) === 'management') ? 'tabOverviewReplacementTemplate' : 'templatefile';          

        return [
            $templateVarName => str_replace(\ModulesGarden\Servers\AwsEc2\Core\ModuleConstants::getTemplateDir() . DIRECTORY_SEPARATOR,
                '', $path . DIRECTORY_SEPARATOR . $fileName),
            'templateVariables' => $vars
        ];        
    }    
    
    protected function checkModuleRequirements()
    {
        $requirementsHandler = new \ModulesGarden\Servers\AwsEc2\Core\App\Requirements\Checker();
        $requirementsErrors = $requirementsHandler->getUnfulfilledRequirements();
        if ($requirementsErrors)
        {
            return implode('<br>', $requirementsErrors);
        }

        return $this->getData('status', false) == 'error' ? $this->getData('message', '') : false;
    }

    /**
     * Set context lang ( AdminArea or ClientArea )
     */
    protected function loadLangContext()
    {
        $this->lang->setContext(($this->getAppParam('moduleAppType') . ($this->isAdmin() ? 'AA' : 'CA')));
    }

    public function setForceHtml()
    {
        $this->forceHtml = true;

        return $this;
    }

    public function unsetForceHtml()
    {
        $this->forceHtml = false;

        return $this;
    }
    
    public function isDebugOn()
    {   
        if ($this->isDebug === null)
        {
            $addon = ServiceLocator::call(Config::class);
        
            $this->isDebug =  (bool)((int)$addon->getConfigValue('debug', "0"));
        }
        
        return $this->isDebug;
    }
    
    public function getErrorPageData($responseResolver)
    {
        $pageController = $responseResolver->getPageController();
        $error = $pageController->getParam('mgErrorDetails');
        if (!$error)
        {
            return null;
        }

        $errorDetails = $error->getDetailsToDisplay();

        return $errorDetails;
    }
}
