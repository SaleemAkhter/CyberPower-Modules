<?php

namespace ModulesGarden\WordpressManager\App\Modules\DirectAdmin;

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
            'data'  => ["--path={$this->wp->getPath()}"]
        ];

        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        $data = $this->wp->getApi()->sendWpRequest();

        return $data;
    }

    public function disable()
    {
        $request = [
            'command' => 'maintenance-mode',
            'action'  => 'deactivate',
            'data'  => ["--path={$this->wp->getPath()}"]
        ];

        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        $data = $this->wp->getApi()->sendWpRequest();
        
        return $data;
    }

    public function checkStatus()
    {
        $request = [
            'command' => 'maintenance-mode',
            'action'  => 'status',
            'data'    => ["--path={$this->wp->getPath()}"]
        ];

        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        $data = $this->wp->getApi()->sendWpRequest();
        return $data;
    }
}
