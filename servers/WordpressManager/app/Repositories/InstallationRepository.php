<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 11, 2017)
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

namespace ModulesGarden\WordpressManager\App\Repositories;

use ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;

/**
 * Description of InstallationRepository
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class InstallationRepository extends BaseRepository
{

    function __construct()
    {
        $this->model = with(new Installation);
    }

    /**
     * 
     * @param Hosting $hosting
     * @param type $relationId
     * @return Installation
     */
    public function forHostingAndRelation(Hosting $hosting, $relationId)
    {
        if ($this->model->where('hosting_id', $hosting->id)->where('relation_id', $relationId)->count())
        {
            return $this->model->where('hosting_id', $hosting->id)->where('relation_id', $relationId)->first();
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
     */
    public function forHostingAndRelationNotIn(Hosting $hosting, array $relationIds)
    {
        $collection = $this->model->where('hosting_id', $hosting->id);
        if (!empty($relationIds))
        {
            $collection->whereNotIn('relation_id', $relationIds);
        }
        return $collection;
    }
}
