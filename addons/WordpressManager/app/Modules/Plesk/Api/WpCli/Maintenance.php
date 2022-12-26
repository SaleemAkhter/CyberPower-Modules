<?php

namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;
use \ModulesGarden\WordpressManager\App\Interfaces\MaintenanceInterface;

class Maintenance implements MaintenanceInterface
{

    /**
     * @var WpCli
     */
    private $wpcli;
    private $defaultParams;

    /**
     * Option constructor.
     * @param WpCli $wpcli
     */
    public function __construct(WpCli $wpcli)
    {
        $this->wpcli = $wpcli;
        $this->defaultParams = ' --skip-plugins --skip-themes';
    }

    public function enable()
    {
        $request = [
            "maintenance-mode",
            "activate",
            "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];

        return $this->wpcli->call($request);
    }

    public function disable()
    {
        $request = [
            "maintenance-mode",
            "deactivate",
            "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];

        return $this->wpcli->call($request);

    }

    public function checkStatus()
    {
        $request = [
            "maintenance-mode",
            "status",
            "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];

        return $this->wpcli->call($request);
    }

}
