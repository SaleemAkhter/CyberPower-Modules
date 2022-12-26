<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 18, 2018)
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

namespace ModulesGarden\WordpressManager\App\Modules\Plesk;


use ModulesGarden\WordpressManager\App\Interfaces\SslInterface;
use ModulesGarden\WordpressManager\App\Models\Installation;

/**
 * Description of Sll
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class Ssl implements SslInterface
{
    /**
     * @var array
     */
    private $params;
    /**
     * @var Installation
     */
    private $installation;

    /**
     * Ssl constructor.
     * @param array $params
     * @param Installation $installation
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }


    public function on(){
        //Do nothing
    }
    
    public function off(){
        //Do nothing
    }

    public function getInstallation()
    {
        return $this->installation;
    }

    public function setInstallation(Installation $installation)
    {
        $this->installation = $installation;
        return $this;
    }
}
