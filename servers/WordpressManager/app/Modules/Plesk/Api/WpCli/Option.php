<?php

namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

class Option
{
    /**
     * @var WpCli
     */
    private $wpcli;

    /**
     * Option constructor.
     * @param WpCli $wpcli
     */
    public function __construct(WpCli $wpcli)
    {
        $this->wpcli = $wpcli;
    }



    /**
     *
     * @return array
     */
    public function getList()
    {
        $request  =[ "option", "list","--format=json", "--path={$this->wpcli->getPath()}" ];
        return $this->wpcli->call($request);
    }

    public function get($key)
    {
        $request  =["option", "get", $key, "--path={$this->wpcli->getPath()}"];
        return $this->wpcli->call($request);
    }

    public function update($key, $value)
    {
        $request  =[ "option", "update", $key, $value, "--path={$this->wpcli->getPath()}"];
        return $this->wpcli->call($request);
    }

}