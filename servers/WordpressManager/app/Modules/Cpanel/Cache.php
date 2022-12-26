<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 18, 2017)
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

/**
 * Description of Cache
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Cache
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

    public function flush()
    {
        $request = [
            'command' => 'cache',
            'action'  => 'flush',
            'params'  => "--path={$this->wp->getPath()}"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }
}
