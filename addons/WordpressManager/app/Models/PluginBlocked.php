<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jul 25, 2018)
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
 * Description of PluginBlocked
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 * @property string  $name 
 * @property string  $slug
 * @property int  $product_id
 */
class PluginBlocked extends ExtendedEloquentModel
{
    protected $table = 'PluginsBlocked';
    
    /**
     *
     *
     */
    protected $guarded = ['id'];

    /**
     *
     * @var array
     */
    protected $fillable   = ['name', 'slug', 'product_id'];
    protected $softDelete = false;
    public $timestamps    = false;
}
