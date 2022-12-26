<?php
/* * ********************************************************************
 * Wordpress_Manager Product developed. (Jan 17, 2018)
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

/**
 *
 * @author pawelk
 */
interface ThemeInterface
{
    public function getList();
    
    public function activate($name);
    
    public function delete($name);
    
    public function disable($name);
    
    public function enable($name);
    
    public function get($name);
    
    public function install($slug);
    
    public function search($name);
    
    public function update($name);
}
