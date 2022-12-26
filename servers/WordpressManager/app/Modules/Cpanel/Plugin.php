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

use \ModulesGarden\WordpressManager\App\Interfaces\WordPressPluginInterface;
/**
 * Description of Plugin
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Plugin implements WordPressPluginInterface
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

    public function getList()
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'list',
            'params'  => "--path={$this->wp->getPath()} --json --fields=name,status,update,version,title"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function search($name)
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'search',
            'value'   => $name,
            'params'  => "--path={$this->wp->getPath()} --fields=name,version,slug,rating,last_updated,requires,tested,active_installs --json".
            " --per-page=50"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }
    
    
    public function detail($slug){
        $request = [
            'command' => 'plugin',
            'action'  => 'search '.$slug,
            'params'  => "--path={$this->wp->getPath()} --fields=name,version,slug,rating,last_updated,requires,tested,active_installs,short_description ".
            " --per-page=50 --format=json"
        ];
        $data = $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
        foreach($data['data'] as $p){
            if($p['slug']== $slug){
                return $p;
            }
        }
        return   $data;
    }
    
    public function activate($name)
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'activate ' . $name,
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function deactivate($name)
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'deactivate ' . $name,
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function update($name)
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'update ' . $name,
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function updateAll()
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'update',
            'params'  => "--path={$this->wp->getPath()} --all"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }
    
    public function install($name)
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'install ' . $name,
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }
    
    public function delete($name)
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'delete ' . $name,
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

}
