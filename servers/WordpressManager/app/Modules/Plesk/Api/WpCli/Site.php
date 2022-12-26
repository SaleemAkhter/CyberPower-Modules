<?php

namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

class Site
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

    public function switchLanguage($language)
    {
        $request  =[ "site", "switch-language", $language, "--path={$this->wpcli->getPath()}"];
        return $this->wpcli->call($request);
    }
}