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
            'params'  => "--path={$this->wp->getPath()} --fields=name,status,update,version,title,description --format=json"
        ];
        $data = $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
        return $data['data'];
    }
    
    public function search($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => "search",
            'value'   => $name,
            'params'  => "--path={$this->wp->getPath()} --fields=name,version,slug,rating,screenshot_url,preview_url,description".
             " --per-page=50 --format=json"
        ];
        $data = $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
        return $data['data'];
    }
    
    public function activate($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'activate '.$name,
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function delete($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'delete '.$name,
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function disable($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'disable '.$name,
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function enable($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'enable '.$name,
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function get($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'get '.$name,
            'params'  => "--path={$this->wp->getPath()} --format=json"
        ];
         return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function install($name)
    {
        $request = [
            'command' => 'theme',
            'action'  => 'install '.$name,
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    

    public function update($name)
    {
       $request = [
            'command' => 'theme',
            'action'  => 'update '.$name,
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request); 
    }


}
