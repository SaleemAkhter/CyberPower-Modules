<?php

namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

class Plugin
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
        $request  =["plugin", "list", "--fields=name,status,update,version,title", "--format=json", "--path={$this->wpcli->getPath()} $this->defaultParams"];
        return $this->wpcli->call($request);
    }

    public function search($name)
    {
        $request  =[
            "plugin", "search", $name,
            "--fields=name,version,slug,rating,last_updated,requires,tested,active_installs","--per-page=50","--format=json", "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];
        return $this->wpcli->call($request);
    }


    public function detail($slug){
        $request  =[
            "plugin", "search", $slug,
             "--fields=name,version,slug,rating,last_updated,requires,tested,active_installs,short_description","--per-page=50","--format=json",
             "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];
        $data = $this->wpcli->call($request);
        foreach($data as $p){
            if($p['slug']== $slug){
                return $p;
            }
        }
        return   $data;
    }

    public function activate($name)
    {
        $request  =[ "plugin", "activate",$name, "--path={$this->wpcli->getPath()} $this->defaultParams"];
        return $this->wpcli->call($request);
    }

    public function deactivate($name)
    {
        $request  =[
               "plugin", "deactivate", $name, "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];
        return $this->wpcli->call($request);
    }

    public function update($name)
    {
        $request  =[
            "plugin", "update",$name, "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];
        return $this->wpcli->call($request);
    }

    public function updateAll()
    {
        $request  =[
            "plugin", "update", "--all", "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];
        return $this->wpcli->call($request);
    }

    public function install($name)
    {
        $request  =[
            "plugin",  "install",$name, "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];
        return $this->wpcli->call($request);
    }

    public function delete($name)
    {
        $request  =[
            "plugin", "delete",$name, "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];
        return $this->wpcli->call($request);

    }

}