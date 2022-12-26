<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 22, 2017)
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

use \ModulesGarden\WordpressManager\App\Interfaces\WordPressConfigInterface;

/**
 * Description of Config
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Config implements WordPressConfigInterface
{
    /**
     *
     * @var Wp
     */
    private $wp;

    function __construct(Wp $wp)
    {
        $this->wp = $wp;
    }


    public function delete($key, $type=null)
    {
        $request = [
            'command' => 'config',
            'action'  => 'delete '. $key,
            'params'  => "--path={$this->wp->getPath()}"
        ];
        if($type){
            $request['params'] .= " --type=".$type;
        }
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
        
    }

    public function getList()
    {
        $request = [
            'command' => 'config',
            'action'  => 'get',
            'params'  => "--path={$this->wp->getPath()} --json" 
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request)['data'];
    }

    public function get($key)
    {
        $request = [
            'command' => 'config',
            'action'  => 'get',
            'params'  => "--path={$this->wp->getPath()} --constant=" . $key
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }
    
    public function has($key)
    {
        $request = [
            'command' => 'config',
            'action'  => 'has '. $key,
            'params'  => "--path={$this->wp->getPath()}" 
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
        
    }

    public function path()
    {
        $request = [
            'command' => 'config',
            'action'  => 'path',
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function set($key, $value, $type)
    {
        $request = [
            'command' => 'config',
            'action'  => sprintf("set %s %s",$key, $value),
            'params'  => "--path={$this->wp->getPath()} --type=" . $type
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

}
