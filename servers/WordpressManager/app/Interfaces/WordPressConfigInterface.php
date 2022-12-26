<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Nov 29, 2017)
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

namespace ModulesGarden\WordpressManager\App\Interfaces;

use ModulesGarden\WordpressManager\App\Models\Installation;

/**
 * Description of WordPressModuleInterface
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
interface WordPressConfigInterface
{
    
    public function delete($key, $type=null);
    
    public function get($key);

    public function has($key);
    
    public function getList();
    
    public function set($key, $value, $type);
    
    public function path();

}
