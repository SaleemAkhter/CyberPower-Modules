<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 12, 2018)
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

namespace ModulesGarden\WordpressManager\App\Http\Admin;

use ModulesGarden\WordpressManager\Core\Http\AbstractController;
use ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager as main;
/**
 * Description of PluginPackage
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InstanceImage extends AbstractController
{
    public function index()
    {
        return Helper\view()->setCustomJsCode()->addElement(main\App\UI\Admin\InstanceImage\InstanceImageDataTable::class);
    }

}
