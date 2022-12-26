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

use \ModulesGarden\WordpressManager\App\Interfaces\ThemeInterface;
/**
 * Description of Plugin
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Theme implements ThemeInterface
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
            'command' => 'theme',
            'action'  => 'list',
            'data'    => ["--path={$this->wp->getPath()}",
                "--fields=name,status,update,version,title,description",
                "--format=json"   
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }
    
    public function search($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'search',
            'data'  => [ $name, 
                         "--path={$this->wp->getPath()}", 
                         "--fields=name,version,slug,rating,screenshot_url,preview_url,description",
                         "--per-page=50",
                        "--format=json" ]
            ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }
    
    public function activate($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'activate',
            'data'    => [ $name,
                         "--path={$this->wp->getPath()}"]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function delete($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'delete',
            'data'    => [ $name,
                          "--path={$this->wp->getPath()}"]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function disable($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'disable',
            'data'    => [ $name,
                          "--path={$this->wp->getPath()}"]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function enable($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'enable',
            'data'    => [ $name,
                          "--path={$this->wp->getPath()}"]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function get($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'get',
            'data'    => [ $name,
                          "--path={$this->wp->getPath()}",
                          "--format=json"]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function install($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'install',
            'data'    => [ $name,
                          "--path={$this->wp->getPath()}"]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    

    public function update($name)
    {
       $request = [
            'command' => 'theme',
            'action'  => 'update',
            'data'    => [ $name,
                          "--path={$this->wp->getPath()}"]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function updateAll()
    {
        $request = [
            'command' => 'theme',
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


}
