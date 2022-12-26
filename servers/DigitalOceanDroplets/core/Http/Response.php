<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Http;

use Symfony\Component\HttpFoundation\Response as OrgiRespose;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\DependencyInjection;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\View\MainMenu;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper\WhmcsVersionComparator;

/**
 * Description of Response
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Response extends OrgiRespose
{

    protected $data          = [];
    protected $lang;
    protected $staticName;
    protected $isBreadcrumbs = true;

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

    public function loadlayoutContenct($pageContect, $controller, $action, $path)
    {
        $mainManu = DependencyInjection::create(MainMenu::class)->buildBreadcrumb($controller, $action);

        $menu  = $mainManu->getMenu();
        $addon = ServiceLocator::call(Addon::class);

        $vars = [
            'assetsURL'                => BuildUrl::getAssetsURL(),
            'mainURL'                  => BuildUrl::getUrl(),
            'mainName'                 => ($this->staticName === null) ? $addon->getConfig('name') : $this->staticName,
            'menu'                     => $menu,
            'breadcrumbs'              => ($this->isBreadcrumbs) ? $mainManu->getBreadcrumb() : null,
            'JSONCurrentUrl'           => BuildUrl::getUrl($controller),
            'currentPageName'          => $controller,
            'Addon'                    => null, // ?????????????????????????????
            'isWHMCS6'                 => version_compare($GLOBALS['CONFIG']['Version'], '6.0.0', '>='),
            'mgWhmcsVersionComparator' => new WhmcsVersionComparator(),
            'content'                  => $pageContect,
            'error'                    => $this->getData('status', false) == 'error' ? $this->getData('message', '') : false,
            'success'                  => $this->getData('status', false) == 'success' ? $this->getData('message', '') : false,
            'tagImageModule'           => $addon->getConfig('moduleIcon'),
            'isDebug'                  => (bool) ((int) $addon->getConfig('debug', "0"))
        ];

        try
        {

            $this->content = ServiceLocator::call('smarty')
                    ->setTemplateDir($path)
                    ->view('main', $vars);
        }
        catch (\Exception $e)
        {
            ServiceLocator::call('errorManager')->addError(self::class, $e->getMessage(), $e->getTrace());
        }


        return $this;
    }

    public function loadlayoutHooksContenct($pageContect, $controller, $action, $path)
    {

        $mainManu = DependencyInjection::create(MainMenu::class)->buildBreadcrumb($controller, $action);

        $menu = $mainManu->getMenu();
        $vars = [
            'assetsURL'                => BuildUrl::getAssetsURL(),
            'mainURL'                  => BuildUrl::getUrl(),
            'mainName'                 => ($this->staticName === null) ? ServiceLocator::call(Addon::class)->getConfig('name') : $this->staticName,
            'menu'                     => $menu,
            'breadcrumbs'              => ($this->isBreadcrumbs) ? $mainManu->getBreadcrumb() : null,
            'JSONCurrentUrl'           => BuildUrl::getUrl($controller),
            'currentPageName'          => $controller,
            'Addon'                    => null, // ?????????????????????????????
            'isWHMCS6'                 => version_compare($GLOBALS['CONFIG']['Version'], '6.0.0', '>='),
            'mgWhmcsVersionComparator' => new WhmcsVersionComparator(),
            'content'                  => $pageContect,
            'error'                    => $this->getData('status', false) == 'error' ? $this->getData('message', '') : false,
            'success'                  => $this->getData('status', false) == 'success' ? $this->getData('message', '') : false
        ];

        try
        {


            $this->content = ServiceLocator::call('smarty')
                    ->setTemplateDir($path)
                    ->view('mainHooks', $vars);
        }
        catch (\Exception $e)
        {
            ServiceLocator::call('errorManager')->addError(self::class, $e->getMessage(), $e->getTrace());
        }


        return $this;
    }

}
