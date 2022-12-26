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

namespace ModulesGarden\WordpressManager\App\Models;

use \ModulesGarden\WordpressManager\Core\Models\ExtendedEloquentModel;
/**
 * Description of PluginPackageItem
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 * @property int $id
 * @property int $name
 * @property int $slug
 * @property \ModulesGarden\WordpressManager\App\Models\ThemePackage $themePackage
 */
class ThemePackageItem  extends ExtendedEloquentModel
{
    protected $table = 'ThemePackageItem';
    
    /**
     *
     *
     */
    protected $guarded = ['id'];

    /**
     *
     * @var array
     */
    protected $fillable   = ['name', 'slug', 'theme_package_id'];
    protected $softDelete = false;
    public $timestamps    = false;
    
    public function themePackage()
    {
        return $this->hasOne('\ModulesGarden\WordpressManager\App\Models\ThemePackage', 'id', 'theme_package_id');
    }
}
