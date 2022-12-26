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
 * @property string $name
 * @property string $soft
 * @property string $domain
 * @property string $protocol
 * @property string $server_host
 * @property int $port
 * @property string $ftp_user
 * @property string $ftp_pass
 * @property string $ftp_path
 * @property string $installed_path
 * @property int $installation_id
 * @property int $enable
 * @property int $private
 * @property \ModulesGarden\WordpressManager\App\Models\Installation $installation
 * @method InstanceImage enable()
 * @method InstanceImage ofId(array $ids)
 * @method InstanceImage ofInstallationId($id)
 * @method InstanceImage ofUserId($id)
 */
class InstanceImage extends ExtendedEloquentModel
{
    protected $table = 'InstanceImage';
    
    protected $guarded = ['id'];

    /**
     * @var array
     */
    protected $fillable   = ['name','soft','domain','protocol','server_host','port','ftp_user', 'ftp_pass', 'ftp_path', 'installed_path', 'installation_id', 'enable', 'user_id'];
    protected $softDelete = false;
    public $timestamps    = true;
    
    public function installation()
    {
        return $this->hasOne('\ModulesGarden\WordpressManager\App\Models\Installation','installation_id');
    }
    
    public function scopeOfId($query, array $ids)
    {
        return $query->whereIn('id', $ids);
    }
    
    public function scopeEnable($query)
    {
        return $query->where('enable', 1);
    }
    
    public function scopeOfInstallationId($query, $id)
    {
        return $query->where('installation_id', $id);
    }
    
    public function scopeOfUserId($query, $id)
    {
        return $query->where('user_id', $id);
    }
   
}
