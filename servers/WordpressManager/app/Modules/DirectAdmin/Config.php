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

namespace ModulesGarden\WordpressManager\App\Modules\DirectAdmin;

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

    public function get($key)
    {
        $request = [
            'command' => 'config',
            'action'  => 'get',
            'data'    => ["--path={$this->wp->getPath()}",
                "--constant={$key}"
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function delete($key, $type=null)
    {
        $request = [
            'command' => 'config',
            'action'  => 'delete',
            'data'    => [$key, "--path={$this->wp->getPath()}",
            ]
        ];
        if($type){
            $request['data'] = array_merge($request['data'], ["--type=".$type]);
        }
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function getList()
    {
        $request = [
            'command' => 'config',
            'action'  => 'get',
            'data'    => ["--path={$this->wp->getPath()}",
                "--json"
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
        
    }

    public function has($key)
    {
        $request = [
            'command' => 'config',
            'action'  => 'has',
            'data'    => [$key, "--path={$this->wp->getPath()}",
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function path()
    {
        $request = [
            'command' => 'config',
            'action'  => 'path',
            'data'    => [$key, "--path={$this->wp->getPath()}",
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function set($key, $value, $type)
    {
        $request = [
            'command' => 'config',
            'action'  => 'set',
            'data'    => [$key, $value, "--type={$type}", "--path={$this->wp->getPath()}",
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }
}
