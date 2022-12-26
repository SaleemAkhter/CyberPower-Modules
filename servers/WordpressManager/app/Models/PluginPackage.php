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
 * Description of PluginPackage
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 * @property int $id
 * @property int $name
 * @property string $description
 * @property int $enable
 * @property \ModulesGarden\WordpressManager\App\Models\PluginPackageItem $items
 * @method PluginPackage enable() Description
 * @method static PluginPackage ofId(array $ids)
 */
class PluginPackage extends ExtendedEloquentModel
{
    protected $table = 'PluginPackage';
    
    /**
     *
     *
     */
    protected $guarded = ['id'];

    /**
     *
     * @var array
     */
    protected $fillable   = ['name', 'description', 'enable'];
    protected $softDelete = false;
    public $timestamps    = false;
    
    public function items()
    {
        return $this->hasMany('\ModulesGarden\WordpressManager\App\Models\PluginPackageItem','plugin_package_id');
    }
    
    public function scopeOfId($query, array $ids)
    {
        return $query->whereIn('id', $ids);
    }
    
    public function scopeEnable($query)
    {
        return $query->where('enable', 1);
    }
}
