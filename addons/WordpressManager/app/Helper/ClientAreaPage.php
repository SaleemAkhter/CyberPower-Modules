<?php

namespace ModulesGarden\WordpressManager\App\Helper;

use ModulesGarden\WordpressManager\Core\Http\Request;
use ModulesGarden\WordpressManager\App\UI\Widget\DoeSectionsSettings\DoeSettingsDataProvider;
use ModulesGarden\WordpressManager\Core\Helper;

/**
 * Description of ClientAreaPage
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class ClientAreaPage
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var DoeSettingsDataProvider
     */
    protected $doeSettingsDataProvider;
    protected $vars = [];

    /**
     * @param Request $request
     * @param DoeSettingsDataProvider $doeSettingsDataProvider
     */
    public function __construct(Request $request, DoeSettingsDataProvider $doeSettingsDataProvider)
    {
        $this->request                 = $request;
        $this->doeSettingsDataProvider = $doeSettingsDataProvider;
    }

    public function setVars(array $vars = [])
    {
        $this->vars = $vars;

        return $this;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function isOrderdomain()
    {
        return (($this->getVar("currentPageName") === "orderdomain") ? true : false);
    }

    public function isChangePage()
    {
        $fileName                 = $this->getVar("filename");
        $action                   = $this->getRequest()->get('a', false);
        $domeain                  = $this->getRequest()->get('domain', false);
        $replaceStandardRegistrar = $this->doeSettingsDataProvider->getValueById("replaceStandardRegistrar");

        if ($fileName == 'domainchecker' || ($fileName == 'cart' && $action == 'add' && $domeain == 'transfer') || ($fileName == 'cart' && $action == 'add' && $domeain == 'register')
        )
        {
            if ($replaceStandardRegistrar === "on")
            {
                return true;
            }
        }

        return false;
    }

    protected function getVar($key = "")
    {
        if (isset($this->vars[$key]) === false)
        {
            return null;
        }

        return $this->vars[$key];
    }

    public function redirect($action = "", array $params = [])
    {
        return Helper\redirect("orderDomain", $action, $params);
    }
}
