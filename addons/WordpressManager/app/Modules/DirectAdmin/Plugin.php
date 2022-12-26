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

namespace ModulesGarden\WordpressManager\App\Modules\DirectAdmin;

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
            'data'    => ["--path={$this->wp->getPath()}", 
            '--json',
            '--fields=name,status,update,version,title'
        ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function install($name,$activate=false)
    {
        $activate=($activate)?" --activate":"";
        $request = [
            'command' => 'plugin',
            'action'  => 'install',
            'data'    => [
                $name,
                "--path={$this->wp->getPath()} --force".$activate
            ]
        ];

        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }
    
    public function activate($name)
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'activate',
            'data'    => [
                $name,
                "--path={$this->wp->getPath()}"
            ]
        ];

        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function deactivate($name)
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'deactivate',
            'data'    => [
                $name,
                "--path={$this->wp->getPath()}"
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function update($name)
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'update',
            'data'    => [
                $name,
                "--path={$this->wp->getPath()}"
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function updateAll()
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'update',
            'data'    => [
                "--path={$this->wp->getPath()}",
                "--all"
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }
    
    public function search($name)
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'search',
            'data'    => [
                $name,
                "--path={$this->wp->getPath()}",
                "--fields=name,version,slug,rating,last_updated,requires,tested,active_installs",
                "--json" ,
                '--per-page=50'
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }
    
    
    public function detail($slug)
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'search',
            'data'    => [
                $slug,
                "--path={$this->wp->getPath()}",
                "--fields=name,version,slug,rating,last_updated,requires,tested,active_installs,short_description",
                "--json" ,
                '--per-page=50'
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        $data = $this->wp->getApi()->sendWpRequest();
        foreach($data as $p){
            if($p['slug']== $slug){
                return $p;
            }
        }
        return   $data;
    }
    
    public function delete($name)
    {
        $request = [
            'command' => 'plugin',
            'action'  => 'delete',
            'data'    => [
                $name,
                "--path={$this->wp->getPath()}",
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

}
