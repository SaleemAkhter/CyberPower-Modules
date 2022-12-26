<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 5, 2017)
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
use ModulesGarden\WordpressManager\Core\Models\Whmcs\Hosting;

/**
 * Description of Installation
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 * @property int $id
 * @property int $user_id
 * @property int $hosting_id
 * @property string $relation_id
 * @property string $domain
 * @property string $url
 * @property string $path
 * @property string $staging
 * @property string $version
 * @property string $username
 * @property Whmcs\Hosting $hosting
 * @property int $domain_id
 * @property \ModulesGarden\WordpressManager\Core\Models\Whmcs\Client $client
 * @method static Installation ofHostingId(int $hostingId)
 * @method Installation ofRelationNotIn(array $relationIds)
 * @method Installation ofDomain($domain)
 * @method Installation ofPath($path)
 * @method Installation ofUrl($url)
 * @method static Installation ofId(int $id)
 * @method Installation ofUserId(int $id)
 * @method Installation ofRelationId($relationId)
 * @property int $auto
 */
class Installation extends ExtendedEloquentModel
{
    protected $table = 'Installations';
    protected $casts=[
        'additional_data'=>'object'
    ];
    /**
     *
     *
     */
    protected $guarded = ['id'];

    /**
     *
     * @var array
     */
    protected $fillable   = ['user_id', 'hosting_id', 'relation_id', 'domain', 'url', 'path', 'version', 'staging', 'username', 'domain_id','auto','site_name','additional_data'];
    protected $softDelete = false;
    public $timestamps    = true;

    public function hosting()
    {
        return $this->hasOne(Whmcs\Hosting::class, 'id', 'hosting_id');
    }

    public function client()
    {
        return $this->hasOne('\ModulesGarden\WordpressManager\Core\Models\Whmcs\Client', 'id', 'user_id');
    }

    public function websiteDetails()
    {
        return $this->hasOne(websiteDetails::class, 'wpid', 'id');
    }

    /**
     * 
     * @param Hosting $hosting
     * @param type $relationId
     * @return this
     * @deprecated since version 1.0.0
     */
    public static function forHostingAndRelation(Hosting $hosting, $relationId)
    {
        throw new \Exception(__FILE__ . ":" . __LINE__ . " " . __METHOD__ . ' is deprecated ');
        if (Installation::where('hosting_id', $hosting->id)->where('relation_id', $relationId)->count())
        {
            return Installation::where('hosting_id', $hosting->id)->where('relation_id', $relationId)->first();
        }
        $model              = new Installation;
        $model->hosting_id  = $hosting->id;
        $model->relation_id = $relationId;
        $model->user_id     = $hosting->userid;
        return $model;
    }

    /**
     * 
     * @param Hosting $hosting
     * @param  array $relationIds
     * @return this
     * @deprecated since version 1.0.0
     */
    public static function forHostingAndRelationNotIn(Hosting $hosting, array $relationIds)
    {
        throw new \Exception(__FILE__ . ":" . __LINE__ . " " . __METHOD__ . ' is deprecated ');
        $collection = Installation::where('hosting_id', $hosting->id);
        if (!empty($relationIds))
        {
            $collection->whereNotIn('relation_id', $relationIds);
        }
        return $collection;
    }

    public function staging()
    {
        if ($this->staging == "0")
        {
            throw new \Exception(sprintf("The instalation %s is not stagin", $this->id));
        }
        return Installation::where("relation_id", $this->staging)
                        ->where("user_id", $this->user_id)
                        ->firstOrFail();
    }

    public function getSoftId()
    {
        $ex = explode("_", $this->relation_id);
        return $ex['0'];
    }

    public function isHttps()
    {
        return preg_match('/https/', $this->url);
    }

    public function scopeOfHostingId($query, $hostingId)
    {
        return $query->where('hosting_id', $hostingId);
    }

    public function scopeOfRelationNotIn($query, $relationIds)
    {
        return $query->whereNotIn('relation_id', $relationIds);
    }

    public function scopeOfDomain($query, $domain)
    {
        return $query->where('domain', $domain);
    }

    public function scopeOfPath($query, $path)
    {
        return $query->where('path', $path);
    }

    public function scopeOfUrl($query, $url)
    {
        return $query->where('url', $url);
    }

    public function scopeOfId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopeOfUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfRelationId($query, $relationId)
    {
        return $query->where('relation_id', $relationId);
    }

    public function getInstallationProductSettings(){
        $hosting = $this->hosting()->first();

        return $hosting->productSettings()->first();
    }
}
