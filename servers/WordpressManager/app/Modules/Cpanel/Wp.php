<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 11, 2017)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\Modules\Cpanel;

use \ModulesGarden\WordpressManager\App\Helper\LangException;

/**
 * Description of Wp
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Wp
{
    /**
     *
     * @var Uapi
     */
    private $uapi;
    private $path;
    const END_POINT = '/execute/Wordpress/';
    private $plugin;
    private $option;
    private $config;
    private $theme;
    private $user;
    private $maintenance;
    private $defaultParams = ' --skip-plugins --skip-themes ';

    function __construct($path)
    {
        $this->path = $path . $this->defaultParams;
    }

    /**
     * 
     * @return Uapi
     */
    function getUapi()
    {
        return $this->uapi;
    }

    function getPath()
    {
        return $this->path;
    }

    function getMaintenance()
    {
        return $this->maintenance;
    }

    function getSpeedtest()
    {
        return $this->speedtest;
    }

    function getUser()
    {
        return $this->user;
    }

    function setUapi(Uapi &$uapi)
    {
        $this->uapi = $uapi;
    }

    function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * 
     * @return Plugin
     * @throws \Exception
     */
    public function plugin()
    {
        if (!is_null($this->plugin)) {
            return $this->plugin;
        }
        if (!$this->uapi) {
            throw (new LangException("UAPI instance is not defined"))->translate();
        }
        return $this->plugin = new Plugin($this);
    }

    /**
     * 
     * @return Cache
     * @throws \Exception
     */
    public function cache()
    {
        if (!is_null($this->cache)) {
            return $this->cache;
        }
        if (!$this->uapi) {
            throw (new LangException("UAPI instance is not defined"))->translate();
        }
        return $this->cache = new Cache($this);
    }

    /**
     * 
     * @return Option
     * @throws \Exception
     */
    public function option()
    {
        if (!is_null($this->option)) {
            return $this->option;
        }
        if (!$this->uapi) {
            throw (new LangException("UAPI instance is not defined"))->translate();
        }
        return $this->option = new Option($this);
    }

    /**
     * 
     * @return Config
     * @throws \Exception
     */
    public function config()
    {
        if (!is_null($this->config)) {
            return $this->config;
        }
        if (!$this->uapi) {
            throw (new LangException("UAPI instance is not defined"))->translate();
        }
        return $this->config = new Config($this);
    }

    public function theme()
    {
        if (!is_null($this->theme)) {
            return $this->theme;
        }
        if (!$this->uapi) {
            throw (new LangException("UAPI instance is not defined"))->translate();
        }
        return $this->theme = new Theme($this);
    }

    public function searchReplace($old, $new)
    {
        if (!$this->uapi) {
            throw (new LangException("UAPI instance is not defined"))->translate();
        }
        $request = [
            'command' => 'search-replace',
            'action'  => $old,
            'value'   => $new,
            'params'  => "--path={$this->getPath()}"
        ];
        $data = $this->getUapi()->exec('api', Self::END_POINT, $request);
        return $data['data'];
    }

    public function site()
    {
        if (!is_null($this->site)) {
            return $this->site;
        }
        if (!$this->uapi) {
            throw (new LangException("UAPI instance is not defined"))->translate();
        }
        return $this->site = new Site($this);
    }

    public function maintenance()
    {
        if (!is_null($this->maintenance)) {
            return $this->maintenance;
        }
        if (!$this->uapi) {
            throw (new LangException("UAPI instance is not defined"))->translate();
        }
        return $this->maintenance = new Maintenance($this);
    }

    public function user()
    {
        if (!is_null($this->user)) {
            return $this->user;
        }
        if (!$this->uapi) {
            throw (new LangException("UAPI instance is not defined"))->translate();
        }
        return $this->user = new User($this);
    }
}
