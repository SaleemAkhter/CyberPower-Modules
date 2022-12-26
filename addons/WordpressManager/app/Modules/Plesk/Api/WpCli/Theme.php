<?php

namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

class Theme
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
        $request  =[
            "theme", "list",
            "--fields=name,status,update,version,title,description","--format=json", "--path={$this->wpcli->getPath()} $this->defaultParams",
        ];
        return $this->wpcli->call($request);
    }

    public function search($name)
    {
        $request  =[
            "theme", "search", $name,
            "--fields=name,version,slug,rating,screenshot_url,preview_url,description", "--per-page=50","--format=json", "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];
        return $this->wpcli->call($request);
    }

    public function activate($name)
    {
        $request  =[
            "theme", "activate", $name, "--path={$this->wpcli->getPath()} $this->defaultParams",
        ];
        return $this->wpcli->call($request);
    }

    public function delete($name)
    {
        $request  =[
            "theme", "delete", $name, "--path={$this->wpcli->getPath()} $this->defaultParams",
        ];
        return $this->wpcli->call($request);
    }

    public function disable($name)
    {
        $request  =[
            "theme", "disable", $name, "--path={$this->wpcli->getPath()} $this->defaultParams",
        ];
        return $this->wpcli->call($request);
    }

    public function enable($name)
    {
        $request  =[
            "theme", "enable", $name, "--path={$this->wpcli->getPath()} $this->defaultParams",
        ];
        return $this->wpcli->call($request);
    }

    public function get($name)
    {
        $request  =[
            "theme", "get", $name,
            "--format=json", "--path={$this->wpcli->getPath()} $this->defaultParams",
        ];
        return $this->wpcli->call($request);
    }

    public function install($name)
    {
        $request  =[
            "theme", "install", $name, "--path={$this->wpcli->getPath()} $this->defaultParams",
        ];
        return $this->wpcli->call($request);
    }



    public function update($name)
    {
        $request  =[
            "theme", "update", $name, "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];
        return $this->wpcli->call($request);
    }

}