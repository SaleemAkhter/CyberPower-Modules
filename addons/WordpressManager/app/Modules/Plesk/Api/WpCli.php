<?php


namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api;


use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\Operator\Cli\Extension;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli\Cache;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli\Config;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli\Maintenance;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli\Option;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli\Plugin;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli\Site;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli\Theme;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli\User;
use ModulesGarden\WordpressManager\App\Modules\Softaculous\PleskProvider;

class WpCli
{
    /**
     * @var Extension
     */
    private $extension;
    private $path;
    protected $operators=[];
    private $username;
    private $user;
    private $maintenance;




    /**
     * WpCli constructor.
     * @param RestFullApi $restFullApi
     */
    public function __construct(Extension $provider, $path, $username)
    {
        $this->extension = $provider;
        $this->path = $path;
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return Extension
     */
    public function getExtension()
    {
        return $this->extension;
    }

    public function searchReplace($old, $new){
        $request  =[
            "search-replace",  $old, $new,  "--path={$this->getPath()}"
        ];
        return $this->call($request);
    }

    /**
     * @return Option
     */
    public function option(){
        if(!isset($this->operators[__FUNCTION__])){
            return $this->operators[__FUNCTION__] = new Option($this);
        }
        return $this->operators[__FUNCTION__];
    }

    /**
     * @return Cache
     */
    public function cache(){
        if(!isset($this->operators[__FUNCTION__])){
            return $this->operators[__FUNCTION__] = new Cache($this);
        }
        return $this->operators[__FUNCTION__];
    }

    /**
     * @return Plugin
     */
    public function plugin(){
        if(!isset($this->operators[__FUNCTION__])){
            return $this->operators[__FUNCTION__] = new Plugin($this);
        }
        return $this->operators[__FUNCTION__];
    }

    /**
     * @return Config
     */
    public function config(){
        if(!isset($this->operators[__FUNCTION__])){
            return $this->operators[__FUNCTION__] = new Config($this);
        }
        return $this->operators[__FUNCTION__];
    }

    /**
     * @return Theme
     */
    public function theme(){
        if(!isset($this->operators[__FUNCTION__])){
            return $this->operators[__FUNCTION__] = new Theme($this);
        }
        return $this->operators[__FUNCTION__];
    }

    public function call(array  $request){
        $request = array_merge(["--call" ,"wordpress-manager-cli",  "--wp", $this->username, $this->path],$request);
        return $this->getExtension()->call(["params" =>  $request ])['stdout'];
    }

    public function site(){
        if(!isset($this->operators[__FUNCTION__])){
            return $this->operators[__FUNCTION__] = new Site($this);
        }
        return $this->operators[__FUNCTION__];
    }

    public function maintenance(){
        if(!isset($this->operators[__FUNCTION__])){
            return $this->operators[__FUNCTION__] = new Maintenance($this);
        }
        return $this->operators[__FUNCTION__];
    }

    public function user(){
        if(!isset($this->operators[__FUNCTION__])){
            return $this->operators[__FUNCTION__] = new User($this);
        }
        return $this->operators[__FUNCTION__];
    }
}