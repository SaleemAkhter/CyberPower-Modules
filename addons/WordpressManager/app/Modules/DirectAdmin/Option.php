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

/**
 * Description of Option
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Option
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

    /**
     * 
     * @return array
     */
    public function getList()
    {

        $request = [
            'command' => 'option',
            'action'  => 'list',
            'data'    => ["--path={$this->wp->getPath()}",
                "--json"
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        $this->wp->getApi()->sendWpRequest();
        return $this->wp->getApi()->sendWpRequest();
    }

    public function get($key)
    {
        $request = [
            'command' => 'option',
            'action'  => 'get',
            'data'    => [
                $key,
                "--path={$this->wp->getPath()}",
                "--json"
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        $this->wp->getApi()->sendWpRequest();
        return $this->wp->getApi()->sendWpRequest();
    }

    public function update($key, $value)
    {
        $request = [
            'command' => 'option',
            'action'  => 'update',
            'data'    => [
                $key,
                $value,
                "--path={$this->wp->getPath()}",
            ]
        ];
        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }
}
