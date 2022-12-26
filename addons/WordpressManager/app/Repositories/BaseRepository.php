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

/**
 * Description of BaseRepository
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
abstract class BaseRepository
{
    /**
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    function __construct($model)
    {
        $this->model = $model;
    }
}
