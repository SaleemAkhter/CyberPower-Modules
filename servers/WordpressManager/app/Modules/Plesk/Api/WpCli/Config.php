<?php

namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

class Config
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

    /**
     *
     * @return array
     */
    public function getList()
    {
        $request  =[ "config", "get","--format=json",  "--path={$this->wpcli->getPath()}  $this->defaultParams"];
        return $this->wpcli->call($request);
    }

    public function delete($key, $type=null)
    {
        $request  = ["config", "delete", $key, "--type={$type}",  "--path={$this->wpcli->getPath()}  $this->defaultParams"];
        return $this->wpcli->call($request);
    }

    public function get($key)
    {
        $request  = ["config", "get", " --constant=".$key,  "--path={$this->wpcli->getPath()}  $this->defaultParams"];
        return $this->wpcli->call($request);
    }

    public function has($key)
    {
        $request  =["config", "has", $key,  "--path={$this->wpcli->getPath()}  $this->defaultParams"];
        return $this->wpcli->call($request);
    }

    public function path()
    {
        $request  = ["config", "path",  "--path={$this->wpcli->getPath()}  $this->defaultParams"];
        return $this->wpcli->call($request);
    }

    public function set($key, $value, $type)
    {
        $request  = [ "config", "set", $key, $value, "--path={$this->wpcli->getPath()}  $this->defaultParams" ];
        return $this->wpcli->call($request);
    }

}