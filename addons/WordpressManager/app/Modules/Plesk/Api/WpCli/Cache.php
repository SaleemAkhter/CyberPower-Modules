<?php

namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

class Cache
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
    public function flush()
    {
        $request  =["cache", "flush","--format=json",  "--path={$this->wpcli->getPath()}  $this->defaultParams" ];
        return $this->wpcli->call($request);
    }

}