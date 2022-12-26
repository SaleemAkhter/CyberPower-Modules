<?php

namespace ModulesGarden\Servers\HetznerVps\App\UI\Console\Pages;

use ModulesGarden\Servers\HetznerVps\App\UI\Console\Helpers\ConsoleManager;
use ModulesGarden\Servers\HetznerVps\Core\Helper\BuildUrl;
use ModulesGarden\Servers\HetznerVps\Core\UI\Builder\BaseContainer;
use ModulesGarden\Servers\HetznerVps\Core\UI\Interfaces\ClientArea;

/**
 * Description of Product
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ConsolePage extends BaseContainer implements ClientArea
{

    protected $id = 'consolePage';
    protected $name = 'consolePage';
    protected $title = 'consolePage';
    protected $console = null;

    protected $errorMessage = null;

    public function initContent()
    {
        try
        {
            $this->console = (new ConsoleManager($this->getWhmcsParams()))->getConsole();
        }
        catch (\Exception $ex)
        {
            $this->errorMessage = $ex->getMessage();
        }
    }

    public function getUrl()
    {
        return $this->console['wss_url'];
    }

    public function getAssetsUrl()
    {
        return BuildUrl::getBaseUrl().'/modules/servers/HetznerVps/vendor/noVNC/';
    }

    public function getPassword()
    {
        return $this->console['password'];
    }

    public function isError()
    {
        return (boolean)!is_null($this->errorMessage);
    }

    public function getError()
    {
        return $this->errorMessage;
    }
}
