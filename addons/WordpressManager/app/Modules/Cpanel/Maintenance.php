<?php

namespace ModulesGarden\WordpressManager\App\Modules\Cpanel;

use \ModulesGarden\WordpressManager\App\Interfaces\MaintenanceInterface;

class Maintenance implements MaintenanceInterface
{

    private $wp;

    function __construct(Wp $wp)
    {
        $this->wp = $wp;
    }

    public function enable()
    {
        $request = [
            'command' => 'maintenance-mode',
            'action'  => 'activate',
            'params'  => "--path={$this->wp->getPath()}"
        ];

        $data = $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);

        return $data['data'];
    }

    public function disable()
    {
        $request = [
            'command' => 'maintenance-mode',
            'action'  => 'deactivate',
            'params'  => "--path={$this->wp->getPath()}"
        ];

        $data = $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);

        return $data['data'];
    }

    public function checkStatus()
    {
        $request = [
            'command' => 'maintenance-mode',
            'action'  => 'status',
            'params'  => "--path={$this->wp->getPath()}"
        ];

        $data = $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);

        return $data['data'];
    }
}
